/**
 * Created by Nguyen Tuan Linh on 2016-12-05.
 */
function strRepeat(string, repeat) {
    for (var i = 0; i < repeat - 1; ++i) {
        string += string;
    }
    return string;
}

function toDigits(digit, minLength) {
    if (typeof minLength === 'undefined') {
        minLength = 2;
    }
    var max = Math.pow(10, minLength - 1);
    return digit < max ? strRepeat('0', minLength - 1) + digit : digit;
}

function urlParam(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    }
    else {
        return decodeURIComponent(results[1]) || 0;
    }
}

function isUnset(variable) {
    return variable == undefined || typeof variable === 'undefined' || variable == null;
}

function isSet(variable) {
    return !isUnset(variable);
}

function isString(variable) {
    return typeof variable === 'string';
}

function isObject(variable, objectName) {
    objectName = isUnset(objectName) ? '[object]' : '[object ' + objectName + ']';
    return isSet(variable) && Object.prototype.toString.call(variable) === objectName;
}

function isArray(variable) {
    return isObject(variable, 'Array');
}

function beginsWith(strSrc, strWith) {
    if (isSet(strSrc)) {
        return strSrc.toString().indexOf(strWith) == 0;
    }
    return false;
}

function nl2br(str) {
    return str.replace(/\r*\n/g, '<br>');
}

function htmlspecialchars(str) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return str.replace(/[&<>"']/g, function (m) {
        return map[m];
    });
}

function strReplaceMany(text, replaces) {
    for (var prop in replaces) {
        text = text.replace(new RegExp('{' + prop + '}', 'g'), replaces[prop]);
    }
    return text;
}