import 'dart:html';
import 'dart:async';
import 'dart:js';
import "package:js/js.dart";
import "../../services/api.dart";
import "../../services/stripe.dart";
import "../../services/swal.dart";
import "../../models/registration.dart";

class RegisterPage {
    Stripe stripe;
    String currentPromo = null;
    num defaultUnitPrice = 10;
    num unitPrice = 10;
    num displayedPrice = 0;
    num remainingCapacity = 100;

    dynamic btcSourceObj = null;
    Timer btcTimer = null;

    bool formDisabled = false;

    String promoCode = null;
    num promoRemainingUses = null;

    /**
     * Binds all handlers to page elements and loads data from the page.
     */
    RegisterPage(){
        // Get the default (no-promos-applied) price for the event, and update the display
        defaultUnitPrice = num.parse(querySelector('.payment').dataset['defaultUnitPrice']);
        unitPrice = defaultUnitPrice;
        refreshDisplayedPrice();

        // Get the remaining event capacity
        remainingCapacity = num.parse(querySelector('section.event').dataset['remaining']);

        // Set up Stripe
        stripe = new Stripe(querySelector('.card').dataset['stripePk']);

        // # Button Bindings
        // Bind the "add new attendee" and "remove last attendee" handlers
        querySelector('.add-attendee').onClick.listen((event) {
            if (formDisabled) return;
            addAttendee();
            refreshDisplayedPrice();
        });
        querySelector('.remove-attendee').onClick.listen((event) {
            if (formDisabled) return;
            removeAttendee();
            refreshDisplayedPrice();
        });

        querySelectorAll('.promo.link, .price .discount .label').onClick.listen((event) {
            if (formDisabled) return;
            showPromoPicker();
        });

        querySelector('.promo-picker button').onClick.listen((event) {
            event.stopPropagation();
            event.preventDefault();
            if (formDisabled) return;
            doApplyPromo();
        });

        querySelector('form').onSubmit.listen((event) {
            event.preventDefault();
            if (formDisabled) return;
            doPay();
        });

        querySelector('.pay-btc').onClick.listen((event) {
            if (formDisabled) return;
            doPayWithBtc();
        });
    }

    /**
     * Applies a promo code.
     */
    Future doApplyPromo() async {
        InputElement promoCodeElem = querySelector('input[name="promo"]');
        var code = promoCodeElem.value.trim();

        promoCode = null;
        promoRemainingUses = null;
        unitPrice = defaultUnitPrice;
        refreshDisplayedPrice();

        if (code.length == 0) {
            hidePromoPicker();
            return;
        }

        // Validate the promo code and get its information:
        var details;
        try {
            details = await api().GetPromotionDetails(code);
        } catch (exception) {
            error('Sorry, the promo code '+code+' does not exist.');
            hidePromoPicker();
            return;
        }

        // Check if it's already been used the maximum number of times:
        if (details['remaining_uses'] != null && details['remaining_uses'] == 0) {
            error('Sorry, '+code+' has already been used.');
            hidePromoPicker();
            return;
        }

        // Check if the user has too many tickets in their cart
        if (details['remaining_uses'] != null && details['remaining_uses'] < getFilledRegistrations().length) {
            error('The code '+code+' only allows '+details['remaining_uses'].toString()
                    +' uses, but you have '+getFilledRegistrations().length.toString()
                    +' tickets in your cart. Please remove some tickets to use'
                    +' this promo (you can order them without a code in a'
                    +' separate order).');
            return;
        }

        // Check if the code has expired
        if (details['expired']) {
            error('Sorry, the promo code '+code+' is expired.');
            hidePromoPicker();
            return;
        }

        // Code looks ok!
        promoCode = code;
        promoRemainingUses = details['remaining_uses'];
        unitPrice = details['cost'];
        refreshDisplayedPrice();
        hidePromoPicker();
        querySelector('.promo.link').style.display = 'none';
        swal(
            title: details['discount'].toString()+'% off!',
            text: 'We\'ve successfully applied the promo code '+code+'. Your'
            +' new total is \$'+displayedPrice.toStringAsFixed(2)+'.',
            type: null,
            imageUrl: '/assets/img/thumbsup.jpg'
        );

    }

