if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

import Autosize from 'autosize';
import Inputmask from 'inputmask';
import * as Range from 'nouislider';
import 'nouislider/distribute/nouislider.min.css';
const FORM_VALIDATOR = require ('jquery-validation');
require ('jquery-validation/dist/localization/messages_' + LOCALE + '.min');

if (typeof ACTIVATED_FUNCTIONS.form_watcher !== 'undefined') {
    $.fn.areYouSure = function (options) {
        var settings = $.extend(
            {
                'message': 'You have unsaved changes!',
                'dirtyClass': 'dirty',
                'change': null,
                'silent': false,
                'addRemoveFieldsMarksDirty': false,
                'fieldEvents': 'change keyup propertychange input',
                'fieldSelector': ":input:not(input[type=submit]):not(input[type=button])"
            }, options);

        var getValue = function ($field) {
            if ($field.hasClass('ays-ignore')
                || $field.hasClass('aysIgnore')
                || $field.attr('data-ays-ignore')
                || $field.attr('name') === undefined) {
                return null;
            }

            if ($field.is(':disabled')) {
                return 'ays-disabled';
            }

            var val;
            var type = $field.attr('type');
            if ($field.is('select')) {
                type = 'select';
            }

            switch (type) {
                case 'checkbox':
                case 'radio':
                    val = $field.is(':checked');
                    break;
                case 'select':
                    val = '';
                    $field.find('option').each(function (o) {
                        var $option = $(this);
                        if ($option.is(':selected')) {
                            val += $option.val();
                        }
                    });
                    break;
                default:
                    val = $field.val();
            }

            return val;
        };

        var storeOrigValue = function ($field) {
            $field.data('ays-orig', getValue($field));
        };

        var checkForm = function (evt) {

            var isFieldDirty = function ($field) {
                var origValue = $field.data('ays-orig');
                if (undefined === origValue) {
                    return false;
                }
                return (getValue($field) != origValue);
            };

            var $form = ($(this).is('form'))
                ? $(this)
                : $(this).parents('form');

            // Test on the target first as it's the most likely to be dirty
            if (isFieldDirty($(evt.target))) {
                setDirtyStatus($form, true);
                return;
            }

            var $fields = $form.find(settings.fieldSelector);

            if (settings.addRemoveFieldsMarksDirty) {
                // Check if field count has changed
                var origCount = $form.data("ays-orig-field-count");
                if (origCount != $fields.length) {
                    setDirtyStatus($form, true);
                    return;
                }
            }

            // Brute force - check each field
            var isDirty = false;
            $fields.each(function () {
                var $field = $(this);
                if (isFieldDirty($field)) {
                    isDirty = true;
                    return false; // break
                }
            });

            setDirtyStatus($form, isDirty);
        };

        var initForm = function ($form) {
            var fields = $form.find(settings.fieldSelector);
            $(fields).each(function () {
                storeOrigValue($(this));
            });
            $(fields).unbind(settings.fieldEvents, checkForm);
            $(fields).bind(settings.fieldEvents, checkForm);
            $form.data("ays-orig-field-count", $(fields).length);
            setDirtyStatus($form, false);
        };

        var setDirtyStatus = function ($form, isDirty) {
            var changed = isDirty != $form.hasClass(settings.dirtyClass);
            $form.toggleClass(settings.dirtyClass, isDirty);

            // Fire change event if required
            if (changed) {
                if (settings.change) settings.change.call($form, $form);

                if (isDirty) $form.trigger('dirty.areYouSure', [$form]);
                if (!isDirty) $form.trigger('clean.areYouSure', [$form]);
                $form.trigger('change.areYouSure', [$form]);
            }
        };

        var rescan = function () {
            var $form = $(this);
            var fields = $form.find(settings.fieldSelector);
            $(fields).each(function () {
                var $field = $(this);
                if (!$field.data('ays-orig')) {
                    storeOrigValue($field);
                    $field.bind(settings.fieldEvents, checkForm);
                }
            });
            // Check for changes while we're here
            $form.trigger('checkform.areYouSure');
        };

        var reinitialize = function () {
            initForm($(this));
        }

        if (!settings.silent && !window.aysUnloadSet) {
            window.aysUnloadSet = true;
            $(window).bind('beforeunload', function () {
                $dirtyForms = $("form").filter('.' + settings.dirtyClass);
                if ($dirtyForms.length == 0) {
                    return;
                }
                // Prevent multiple prompts - seen on Chrome and IE
                if (navigator.userAgent.toLowerCase().match(/msie|chrome/)) {
                    if (window.aysHasPrompted) {
                        return;
                    }
                    window.aysHasPrompted = true;
                    window.setTimeout(function () {
                        window.aysHasPrompted = false;
                    }, 900);
                }
                return settings.message;
            });
        }

        return this.each(function (elem) {
            if (!$(this).is('form')) {
                return;
            }
            var $form = $(this);

            $form.submit(function () {
                $form.removeClass(settings.dirtyClass);
            });
            $form.bind('reset', function () {
                setDirtyStatus($form, false);
            });
            // Add a custom events
            $form.bind('rescan.areYouSure', rescan);
            $form.bind('reinitialize.areYouSure', reinitialize);
            $form.bind('checkform.areYouSure', checkForm);
            initForm($form);
        });
    };
}

