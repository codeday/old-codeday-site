import "dart:html";
import "pages/register.dart";
import "pages/event.dart";

void main() {
    String bodyClass = querySelector('body').className;
    var page;

    switch(bodyClass) {
        case 'event index':
            print('foo');
            page = new EventPage();
            break;
        case 'event register':
            page = new RegisterPage();
            break;
    }
}
