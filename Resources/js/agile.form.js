window.Agile = window.Agile || {};

(function ($, Agile) {
    "use strict";

    Agile.form = {
        fixFormAction: function ($target, url) {
            var $form = $target.find('form');
            if (!$form.attr('action')) {
                $form.attr('action', url);
            }
        },

        preventLeave: function (message) {
            var formDataChanged = false;
            $(document).on('change', 'form.st-prevent-leave :input', function () {
                if ($(this).closest('form.st-prevent-leave').find('input[type=password]').length === 0) {
                    formDataChanged = true;
                }
            }).on('submit', 'form.st-prevent-leave', function () {
                formDataChanged = false;
            });

            window.onbeforeunload = function () {
                if (formDataChanged) {
                    return message;
                }
            };
        },

        gmapAutoComplete: {
            setup: function (field_id) {
                var formComponents = {
                    street_number: 'short_name',
                    route: 'long_name',
                    locality: 'long_name',
                    administrative_area_level_1: 'short_name',
                    administrative_area_level_2: 'short_name',
                    country: 'short_name',
                    postal_code: 'short_name'
                }, input = document.getElementById(field_id + '_formatted_address'), autocomplete;

                (function (input) {
                    var addListener = input.addEventListener || input.attachEvent;

                    function listener(type, origListener) {
                        var l,
                            suggestionSelected,
                            simulatedDownArrow;
                        if ("keydown" === type) {
                            l = function (event) {
                                suggestionSelected = 0 < $(".pac-item-selected").length;
                                if (13 === event.which) {
                                    event.preventDefault();
                                    if (!suggestionSelected) {
                                        simulatedDownArrow = $.Event("keydown", {keyCode: 40, which: 40});
                                        origListener.apply(input, [simulatedDownArrow]);
                                    }
                                }

                                origListener.apply(input, [event]);
                            };
                        } else {
                            l = origListener;
                        }
                        addListener.apply(input, [type, l]);
                    }

                    if (input.addEventListener) {
                        input.addEventListener = listener;
                    } else if (input.attachEvent) {
                        input.attachEvent = listener;
                    }
                }(input));

                function fill() {
                    var place = autocomplete.getPlace(),
                        i,
                        addressType,
                        val,
                        $c = $('#' + field_id);
                    if (!place.geometry) {
                        return;
                    }
                    $c.find(':input').val('');
                    $c.find('#' + field_id + '_latitude').val(place.geometry.location.lat());
                    $c.find('#' + field_id + '_longitude').val(place.geometry.location.lng());
                    $c.find('#' + field_id + '_formatted_address').val(place.formatted_address);
                    for (i = 0; i < place.address_components.length; i++) {
                        addressType = place.address_components[i].types[0];
                        if (formComponents[addressType]) {
                            val = place.address_components[i][formComponents[addressType]];
                            $c.find('#' + field_id + '_' + addressType).val(val);
                        }
                    }
                }

                autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['geocode']
                });
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    fill();
                });
            }
        },

        select2: {
            setup: function (field_id, options) {
                $('#' + field_id).select2(options);
            }
        },

        switcher: {
            setup: function (field_id, options) {
                $('#' + field_id).bootstrapSwitch(options);
            }
        }
    };
}(jQuery, window.Agile));
