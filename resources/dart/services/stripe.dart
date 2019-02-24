import "../models/registration.dart";
import "dart:html";
import 'dart:async';
import 'dart:convert';

class Stripe {
    final String Base = 'https://api.stripe.com/v1';
    String Public;

    Stripe(this.Public);

    Future GetToken(String card, int expMonth, int expYear) {
        return Request('tokens', 'POST', {
            'card': {
                'number': card,
                'exp_month': expMonth.toString(),
                'exp_year': expYear.toString()
            }
        });
    }

    Future GetBitcoinSource(int amount, String email) {
        return Request('sources', 'POST', {
            'type': 'bitcoin',
            'amount': amount,
            'currency': 'usd',
            'owner': {
                'email': email
            }
        });
    }

    Future CheckBitcoinSource(String sourceId, String secret) {
        return Request('sources/${sourceId}', 'GET', {
            'client_secret': secret
        });
    }

    /*
     * Makes a Stripe request and returns the result
     */
    Future Request(String endpoint, String method, Map body) async {
        body['key'] = Public;
        body['payment_user_agent'] = 'stripe.js/c180728';

        var parts = [];
        body.forEach((key, value) {
            if (value is Map) {                                                                                    
                Map mValue = value;                                                                                
                mValue.forEach((skey, elem) {
                    parts.add('${Uri.encodeQueryComponent(key)}[${Uri.encodeQueryComponent(skey)}]='
                            '${Uri.encodeQueryComponent(elem)}');                                                       
                });                                                                                                     
                                                                                                                        
                return;                                                                                                 
            }      

            if (value is Iterable) {
                Iterable iValue = value;
                iValue.forEach((elem) {
                    parts.add('${Uri.encodeQueryComponent(key)}[]='
                            '${Uri.encodeQueryComponent(elem)}');
                });

                return;
            }

            if (value == null) return;

            if (value is num || value is int) {
                value = value.toString();
            }
            parts.add('${Uri.encodeQueryComponent(key)}='
                '${Uri.encodeQueryComponent(value)}');
        });
        var formData = parts.join('&');

        Completer finalResult = new Completer();

        var completeNormal = (response) {
            if (response == null || response.response == null) return;
            finalResult.complete({'status': response.status, 'response': jsonDecode(response.response)});
        };

        if (method.toUpperCase() == 'GET') {
            HttpRequest.request(Base+'/'+endpoint+'?'+formData,
                    method: method.toUpperCase()
            ).catchError((error) => completeNormal(error.target))
            .then(completeNormal);
        } else {
            HttpRequest.request(Base+'/'+endpoint,
                    method: method.toUpperCase(),
                    requestHeaders: {"Content-Type": "application/x-www-form-urlencoded"},
                    sendData: formData
            ).catchError((error) => completeNormal(error.target))
            .then(completeNormal);
        }

        return finalResult.future;
    }
}
