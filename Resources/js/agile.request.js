window.Agile = window.Agile || {};

(function ($, Agile) {
    "use strict";

    var config = {
        timeoutError: "Server does not respond, please try again later",
        serverError: "An error has occurred, please try again later"
    };

    Agile.request = {
        setConfig: function (options) {
            $.extend(config, options);
        },

        handleRedirectResponse: function (xhr) {
            var json = xhr.responseJSON;
            if (json && json.redirect) {
                document.location.href = json.redirect;
                return true;
            }
            return false;
        },

        get: function (url, params, success, options) {
            return Agile.request.request('get', url, params, success, options);
        },

        post: function (url, data, success, options) {
            return Agile.request.request('post', url, data, success, options);
        },

        'delete': function (url, data, success, options) {
            return Agile.request.request('delete', url, data, success, options);
        },

        request: function (method, url, data, success, options) {
            return Agile.request.ajax($.extend({
                url: url,
                type: method,
                data: data,
                success: success
            }, options));
        },

        ajax: function (options) {
            if (options.error) {
                options._error = options.error;
                delete options.error;
            }

            var ajaxOptions =
                $.extend({
                    error: function (xhr, type, errorText) {
                        var s = xhr.status;
                        if (options._error) {
                            options._error.call(this, xhr, type, errorText);
                        }
                        if (s === 403) {
                            $(document).trigger({
                                type: "agile.request.forbidden",
                                errorText: errorText
                            });
                        } else if (type === 'error' && xhr.readyState > 0) {
                            Agile.request.alertError(config.serverError);
                        } else if (type === 'timeout') {
                            Agile.request.alertError(config.timeoutError);
                        }
                    }
                }, options);

            if (options.requiredLogin && !Agile.isAuthenticated()) {
                ajaxOptions.error({
                    status: 403
                }, null, "Forbidden");

                if (ajaxOptions.complete) {
                    ajaxOptions.complete({
                        status: 403
                    }, null, "Forbidden");
                }

                return;
            }

            return $.ajax(ajaxOptions);
        },

        alertError: function (error) {
            if (Agile.modal) {
                Agile.modal.alert('<p>' + (error || config.serverError) + '</p>');
            } else {
                alert(error || config.serverError);
            }
        }
    };
}(jQuery, window.Agile));
