/**
 * Created by Nguyen Tuan Linh on 2016-12-05.
 */
var _sessionTimeout = null;

function updateCsrfToken(csrfToken) {
    KATNISS_REQUEST_TOKEN = csrfToken;
    $('input[type="hidden"][name="_token"]').val(csrfToken);

    startSessionTimeout();
}

function startSessionTimeout() {
    if (isSet(_sessionTimeout)) {
        clearTimeout(_sessionTimeout);
    }
    _sessionTimeout = setTimeout(function () {
        console.log('session end');
        if (KATNISS_USER !== false && KATNISS_USER_REQUIRED) {
            x_modal_lock();
        }
        else {
            var api = new KatnissApi(true);
            api.get('user/csrf-token', null, function (failed, data, messages) {
                if (!failed) {
                    updateCsrfToken(data.csrf_token);
                }
            })
        }
    }, KATNISS_SESSION_LIFETIME + 1000); // plus to make sure session ending completely
}

startSessionTimeout();