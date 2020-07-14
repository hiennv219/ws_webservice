/**
 * Created by Nguyen Tuan Linh on 2016-12-05.
 */
function KatnissApiMessages(messages) {
    this.messages = isArray(messages) ? messages : (isString(messages) ? [messages] : []);
}
KatnissApiMessages.prototype.hasAny = function () {
    return this.messages.length > 0;
};
KatnissApiMessages.prototype.all = function () {
    return this.messages;
};
KatnissApiMessages.prototype.first = function () {
    return this.hasAny() ? this.messages[0] : '';
};
function KatnissApi(isWebApi) {
    this.REQUEST_TYPE_POST = 'post';
    this.REQUEST_TYPE_GET = 'get';
    this.REQUEST_TYPE_PUT = 'put';
    this.REQUEST_TYPE_DELETE = 'delete';

    this.isWebApi = isSet(isWebApi) && isWebApi === true;
}
KatnissApi.prototype.switchToAppApi = function () {
    this.isWebApi = false;
};
KatnissApi.prototype.switchToWebApi = function () {
    this.isWebApi = true;
};
KatnissApi.prototype.buildUrl = function (relativePath) {
    var apiUrl = !this.isWebApi ? KATNISS_API_URL : KATNISS_WEB_API_URL;
    return beginsWith(relativePath, apiUrl) ?
        relativePath : apiUrl + '/' + relativePath;
};
KatnissApi.prototype.buildParams = function (params) {
    if (isUnset(params)) params = {};
    if (this.isWebApi) {
        if (isObject(params, 'FormData')) {
            params.append('_token', KATNISS_REQUEST_TOKEN);
        }
        else {
            params._token = KATNISS_REQUEST_TOKEN;
        }
        return params;
    }

    if (isObject(params, 'FormData')) {
        params.append('_app', JSON.stringify(KATNISS_APP));
        params.append('_settings', JSON.stringify(KATNISS_SETTINGS));
    }
    else {
        params._app = KATNISS_APP;
        params._settings = KATNISS_SETTINGS;
    }
    return params;
};
KatnissApi.prototype.buildOptions = function (requestType, params, options) {
    if (!isSet(options)) {
        options = {};
    }
    if (!isSet(options.dataType)) {
        options.dataType = 'json';
    }
    if (isObject(params, 'FormData')) {
        options.processData = false;
        options.contentType = false;
        if (requestType == 'put') {
            params.append('_method', requestType);
            requestType = 'post';
        }
    }
    options.type = requestType;
    options.data = this.buildParams(params);
    return options;
};
KatnissApi.prototype.beforeRequest = function () {
    if (this.isWebApi) startSessionTimeout();
};
KatnissApi.prototype.get = function (relativePath, params, done, fail, always) {
    this.beforeRequest();
    this.promise(
        $.get(
            this.buildUrl(relativePath),
            this.buildParams(params)
        ),
        done, fail, always
    );
};
KatnissApi.prototype.post = function (relativePath, params, done, fail, always) {
    this.beforeRequest();
    this.promise(
        $.post(
            this.buildUrl(relativePath),
            this.buildParams(params)
        ),
        done, fail, always
    );
};
KatnissApi.prototype.put = function (relativePath, params, done, fail, always) {
    params._method = 'put';
    this.post(relativePath, params, done, fail, always);
};
KatnissApi.prototype.delete = function (relativePath, params, done, fail, always) {
    params._method = 'delete';
    this.post(relativePath, params, done, fail, always);
};
KatnissApi.prototype.request = function (relativePath, requestType, params, options,
                                         done, fail, always) {
    this.beforeRequest();
    this.promise(
        $.ajax(
            this.buildUrl(relativePath),
            this.buildOptions(requestType, params, options)
        ),
        done, fail, always
    );
};
KatnissApi.prototype.promise = function (ajax, done, fail, always) {
    var _this = this;
    ajax.done(function (response) {
        if (isSet(done)) {
            done.call(
                _this,
                _this.isFailed(response),
                _this.data(response),
                _this.messages(response)
            );
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (isSet(fail)) {
            fail.call(_this, textStatus, errorThrown);
        }
    }).always(function () {
        if (isSet(always)) {
            always.call(_this);
        }
    })
};
KatnissApi.prototype.isFailed = function (response) {
    return isSet(response._success) && response._success != true;
};
KatnissApi.prototype.data = function (response) {
    return isSet(response._data) ? response._data : null;
};
KatnissApi.prototype.messages = function (response) {
    return new KatnissApiMessages(response._messages);
};