    /**
     * Hides the promo code picker input, and clears the field.
     */
    void hidePromoPicker() {
        var promoPickerElem = querySelector('.promo-picker');
        var promoLinkElem = querySelector('.promo.link');
        InputElement promoCodeElem = querySelector('input[name="promo"]');

        promoCodeElem.value = '';
        promoPickerElem.style.display = 'none';
        promoLinkElem.style.display = 'inline-block';
    }

    /**
     * Shows the promo code picker input.
     */
    void showPromoPicker() {
        var promoPickerElem = querySelector('.promo-picker');
        var promoLinkElem = querySelector('.promo.link');
        promoPickerElem.style.display = 'block';
        promoLinkElem.style.display = 'none';
    }

    Future doPayWithBtc() async {
        disableForm();

        var complete = true;

        getRegistrationInputs(true).forEach((elem) {
            if (elem.id != "card_number" && elem.id != "exp" && (elem.value.trim().length < 1 || !elem.checkValidity())) complete = false;
        });

        if (!complete) {
            await swal(
                title: 'Error',
                text: 'Please complete all fields.',
                type: 'error'
            );
            enableForm();
            return;
        }

        var btcSource = await requestStripeBtcSource();
        btcSourceObj = btcSource['response'];
        
        btcTimer = new Timer.periodic(const Duration(milliseconds: 5000), checkBtcSource);

        var cancelled = await swalRaw({
            'allowOutsideClick': false,
            'title': "Complete your bitcoin purchase",
            'text': "Please send ${btcSourceObj['bitcoin']['amount'] / 100000000} BTC to ${btcSourceObj['bitcoin']['address']}",
            'imageUrl': "https://chart.googleapis.com/chart?cht=qr&chs=200x200&chld=L|0&chl=${Uri.encodeComponent(btcSourceObj['bitcoin']['uri'])}",
            'imageSize': "150x150",
            'confirmButtonText': "Cancel"
        });

        // everything below here runs if the dialog is cancelled
        enableForm();
        btcTimer.cancel();
        print("timer cancelled");
    }

    Future finalizePaymentWithBtc() async {
        return api().RegisterWithSource(
            registrations: getFilledRegistrations(),
            stripeSource: btcSourceObj['id'],
            quotedPrice: displayedPrice,
            promoCode: promoCode
        );
    }

    void checkBtcSource(Timer timer) async {
        var src = await stripe.CheckBitcoinSource(btcSourceObj['id'], btcSourceObj['client_secret']);
        var srcObj = src['response'];

        if (srcObj['status'] == 'chargeable') {
            timer.cancel();

            swalRaw({
                'allowOutsideClick': false,
                'title': "Processing transaction",
                'text': "This should only take a second...",
                'confirmButtonText': "Cancel"
            });

            var regResponse = await finalizePaymentWithBtc();

            if (regResponse['status'] == 200) {
                // Success
                swal(
                        title: 'You\'re in!',
                        text: 'You have successfully registered for CodeDay! A receipt has'+
                                ' been emailed to all ticket holders.',
                        type: 'success'
                );

                var params = new JsObject.jsify({
                    'app_id': querySelector('body').dataset['facebookAppId'],
                    'page_id': querySelector('body').dataset['facebookPageId'],
                    'ref': regResponse['ids'][0],
                    'user_ref': querySelector('body').dataset['userRef']
                });

                context['FB']['AppEvents'].callMethod("logEvent", ['MessengerCheckboxUserConfirmation', null, params]);

                // context['console'].callMethod("log", [querySelector('body').dataset['facebookAppId'], querySelector('body').dataset['facebookPageId'], regResponse['ids'][0], querySelector('body').dataset['userRef']]);

                querySelectorAll('.registration, .payment').style.display = 'none';
                querySelector('form .success').style.display = 'block';
                querySelectorAll('form .success a.download').forEach((elem) {
                    AnchorElement a = elem as AnchorElement;
                    a.href = a.href+regResponse['ids'].join(',');
                });

            } else {
                error(regResponse['message']);
                enableForm();
                return;
            }
        }
    }

