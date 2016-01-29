import 'dart:html';
import 'dart:async';
import 'dart:js';
import "package:js/js.dart";
import "../api.dart";
import "../registration.dart";
import "../swal.dart";

class Register {
    String stripeApiPublic;
    String currentPromo = null;
    num defaultUnitPrice = 10;
    num unitPrice = 10;
    num displayedPrice = 0;

    bool formDisabled = false;

    String promoCode = null;
    num promoRemainingUses = null;

    /**
     * Binds all handlers to page elements and loads data from the page.
     */
    Register(){
        // Get the default (no-promos-applied) price for the event, and update the display
        defaultUnitPrice = num.parse(querySelector('.payment').dataset['defaultUnitPrice']);
        unitPrice = defaultUnitPrice;
        refreshDisplayedPrice();

        // Set up Stripe
        stripeApiPublic = querySelector('.card').dataset['stripePk'];
        context['Stripe'].callMethod('setPublishableKey', [stripeApiPublic]);

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
            if (formDisabled) return;
            doApplyPromo();
        });
        querySelector('.pay').onClick.listen((event) {
            if (formDisabled) return;
            doPay();
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
            type: 'success',
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
            if (elem.value.trim().length < 1) complete = false;
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
                await error(tokenResponse['response']['error']['message']);
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
                            ' been emailed to all ticket holders. You will NOT receive'+
                            ' a ticket -- we will check you in by name at the door.',
                    type: 'success'
            );
        } else {
            error(regResponse['message']);
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

    /**
     * Requests a Stripe token for the card currently displayed on the page.
     */
    Future requestStripeToken() async {
        InputElement cardNumber = querySelector('#card_number');
        InputElement cardExp = querySelector('#exp');

        var completer = new Completer();

        var cardInfo = new JsObject.jsify({
            "number": cardNumber.value,
            "exp": cardExp.value
        });
        var callback = allowInterop((status, response) => completer.complete({
            "status": status,
            "response": response
        }));

        try {
            context['Stripe']['card'].callMethod('createToken', [cardInfo, callback]);
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