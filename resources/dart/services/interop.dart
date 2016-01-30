import "dart:js";

JsFunction jsfn(Function callable) {
    var object = new JsObject(context['Object']);
    object['m'] = callable;
    return object['m'];
}