    /**
     * Process to be completed when the user clicks the "Pay" button -- gets a
     * card token from Stripe (if the cost is above zero), then submits the
     * registration to Clear. Finally, if everything was successful, updates
     * the page to show a success.
     */
    Future doPay() async {
        disableForm();

        // Make sure all fields are filled out:
        var complete = true;
        getRegistrationInputs(true).forEach((elem) {
            if (elem.value.trim().length < 1 || !elem.checkValidity()) complete = false;
        });
        if (!complete) {
            await swal(
                title: 'Error',
                text: 'Please complete all fields.',
                type: 'error'
            );
            enableForm();
            return;
        }

        var stripeToken = null;
        if (displayedPrice > 0) {
            // Post to Stripe, and check if the response was successful:
            var tokenResponse = await requestStripeToken();
            if (tokenResponse['status'] != 200) { // ERROR ERROR ERROR
                error(tokenResponse['response']['error']['message']);
                enableForm();
                return;
            }
            stripeToken = tokenResponse['response']['id'];
        }

        // Submit the registration to Clear and check the result
        var regResponse;
        try {
            regResponse = await postRegistrationRequest(stripeToken);
        } catch (exception) {
            error('Something went wrong with our payment processor. Please contact support@codeday.org');
        }

        if (regResponse['status'] == 200) {
            // Success
            swal(
                    title: 'You\'re in!',
                    text: 'You have successfully registered for CodeDay! A receipt has'+
                            ' been emailed to all ticket holders.',
                    type: 'success'
            );

            var params = new JsObject.jsify({
                'app_id': querySelector('body').dataset['facebookAppId'],
                'page_id': querySelector('body').dataset['facebookPageId'],
                'ref': regResponse['ids'][0],
                'user_ref': querySelector('body').dataset['userRef']
            });

            context['FB']['AppEvents'].callMethod("logEvent", ['MessengerCheckboxUserConfirmation', null, params]);

            // context['console'].callMethod("log", [querySelector('body').dataset['facebookAppId'], querySelector('body').dataset['facebookPageId'], regResponse['ids'][0], querySelector('body').dataset['userRef']]);

            querySelectorAll('.registration, .payment').style.display = 'none';
            querySelector('form .success').style.display = 'block';
            querySelectorAll('form .success a.download').forEach((elem) {
                AnchorElement a = elem as AnchorElement;
                a.href = a.href+regResponse['ids'].join(',');
            });

        } else {
            error(regResponse['message']);
            enableForm();
            return;
        }
    }

    /**
     * Gets a list of all inputs involved in the registration process, for
     * checking if the form is complete, or for disabling during processing.
     */
    List<InputElement> getRegistrationInputs([bool requiredOnly = false]) {
        var elements = new List<InputElement>();
        elements.addAll(querySelectorAll('.attendee input'));

        if (displayedPrice > 0) {
            elements.addAll(querySelectorAll('#card_number, #exp'));
        }

        if (!requiredOnly) {
            elements.add(querySelector('input[name="promo"]'));
        }

        return elements;
    }

    /**
     * Disables editing and submission of the form.
     */
    void disableForm() {
        formDisabled = true;
        getRegistrationInputs().forEach((elem) => elem.disabled = true);
    }

    /**
     * Enables editing and submission of the form.
     */
    void enableForm() {
        formDisabled = false;
        getRegistrationInputs().forEach((elem) => elem.disabled = false);
    }

    Future requestStripeBtcSource() async {
        var completer = new Completer();
        var regs = getFilledRegistrations();
        completer.complete(await stripe.GetBitcoinSource(displayedPrice * 100, regs.first.Email));
        return completer.future;
    }

    /**
     * Requests a Stripe token for the card currently displayed on the page.
     */
    Future requestStripeToken() async {
        var completer = new Completer();

        InputElement cardNumber = querySelector('#card_number');
        InputElement cardExp = querySelector('#exp');

        // Parse out the expiration date
        var expParts = cardExp.value.split('/');
        int expDay;
        int expYear;
        
        expDay = int.parse(expParts[0].trim());
        expYear = int.parse(expParts[1].trim());

        // Send the request
        try {
            completer.complete(await stripe.GetToken(cardNumber.value, expDay, expYear));
        } catch (exception, stack) {
            completer.complete({
                "status": 402,
                "response": {"error": {
                    "type": "card_error",
                    "code": "invalid",
                    "message": "Your card's information is invalid."
                }}});
        }

        return completer.future;
    }

