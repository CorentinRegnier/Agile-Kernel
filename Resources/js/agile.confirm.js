(function ($) {
    "use strict";
    $.fn.AgileConfirm = function () {
        this.on("click", "a[data-confirm-action]", function (e) {
            if (!confirm($(this).data("confirm-action"))) {
                e.preventDefault();
            }
        });
        return this;
    };
}(jQuery));