$.Form = {
    init: function () {
        this.autosize();
        this.configure();
        this.mask();
        this.range();
        this.watch();
    },
    autosize: function () {
        Autosize($('textarea'));
    },
    configure: function () {
        FORM_VALIDATOR.validator.setDefaults({
            ignore: ':hidden:not(.validate)',
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
            },
        });

        FORM_VALIDATOR.validator.addMethod('regex', function (value, element) {
            var pattern = $(element).prop('pattern');
            if (!pattern || this.optional(element)) {
                return true;
            }
            return new RegExp(pattern).test(value);
        }, VALIDATOR_TRANSLATIONS.regex);

        $('body').find('form:not(.no-validate)').each(function () {
            $.Form.validate($(this));
        });
    },
    mask: function () {
        Inputmask().mask('[data-inputmask]');
    },
    range: function () {
        var containers = $('.nouislider-container');
        containers.each(function () {
            var selector = $(this)[0];
            var input = $(this).find('input.nouislider');

            Range.create(selector, {
                start: input.data('double') === 'data-double' ? JSON.parse(input.val()) : [input.val()],
                connect: input.data('double') === 'data-double' ? true : 'lower',
                step: parseInt(input.data('step')),
                range: {
                    'min': [parseInt(input.data('min'))],
                    'max': [parseInt(input.data('max'))]
                },
                pips: {
                    mode: 'steps',
                    stepped: true,
                    density: 4
                },
            });
            $.Form.rangeValue(selector, input);
        });
    },
    rangeValue: function (range, input) {
        range.noUiSlider.on('update', function () {
            input.val(range.noUiSlider.get());
            if (typeof ACTIVATED_FUNCTIONS.form_watcher !== 'undefined') {
                input.closest('form').trigger('checkform.areYouSure');
            }
        });
    },
    validate: function (form) {
        FORM_VALIDATOR(form).validate({
            highlight: function (input) {
                $(input).parents('.form-group').addClass('has-error');
                $(input).parents('.form-line').addClass('error').removeClass('focused, success');
                $(input).trigger('input.validate.invalid', {input,  form});
            },
            unhighlight: function (input) {
                $(input).parents('.form-group').removeClass('has-error');
                $(input).parents('.form-line').removeClass('error').addClass('focused, success');
                $(input).trigger('input.validate.valid', {input,  form});
            },
        });

        FORM_VALIDATOR(form).find('[data-inputmask]').rules('add', {
            regex: true
        });
    },
    watch: function () {
        if (typeof ACTIVATED_FUNCTIONS.form_watcher !== 'undefined') {
            $('form:not(.no-watch)').areYouSure({
                fieldSelector: ":input:not(input[type=submit]):not(input[type=button])",
                change: function() {
                    // Enable save button only if the form is dirty. i.e. something to save.
                    if ($(this).hasClass('dirty')) {
                        $(this).find('[type="submit"]').removeAttr('disabled');
                    } else {
                        $(this).find('[type="submit"]').attr('disabled', 'disabled');
                    }
                }
            });
        }
    }
};

$(document).ready(function () {
    $.Form.init();
});