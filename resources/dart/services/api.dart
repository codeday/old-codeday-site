import "../models/registration.dart";
import "dart:html";
import 'dart:async';
import 'dart:convert';

class Api {
    String Base;
    String Public;
    String Event;

    Api() {
        Base = querySelector('body').dataset['apiBase'];
        Public = querySelector('body').dataset['apiPublic'];
        Event = querySelector('body').dataset['event'];
    }

    /*
     * Registers the specified attendees for the event.
     */
    Future Register({List<Registration> registrations, String stripeToken, num quotedPrice, num quotedTax, String promoCode: null}) {
        return Request('register/'+Event+'/register', 'POST', {
            'card_token': stripeToken,
            'quoted_price': quotedPrice,
            'quoted_tax': quotedTax,
            'first_names': registrations.map((r)=>r.FirstName),
            'last_names': registrations.map((r)=>r.LastName),
            'emails': registrations.map((r)=>r.Email),
            'code': promoCode
        });
    }

    Future RegisterWithSource({List<Registration> registrations, String stripeSource, num quotedPrice, num quotedTax, String promoCode: null}) {
        return Request('register/'+Event+'/register', 'POST', {
            'bitcoin_source': stripeSource,
            'quoted_price': quotedPrice,
            'quoted_tax': quotedTax,
            'first_names': registrations.map((r)=>r.FirstName),
            'last_names': registrations.map((r)=>r.LastName),
            'emails': registrations.map((r)=>r.Email),
            'code': promoCode
        });
    }

    /*
     * Gets details about the specified promotion code. Returns an object
     * containing { bool expired, num remaining_uses, num cost, num discount }
     */
    Future GetPromotionDetails(String code) {
        return Request('register/'+Event+'/promotion', 'GET', {
            'code': code
        });
    }

    /*
     * Subscribes to notifications for event registration opening.
     */
    Future NotifySubscribe(String email)
    {
        return Request('notify/subscribe', 'POST', {
            'email': email,
            'event': Event
        });
    }

    /*
     * Makes an API request and returns the result
     */
    Future Request(String endpoint, String method, var body) async {
        body['access_token'] = Public;

        var parts = [];
        body.forEach((key, value) {
            if (value is Iterable) {
                Iterable iValue = value;
                iValue.forEach((elem) {
                    parts.add('${Uri.encodeQueryComponent(key)}[]='
                            '${Uri.encodeQueryComponent(elem)}');
                });

                return;
            }

            if (value == null) return;

            if (value is num) {
                value = value.toString();
            }
            parts.add('${Uri.encodeQueryComponent(key)}='
                '${Uri.encodeQueryComponent(value)}');
        });
        var formData = parts.join('&');

        Completer finalResult = new Completer();

        if (method.toUpperCase() == 'GET') {
            HttpRequest response = await HttpRequest.request(Base+'/'+endpoint+'?'+formData,
                    method: method.toUpperCase()
            );
            finalResult.complete(jsonDecode(response.response));
        } else {
            HttpRequest response = await HttpRequest.request(Base+'/'+endpoint,
                    method: method.toUpperCase(),
                    requestHeaders: {"Content-Type": "application/x-www-form-urlencoded"},
                    sendData: formData
            );
            finalResult.complete(jsonDecode(response.response));
        }

        return finalResult.future;
    }
}

var _api = null;
Api api() {
    if (_api == null) {
        _api = new Api();
    }

    return _api;
}