    /**
     * Submits a request to Clear to process the registration with the
     * information currently shown on the page.
     */
    Future postRegistrationRequest([String stripeToken = null]) {
        return api().Register(
            registrations: getFilledRegistrations(),
            stripeToken: stripeToken,
            quotedPrice: displayedPrice,
            promoCode: promoCode
        );
    }

    /**
     * Adds an attendee to the page.
     */
    void addAttendee() {
        // Check to make sure the user doesn't have a promo applied with a maximum
        // number of users.
        if (promoRemainingUses != null &&
                getFilledRegistrations().length == promoRemainingUses) {
            error( "The promotion "+promoCode+" only has "+promoRemainingUses.toString()
                    +" uses remaining. If you want to register additional"
                    +" users without this code, you will need to submit"
                    +" two orders.");
            return;
        }

        // Check to make sure this wouldn't exceed the event capacity:
        if (getFilledRegistrations().length >= remainingCapacity) {
            error("Sorry, we only have room for "+remainingCapacity.toString()
                    +" more attendees right now.");
            return;
        }

        var attendeeTemplate = querySelector('.attendee').clone(true);
        ElementList<InputElement> fields = attendeeTemplate.querySelectorAll('input');
        fields.forEach((InputElement)=>InputElement.value = '');

        querySelector('.registration').insertBefore(attendeeTemplate, querySelector('.add-attendee'));

        var attendees = querySelectorAll('.registration .attendee');
        if (attendees.length > 1) {
            querySelector('.remove-attendee').style.display = 'inline-block';
        }
    }

    /**
     * Removes the last attendee from the page.
     */
    void removeAttendee()
    {
        var attendees = querySelectorAll('.registration .attendee');
        attendees[attendees.length - 1].remove();

        if (attendees.length <= 2) {
            querySelector('.remove-attendee').style.display = 'none';
        }
    }

    /**
     * Gets a List containing Registration objects for all registrations listed
     * on the page.
     */
    List<Registration> getFilledRegistrations() {
        var attendees = querySelectorAll('.attendee');

        return attendees.map((a) {
            InputElement fnElem = a.querySelector('input[name="first_name"]');
            InputElement lnElem = a.querySelector('input[name="last_name"]');
            InputElement emElem = a.querySelector('input[name="email"]');

            return new Registration(
                    fnElem.value,
                    lnElem.value,
                    emElem.value
            );
        });
    }

    /**
     * Syncs the price displayed on the page with the current unit price and
     * number of tickets, and updates the displayedPrice property (which is sent
     * with the registration request to ensure the user isn't overcharged.)
     */
    void refreshDisplayedPrice()
    {
        var regCount = getFilledRegistrations().length;
        var subtotal = regCount * defaultUnitPrice;
        displayedPrice = regCount * unitPrice;

        var subtotalLabelElem = querySelector('.price .subtotal .label');
        var subtotalElem = querySelector('.price .subtotal .amount');

        var discountContainerElem = querySelector('.price .discount');
        var discountLabelElem = querySelector('.price .discount .label');
        var discountElem = querySelector('.price .discount .amount');

        var totalElem = querySelector('.price .total .amount');

        subtotalLabelElem.text = 'Attendee (x'+regCount.toString()+')';
        subtotalElem.text = subtotal.toStringAsFixed(2);

        if (promoCode != null) {
            discountLabelElem.text = 'Discount - "'+promoCode+'"';
            discountElem.text = (subtotal - displayedPrice).toStringAsFixed(2);
            discountContainerElem.style.display = 'block';
        } else {
            discountLabelElem.text = 'No Discount';
            discountElem.text = '0.00';
            discountContainerElem.style.display = 'none';
        }

        totalElem.text = displayedPrice.toStringAsFixed(2);

        var cardElem = querySelector('.card');
        if (displayedPrice == 0) {
            cardElem.style.display = 'none';
        } else {
            cardElem.style.display = 'block';
        }
    }
}
