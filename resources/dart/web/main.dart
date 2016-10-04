import "dart:html";
import "pages/register.dart";
import "pages/event.dart";

void main() {
    String bodyClass = querySelector('body').className;
    var page;

    if (bodyClass.contains('event index')) {
        page = new EventPage();
    } else if (bodyClass.contains('event register')) {
        page = new RegisterPage();
    }
}
