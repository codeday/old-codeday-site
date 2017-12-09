import "dart:js";
import "dart:async";

Future swal({String title, String text, String type, String imageUrl: null}) {
    var onClose = new Completer();

    context.callMethod('swal', [
        new JsObject.jsify({
            "title": title,
            "text": text,
            "type": type,
            "imageUrl": imageUrl
        }), onClose.complete
    ]);

    return onClose.future;
}

Future swalRaw(params) {
    var onClose = new Completer();

    context.callMethod('swal', [ new JsObject.jsify(params), onClose.complete ]);

    return onClose.future;
}

Future error(text) {
    return swal(
        title: 'Error',
        text: text,
        type: 'error'
    );
}
