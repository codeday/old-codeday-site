import "dart:html";
import "pages/register.dart";

void main() {
    String bodyClass = querySelector('body').className;
    var page;

    switch(bodyClass) {
        case 'event register':
            page = new Register();
            break;
    }
}