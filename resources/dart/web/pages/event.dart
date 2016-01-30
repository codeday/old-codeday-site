import "dart:async";
import "dart:html";
import "../../services/api.dart";
import "../../services/swal.dart";

class EventPage {
    EventPage() {
        querySelector('.notify-signup').onSubmit.listen((event) async {
            event.preventDefault();
            InputElement notifyEmail = querySelector('.notify-signup input[name="email"]');
            var result = await doNotifySubscribe(notifyEmail.value);

            if (result['status'] == 200) {
                swal(
                    title: 'Subscribed!',
                    text: 'We will let you know as soon as registrations open.',
                    type: 'success'
                );
            } else {
                error('Something went wrong when we tried to subscribe you to'
                        +' notifications. You may already be subscribed. If'
                        + 'not, please contact support@codeday.org');
            }

            notifyEmail.value = '';
        });
    }

    Future doNotifySubscribe(String email) {
        return api().NotifySubscribe(email);
    }
}
