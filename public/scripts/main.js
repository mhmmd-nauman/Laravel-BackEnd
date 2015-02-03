/*
 * SPASON initial app and helpers
 */


var SPASON = SPASON || {};

SPASON.namespace = function (nsString) {
    var parts = nsString.split('.'),
        parent = SPASON,
        i;

        if (parts[0] === 'SPASON') {
            parts = parts.slice(1);
        }
        for (i = 0; i < parts.length; i += 1) {
            if (typeof parent[parts[i]] === 'undefined') {
                parent[parts[i]] = {};
            }
            parent = parent[parts[i]];
        }
    return parent;
};

// create config object for data from backend
SPASON.namespace('config');


// minimal template
SPASON.template = function(html, options) {
    var re = /<%([^%>]+)?%>/g, reExp = /(^( )?(if|for|else|switch|case|break|{|}))(.*)?/g, code = 'var r=[];\n', cursor = 0;
    var add = function(line, js) {
        js? (code += line.match(reExp) ? line + '\n' : 'r.push(' + line + ');\n') :
            (code += line != '' ? 'r.push("' + line.replace(/"/g, '\\"') + '");\n' : '');
        return add;
    };
    while(match = re.exec(html)) {
        add(html.slice(cursor, match.index))(match[1], true);
        cursor = match.index + match[0].length;
    }
    add(html.substr(cursor, html.length - cursor));
    code += 'return r.join("");';
    return new Function(code.replace(/[\r\t\n]/g, '')).apply(options);
};


if ($('#header-secondary').length) {
    $('#header-secondary').affix({
        offset: {
            top: $('#header-secondary').offset().top
        }
    });
}


if ($('#overview-dates').length) {
    $('#overview-dates').affix({
        offset: {
            top: $('#overview-dates').offset().top - 60,
            bottom: function () {
                var offsetBottom = function() {
                    var pageHeight = $('.l-wrap').outerHeight(),
                        offset = $('.overview-summary').offset().top + $('.overview-summary').height() - 1;

                    return pageHeight - offset;
                };

                $(window).on('scroll', function() {
                    $('#overview-dates').data('bs.affix').options.offset.bottom = offsetBottom();
                });
                $(window).on('load', function() {
                    $('#overview-dates').data('bs.affix').options.offset.bottom = offsetBottom();
                });

                return (this.bottom = offsetBottom());
            }
        }
    });
}

if ($('#overview-review').length) {
    $('#overview-review').affix({
        offset: {
            top: $('#overview-review').offset().top - $('#overview-dates').offset().top / 2
        }
    });
}


if ($('#package-book').length) {
    $('#package-book').affix({
        offset: {
            top: $('#package-book').offset().top - 60,
        }
    });
}





var searchFormReset = {
    el: {
        form: '.search-form',
        field: '#search-form-field',
        reset: '#search-form-reset'
    },

    events: function() {
        var self = this;

        $(self.el.field).on('keyup', function() {
            self.toggleReset();
        });

        $(self.el.reset).on('click', function() {
            setTimeout(function() {
                self.clearField();
                $(self.el.form).submit();
                self.toggleReset();

            }, 20);
        });
    },

    init: function() {
        this.toggleReset();
        this.events();
    },

    isEmpty: function() {
        return !$(this.el.field).val().length;
    },

    toggleReset: function() {
        if (this.isEmpty()) {
            $(this.el.reset).removeClass('is-visible');
        } else {
            $(this.el.reset).addClass('is-visible');
        }
    },

    clearField: function() {
        $(this.el.field).val('');
    }
};
if ($('#search-form-field').length) {
    searchFormReset.init();
}


$(function () {
    //$('.datepicker-box').datepicker({weekStart: 1,language: 'sv'});
    if ($('select').length) {
        $('select').selectpicker();
    }
});
$(document).ready(function(){
    var visibleMenu = false;

    $(".show-menu").click(function(e) {
        if(visibleMenu === false){
            e.preventDefault();
            $(".site-menu").animate({right: "0px"});
            visibleMenu = true;
            // lock scroll position, but retain settings for later
            var scrollPosition = [
            self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
            self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
            ];
            var html = jQuery('html'); // it would make more sense to apply this to body, but IE7 won't have that
            html.data('scroll-position', scrollPosition);
            html.data('previous-overflow', html.css('overflow'));
            html.css('overflow', 'hidden');
            window.scrollTo(scrollPosition[0], scrollPosition[1]);
            var overlay = jQuery('<div id="overlay"> </div>');
            overlay.appendTo(document.body);
            overlay.click(function(e) {
                e.preventDefault();
                hideMenu();
            });
        }
    });

    $(".hide-menu").click(function(e) {
        e.preventDefault();
        hideMenu();
    });


$(window).bind("touchstart, scroll",function() {
        hideMenu();
    });

    function hideMenu() {
        if(visibleMenu === true){
            $("#overlay").remove();
            $(".site-menu").animate({right: "-200px"});
            visibleMenu = false;
            // un-lock scroll position
            var html = jQuery('html');
            var scrollPosition = html.data('scroll-position');
            html.css('overflow', html.data('previous-overflow'));
            window.scrollTo(scrollPosition[0], scrollPosition[1]);
        }
    }
});




$(function () {

    var calendarNights = $('select.nights-amount').val();

    if ($('.calendar').length) {
        var spCallendar = {
            nightsCount: calendarNights,
            startDate: null,

            setNightsCount: function(newNightsCount){
                $(".calendar").ionCalendar('update', {
                    daySpan: Number(newNightsCount),
                    onClick: function (date) {
                        spCallendar.startDate = moment(date).format('L');
                    }
                });
            }
        };

        var daysInAdvance = calendarData.daysInAdvance || 0;

        $('.calendar').ionCalendar({
            daySpan: parseInt(spCallendar.nightsCount),
            startDate: moment().add(daysInAdvance+1, 'days'),
            startFixedDate: moment().add(daysInAdvance+1, 'days'),
            availableDays: (calendarData.availableWeekDays.length) ? calendarData.availableWeekDays : [],
            onClick: function(date) {
                var days = calendarNights;
                var from = moment(date).format('DD-MM-YYYY');
                var to = moment(date).add('days', days).format('DD-MM-YYYY');

                $('.js-date-from').val(from);
                $('.js-date-to').val(to);
            }
        });

        $('.nights-amount').on('change', function(){
            var nights = $(this).val();

            spCallendar.setNightsCount(nights);
        });
    }
});

var getTranslation = function(translationObj, path) {
    if ( translationObj ) {
        var pathParts = path.split('.');
        var newObj = translationObj[pathParts[0]];

        if(pathParts[1]){
            pathParts.splice(0,1);
            var newString = pathParts.join('.');
            return getTranslation(newObj,newString);
        }

        return newObj;
    }

    return path;
}
/*
$('.js-booking-form').bootstrapValidator({
    trigger: 'blur',
    fields: {
        discount: {
            validators: {
                remote: {
                    message: getTranslation(jsTranslations, 'booking.validation.discountNotValid'),
                    url: '/hotel/booking/validate_discount?valid'
                }
            }
        }
    }
});
*/

if ($('#booking-form').length) {
    $('#booking-form').bootstrapValidator({
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: getTranslation(jsTranslations, 'booking.validation.nameRequired')
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: getTranslation(jsTranslations, 'booking.validation.emailRequired')

                    },
                    emailAddress: {
                        message: getTranslation(jsTranslations, 'booking.validation.emailNotValid')
                    }
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: getTranslation(jsTranslations, 'booking.validation.phoneRequired')
                    }
                }
            }
        }
    });
}
// if ($('#booking-discount').length) {
//     $('#booking-form').bootstrapValidator({
//         fields: {
//             discount: {
//                 validators: {
//                     notEmpty: {
//                         message:
//                     }
//                 }
//             }
//         }
//     });
// }


$('.js-packages-filter-item').on('click', function(e) {
    e.preventDefault();
    $(this).find('.js-filter-radio').prop('checked', true);

    $('.sp-packages-filters .sp-packages-link').removeClass('active');
    $(this).find('.sp-packages-link').addClass('active');
    $('.js-package-filters-form').submit();
});

// $('.js-send-discount-code').on('click', function(e) {
//     e.preventDefault();

//     var discountCode = $('.js-discount-code').val();
//     var packageId    = $('.js-booking-package-id').val();

//     $.ajax({
//         type: 'POST',
//         url: '/hotel/booking/validate_discount?valid',
//         data: { discount_code: discountCode, package_id: packageId },
//         success: function(resp) {
//             console.log(resp);
//             if (resp.valid) {
//                 $('.js-discount-infobox').html(resp.message);
//             } else {
//                 $('.js-discount-infobox').text(resp.error);
//             }
//         }
//     });

// });


$('.js-overview-datepicker').each(function() {
    $(this).ionDatePicker({
        callbackInput: '#'+$(this).attr('id'),
        selectedDays: 1
    });
});


$('.js-check-packages-available').on('click', function(e) {
    e.preventDefault();
    var dateFromVal = $('#overview-date-from').val(),
        dateToVal   = $('#overview-date-till').val(),
        guestsVal   = $('#overview-guests').val(),
        hotelId     = $('#overview-hotel-id').val();

    $.ajax({
        type: 'POST',
        url: '/hotel/validate_package_availability',
        data: { date_from: dateFromVal, date_to: dateToVal, guests: guestsVal, hotel_id: hotelId },
        success: function(resp) {
            if (resp.has_packages) {
                //$('.js-available-check-msg').removeClass('hidden');
                //$('.js-not-available-check-msg').addClass('hidden');
                $('#packages-check-form').submit();
            } else {
                $('.js-available-check-msg').addClass('hidden');
                $('.js-not-available-check-msg').removeClass('hidden');
            }
        }
    });

});


$(document).ready(function() {

    if ( typeof gm_data != 'undefined' && $('#js-location').is(':visible')) {
        var searchMap = SPASON.map.initializeGoogleMaps(gm_data);
        if ( searchMap ) {
            SPASON.map.initGoogleMapsMarkers(searchMap);
        }
    } else {
        $('a[href="#overview-map"]').one('show.bs.tab', function() {
            var searchMap = SPASON.map.initializeGoogleMaps(gm_data);
            if ( searchMap ) {
                SPASON.map.initGoogleMapsMarkers(searchMap);
            }
        });

        // temp
        $('#overview-map-open').on('click', function(e) {
            e.preventDefault();
            $('a[href="#overview-map"]').tab('show');
        });
    }

    // images popup
    if ( typeof hotel_images != 'undefined' ) {
        $('.js-gallery-open').magnificPopup({
            preload: [1, 2],
            items: hotel_images.big,
            gallery: {
                enabled:true
            },
            type: 'image',
            closeBtnInside: false,
            closeMarkup: '<button title="%title%" type="button" class="mfp-close">&#x4d;</button>'
        }).on('click', function() {
                $(this).magnificPopup('open');
        });
    }


});

/* Auth module
 */


SPASON.namespace('auth');
SPASON.auth = (function() {
    var $signupPopup = $('#signup-popup'),
        $signupPopupNext = $('#signup-popup-next'),
        $loginPopup = $('#login-popup'),

        $signupFormFirst = $('#signup-form-first'),
        $signupFormSecond = $('#signup-form-second'),
        $loginForm = $('#login-form'),

        $signupEmailFirst = $('#signup-email-first'),
        $signupEmailSecond = $('#signup-email-second'),

        $signupNextOpen = $('#signup-next-open'),
        $loginOpen = $('#login-open'),

        isFirstFormSubmitted = false;


    // validators
    $signupFormFirst.bootstrapValidator({
        trigger: 'blur',
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    },
                    remote: {
                        message: 'The email address already exists',
                        url: '/client/register/validate/'
                    }
                }
            }
        }
    }).on('error.field.bv', '#signup-email-first', function() {
        $signupNextOpen.attr('disabled', false);
        isFirstFormSubmitted = false;
    }).on('success.field.bv', '#signup-email-first', function(e, data) {

        // save email address and show second signup form
        var emailVal = $signupEmailFirst.val();

        if (isFirstFormSubmitted) {
            $.ajax({
                type: 'POST',
                url: '/client/register',
                data: { email: emailVal },
                success: function(resp) {
                    if (!resp.error) {
                        $signupEmailSecond.val(resp.result.user.email);

                        $signupPopup.modal('hide');
                        $signupPopupNext.modal('show');

                        $signupNextOpen.attr('disabled', false);
                        isFirstFormSubmitted = false;
                    } else {
                        console.log(resp);
                    }
                }
            });
            isFirstFormSubmitted = false;
        }
    });

    $signupFormSecond.bootstrapValidator({
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name field is required'
                    }
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: 'The phone number is required'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            }
        }
    });

    $loginForm.bootstrapValidator({
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            }
        }
    });


    // validate first form on click
    var validateFirstForm = function() {
        $signupNextOpen.on('click', function(e) {
            e.preventDefault();

            $signupNextOpen.attr('disabled', true);
            isFirstFormSubmitted = true;

            $signupFormFirst.data('bootstrapValidator').validate();
            return $signupFormFirst.data('bootstrapValidator').isValid();
        });
    };
    validateFirstForm();

    // save addition user data
    var finishUserRegistration = function() {
        var nameVal = $('#signup-name').val(),
            phoneVal = $('#signup-phone').val(),
            passwordVal = $('#signup-password').val();

        if (passwordVal || phoneVal || nameVal) {
            $.ajax({
                type: 'POST',
                url: '/client/update',
                data: {
                    name: nameVal,
                    phone: phoneVal,
                    password: passwordVal
                },
                success: function(resp) {
                    if (!resp.error) {
                        //console.log(resp);
                    } else {
                        //console.log(resp);
                    }
                }
            });
        }
    };

    $loginOpen.on('click', function(e) {
        e.preventDefault();

        $signupPopup.modal('hide');
        $loginPopup.modal('show');
    });


    // events on hide popups
    $signupPopup.on('hide.bs.modal', function () {
        $signupFormFirst.find('input').val('');
        $signupFormFirst.data('bootstrapValidator').resetForm();

        $signupNextOpen
        .removeData()
        .off();

        validateFirstForm();
    });

    $signupPopupNext.on('hidden.bs.modal', function () {
        $signupFormSecond.find('input').val('');
        $signupFormSecond.data('bootstrapValidator').resetForm();

        finishUserRegistration();
    });
}());

/*
 * Date picker config and scripts
 */


SPASON.namespace('datepicker');
SPASON.datepicker = (function() {
    return {
        config: {
            days: ['söndag', 'måndag', 'tisdag', 'onsdag', 'torsdag', 'fredag', 'lördag'],
            months: ['januari', 'februari', 'mars', 'april', 'maj', 'juni', 'juli', 'augusti', 'september', 'oktober', 'november', 'december'],
            header_captions: {
                'days'   : 'F - Y',
                'months' : 'Y',
                'years'  : 'Y1 - Y2'
            },
            show_other_months: false,
            header_navigation: ['&#x34;', '&#x35;'],
            show_icon: false,
            show_clear_date: true,
            show_select_today: false,
            offset: [5, -1]
        },
        toggleIcon: function($this) {
            if ($this.val()) {
                $this.next().removeClass('is-visible');
            } else {
                $this.next().addClass('is-visible');
            }
        },

        dateParse: function(date) {
            var split = date.split('-');

            return {
                year  : +split[0],
                month : +split[1] - 1,
                day   : +split[2]
            };
        },

        dateDiff: function(from, till) {
            return Math.floor((till.getTime() - from.getTime()) / 1000/ 60 / 60 / 24);
        },

        dateFormat: {
            day: function(date) {
                return ('0' + date.getDate()).slice(-2);
            },
            month: function(date) {
                return ('0' + (date.getMonth() + 1)).slice(-2);
            },
            full: function(date) {
                var format = {
                    day   : ('0' + date.getDate()).slice(-2),
                    month : ('0' + (date.getMonth() + 1)).slice(-2),
                    year  : date.getFullYear()
                };

                return ''+ format.year + '-' + format.month + '-' + format.day + '';
            }
        }
    };
}());


SPASON.namespace('datepicker.packages');
SPASON.datepicker.packages = (function() {
    var dp = SPASON.datepicker,

        $fieldFrom = $('#date-from'),
        $fieldTill = $('#date-till'),
        $fieldGuests = $('.js-filter-packages-persons'),

        dateParse = function(date) {
            var split = date.split('-').join('/').split('/');

            return {
                year  : +split[2],
                month : +split[1] - 1,
                day   : +split[0]
            };
        },

        initDate = function() {
            if ($fieldFrom.val()) {
                var initDateFrom = $fieldFrom.val();
                dateFrom = new Date(dateParse(initDateFrom).year, dateParse(initDateFrom).month, dateParse(initDateFrom).day);
            }
            if ($fieldTill.val()) {
                var initDateTill = $fieldTill.val();
                dateTill = new Date(dateParse(initDateTill).year, dateParse(initDateTill).month, dateParse(initDateTill).day);
            }
        },

        submitForm = function() {
            $('.js-package-filters-form').submit();
        },

        dateFrom,
        dateTill;

        /**
         * Get disabled packages
         *
         * @param dateFrom
         * @param dateTill
         * @param guests
         * @returns {*}
         */
        ajaxGetDisabledPackages = function(dateFrom, dateTill, guests) {
            if ( window.selectedHotelSlug ) {
                var query = {};

                if ( dateFrom ) {
                    query.date_from = dateFrom;
                }

                if ( dateTill ) {
                    query.date_to = dateTill;
                }

                if ( guests ) {
                    query.persons = guests;
                }

                if ( !dateFrom && !dateTill ) {
                    query = {};
                }

                return $.ajax(
                    {
                        url: '/spahotell/'+ window.selectedHotelSlug +'/paket',
                        type: 'GET',
                        data: query
                    }
                );
            }
        }

        /**
         * Filter packages and disable in html
         *
         * @param disabledPackagesIds
         */
        filterPackages = function(disabledPackagesIds) {
            // clear all packages
            $('.packages-content .box').removeClass('is-disabled');
            $('.packages-content .box span.box-overlay').remove();

            // disable packages
            if ( disabledPackagesIds ) {
                for(id in disabledPackagesIds) {
                    $('.js-package-id'+id+' .box').addClass('is-disabled');
                    $('.js-package-id'+id+' .box').append('<span class="box-overlay"></span>');
                }
            }
        }

        $('.js-filter-packages-persons').on('change', function() {
            //$('.js-package-filters-form').submit();
            $.when(ajaxGetDisabledPackages($fieldFrom.val(), $fieldTill.val(), $fieldGuests.val())).done(function(disabledPackages) {
                if ( disabledPackages ) {
                    filterPackages(disabledPackages);
                }
            });
        });


        $fieldFrom.Zebra_DatePicker(
            $.extend({}, dp.config, {
                offset: [0, 0],
                show_clear_date: 0,
                direction: true,
                pair: $fieldTill,
                format: 'd/m - Y',
                onSelect: function(val, date) {
                    //submitForm();
                    $('#date-from-calendar-txt').text(val);
                    $.when(ajaxGetDisabledPackages($fieldFrom.val(), $fieldTill.val(), $fieldGuests.val())).done(function(disabledPackages) {
                        if ( disabledPackages ) {
                            filterPackages(disabledPackages);
                        }
                    });
                },
                onClear: function() {
                    $('#date-from-calendar-txt').text('Från');
                    $.when(ajaxGetDisabledPackages($fieldFrom.val(), $fieldTill.val(), $fieldGuests.val())).done(function(disabledPackages) {
                        if ( disabledPackages ) {
                            filterPackages(disabledPackages);
                        }
                    });
                }
            })
        );

        $fieldTill.Zebra_DatePicker(
            $.extend({}, dp.config, {
                offset: [0, 0],
                show_clear_date: 0,
                addition_css_class: 'dp_date_till',
                direction: 1,
                format: 'd/m - Y',
                onSelect: function(val, date) {
                    //submitForm();
                    $('#date-to-calendar-txt').text(val);
                    $.when(ajaxGetDisabledPackages($fieldFrom.val(), $fieldTill.val(), $fieldGuests.val())).done(function(disabledPackages) {
                        if ( disabledPackages ) {
                            filterPackages(disabledPackages);
                        }
                    });
                },
                onClear: function() {
                    $('#date-to-calendar-txt').text('Till');
                    $.when(ajaxGetDisabledPackages($fieldFrom.val(), $fieldTill.val(), $fieldGuests.val())).done(function(disabledPackages) {
                        if ( disabledPackages ) {
                            filterPackages(disabledPackages);
                        }
                    });
                }
            })
        );

    initDate();
}());


SPASON.namespace('datepicker.overview');
SPASON.datepicker.overview = (function() {
    var dp = SPASON.datepicker,

        $fieldFrom = $('#overview-date-from'),
        $fieldTill = $('#overview-date-till'),

        fillSiblingDate = function(flag) {
            if (flag === 'checkout') {
                dateTill = new Date(dateFrom.getTime());
                dateTill.setDate(dateFrom.getDate() + 1);

                $fieldTill.val(dp.dateFormat.day(dateTill) + '/' + dp.dateFormat.month(dateTill) + ' - ' + dateFrom.getFullYear());
                $fieldTill.data('Zebra_DatePicker').show();
            } else if (flag === 'checkin') {
                dateFrom = new Date(dateTill.getTime());
                dateFrom.setDate(dateTill.getDate() - 1);

                $fieldFrom.val(dp.dateFormat.day(dateFrom) + '/' + dp.dateFormat.month(dateFrom) + ' - ' + dateFrom.getFullYear());
                $fieldFrom.data('Zebra_DatePicker').show();
            }
        },

        dateToday = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()),
        dateFrom,
        dateTill,
        daysDiff;


    $fieldFrom.Zebra_DatePicker(
        $.extend({}, dp.config, {
            direction: true,
            pair: $fieldTill,
            format: 'd/m - Y',
            onSelect: function(val, date) {
                dateFrom = new Date(dp.dateParse(date).year, dp.dateParse(date).month, dp.dateParse(date).day);

                if (dateTill) {
                    daysDiff = dp.dateDiff(dateFrom, dateTill);

                    if (daysDiff <= 0) {
                        fillSiblingDate('checkout');
                    }
                } else {
                    fillSiblingDate('checkout');
                }

                dp.toggleIcon($fieldFrom);
                dp.toggleIcon($fieldTill);
            },
            onClear: function() {
                $fieldTill.data('Zebra_DatePicker').update({
                    reference_date: dateToday
                });

                dateFrom = null;

                dp.toggleIcon($fieldFrom);
                dp.toggleIcon($fieldTill);
            }
        })
    );

    $fieldTill.Zebra_DatePicker(
        $.extend({}, dp.config, {
            addition_css_class: 'dp_date_till',
            direction: 1,
            format: 'd/m - Y',
            onSelect: function(val, date) {
                dateTill = new Date(dp.dateParse(date).year, dp.dateParse(date).month, dp.dateParse(date).day);

                if (dateFrom) {
                    daysDiff = dp.dateDiff(dateFrom, dateTill);

                    if (daysDiff <= 0) {
                        fillSiblingDate('checkin');
                    }
                } else if (dp.dateDiff(dateToday, dateTill) - 1 === 0) {
                    fillSiblingDate('checkin');
                }

                dp.toggleIcon($fieldFrom);
                dp.toggleIcon($fieldTill);
            },
            onClear: function() {
                dateTill = null;

                dp.toggleIcon($fieldFrom);
                dp.toggleIcon($fieldTill);
            }
        })
    );

    dp.toggleIcon($fieldFrom);
    dp.toggleIcon($fieldTill);
}());


SPASON.namespace('datepicker.package');
SPASON.datepicker.package = (function() {

    // run only if field present on the page
    if (!$('#package-date-from').length) return;

    var dp = SPASON.datepicker,

        $form = $('#package-form'),
        $formSubmit = $('#package-form-submit'),
        $formErr = $('#package-book-err'),

        $fieldFrom = $('#package-date-from'),
        $fieldTill = $('#package-date-till'),
        $persons   = $('#package-persons'),

        $hiddenFrom = $('#package-date-from-hidden'),
        $hiddenTill = $('#package-date-till-hidden'),

        availableDays = {
            min: parseInt($fieldFrom.data('available-days').split(':')[0], 10) || 1,
            max: parseInt($fieldFrom.data('available-days').split(':')[1], 10)
        },

        initPrice = function() {
            if ($fieldFrom.val()) {
                var initDateFrom = $hiddenFrom.val();
                dateFrom = new Date(dp.dateParse(initDateFrom).year, dp.dateParse(initDateFrom).month, dp.dateParse(initDateFrom).day);
            }
            if ($fieldTill.val()) {
                var initDateTill = $hiddenTill.val();
                dateTill = new Date(dp.dateParse(initDateTill).year, dp.dateParse(initDateTill).month, dp.dateParse(initDateTill).day);
            }
            if ($fieldFrom.val() && $fieldTill.val()) {
                priceUpdate();
            }
        },

        validateCondition = function() {
            daysDiff = dp.dateDiff(dateFrom, dateTill);

            var condition;

            if (availableDays.max) {
                condition = daysDiff < availableDays.min || daysDiff > availableDays.max || daysDiff <= 0;
            } else {
                condition = daysDiff < availableDays.min || daysDiff <= 0;
            }

            return condition;
        },

        validateDate = function(flag) {
            if (flag === 'from') {
                if (validateCondition()) {
                    fillSiblingDate('checkin');
                } else {
                    priceUpdate();
                }
            } else if (flag === 'till') {
                if (validateCondition()) {
                    fillSiblingDate('checkout');
                } else {
                    priceUpdate();
                }
            }
        },

        validateForm = function() {
            var isValid = false;

            if ($fieldFrom.val() && $fieldTill.val()) {
                $formErr.removeClass('is-visible');
                isValid = true;
            } else {
                $formErr.addClass('is-visible');
                isValid = false;
            }

            return isValid;
        },

        priceUpdate = function() {
            daysDiff = dp.dateDiff(dateFrom, dateTill);

            var packageBasePrice = $('#package-base-price').val(),
                packagePersons   = $('#package-persons').val(),
                packageFullPrice = packageBasePrice * packagePersons * daysDiff;

            $('#package-price').text(packageFullPrice);
        },

        fillSiblingDate = function(flag) {
            if (flag === 'checkout') {
                dateTill = new Date(dateFrom.getTime());
                dateTill.setDate(dateFrom.getDate() + availableDays.min);

                $fieldTill.val(dp.dateFormat.day(dateTill) + '/' + dp.dateFormat.month(dateTill));
                $hiddenTill.val(dp.dateFormat.full(dateTill));
                $fieldTill.data('Zebra_DatePicker').show();
            } else if (flag === 'checkin') {
                dateFrom = new Date(dateTill.getTime());
                dateFrom.setDate(dateTill.getDate() - availableDays.min);

                $fieldFrom.val(dp.dateFormat.day(dateFrom) + '/' + dp.dateFormat.month(dateFrom) + ' - ' + dateFrom.getFullYear());
                $hiddenFrom.val(dp.dateFormat.full(dateFrom));
                $fieldFrom.data('Zebra_DatePicker').show();
            }
        },

        dateToday = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()),
        dateFrom,
        dateTill,
        daysDiff;


    $fieldFrom.Zebra_DatePicker(
        $.extend({}, dp.config, {
            direction: true,
            pair: $fieldTill,
            format: 'd/m - Y',
            onSelect: function(val, date) {
                if (availableDays.max === 0) {
                    $fieldTill.data('Zebra_DatePicker').update({
                        direction: availableDays.min
                    });
                } else {
                    $fieldTill.data('Zebra_DatePicker').update({
                        direction: [
                            availableDays.min,
                            availableDays.max - availableDays.min
                        ]
                    });
                }

                $hiddenFrom.val(date);
                dateFrom = new Date(dp.dateParse(date).year, dp.dateParse(date).month, dp.dateParse(date).day);

                if (dateTill) {
                    validateDate('till');
                } else {
                    fillSiblingDate('checkout');
                }

                dp.toggleIcon($fieldFrom);
                dp.toggleIcon($fieldTill);
            },
            onClear: function() {
                $fieldTill.data('Zebra_DatePicker').update({
                    reference_date: dateToday
                });

                dateFrom = null;
                $hiddenFrom.val('');

                dp.toggleIcon($fieldFrom);
                dp.toggleIcon($fieldTill);
            }
        })
    );

    $fieldTill.Zebra_DatePicker(
        $.extend({}, dp.config, {
            addition_css_class: 'dp_date_till',
            direction: availableDays.min,
            format: 'd/m',
            onSelect: function(val, date) {
                $hiddenTill.val(date);
                dateTill = new Date(dp.dateParse(date).year, dp.dateParse(date).month, dp.dateParse(date).day);

                if (dateFrom) {
                    validateDate('from');
                } else if (dp.dateDiff(dateToday, dateTill) - availableDays.min === 0) {
                    fillSiblingDate('checkin');
                }

                dp.toggleIcon($fieldFrom);
                dp.toggleIcon($fieldTill);
            },
            onClear: function() {
                dateTill = null;
                $hiddenTill.val('');

                dp.toggleIcon($fieldFrom);
                dp.toggleIcon($fieldTill);
            }
        })
    );


    initPrice();
    dp.toggleIcon($fieldFrom);
    dp.toggleIcon($fieldTill);

    // update start date for till datepicker
    if ($fieldFrom.val()) {
        $fieldTill.data('Zebra_DatePicker').update({
            reference_date: dateFrom
        });
    }


    $persons.on('change', function() {
        if (dateFrom && dateTill) {
            priceUpdate();
        }
    });

    $($fieldFrom, $fieldTill).on('click', function() {
        $formErr.removeClass('is-visible');
    });

    $form.on('submit', function(e) {
        return validateForm();
    });
}());

/*
 * Discount booking
 * TODO: redo!
 */


(function() {
    var packageId = $('#package-id').val(),
        discountCode;

    var discountAdd = function(resp) {
        $('#discount-popup').modal('hide');

        $('#discount-added').show();
        $('#discount-minus').show();
        $('#discount-add').hide();

        $('#discount-name').text(resp.discountName);
        $('#discount-code-hidden').val(discountCode);

        calculateDiscount(resp);
        if (resp.priceType === 'person') {
            $('#discount-person-row').show();
            $('#discount-minus-person').text(resp.discountAmount).parent().show();
        } else {
            $('#discount-package-row').show();
            $('#discount-minus-package').text(resp.discountAmount).parent().show();
        }

        $('#discount-code').val('');
        $('#package-discount-hidden').val(discountCode);
    };
    var discountRemove = function() {
        $('#discount-added').hide();

        $('#discount-minus').hide();
        $('#discount-minus-person').parent().hide();
        $('#discount-minus-package').parent().hide();

        $('#discount-add').show();

        $('#discount-person-row').hide();
        $('#discount-package-row').hide();

        $('#discount-name').text('');
        $('#discount-code-hidden').val('');

        $('#discount-total-price').text($('#discount-package-price').text() * $('#discount-person').text());
        $('#package-discount-hidden').val('');
    };
    var calculateDiscount = function(resp) {
        if (resp.priceType === 'person') {
            var personPrice = $('#discount-package-price').text() - resp.discountAmount;
            var totalPrice = personPrice * $('#discount-person').text();

            $('#discount-person-price').text(personPrice);
            $('#discount-total-price').text(totalPrice);
        } else {
            var packagePrice = $('#discount-total-price').text() - resp.discountAmount;

            $('#discount-package-price-min').text(packagePrice);
        }
    };


    $('#discount-form').on('submit', function(e) {
        e.preventDefault();

        discountCode = $('#discount-code').val();

        if (discountCode) {
            $('#discount-send').attr('disabled', true);

            $.ajax({
                type: 'POST',
                url: '/hotel/booking/validate_discount?valid',
                data: {
                    discount_code: discountCode,
                    package_id: packageId
                },
                success: function(resp) {
                    // console.log(resp);
                    $('#discount-send').attr('disabled', false);
                    if (resp.valid) {
                        discountAdd(resp);
                    } else {
                        $('#discount-error').text(resp.error);
                    }
                },
                error: function(resp) {
                    console.log(resp);
                    $('#discount-send').attr('disabled', false);
                    $('#discount-error').text(resp.status);
                }
            });
        }
    });

    $('#discount-remove').on('click', function(e) {
        e.preventDefault();
        discountRemove();
    });
}());

/*
 * Map module
 */


SPASON.namespace('map');
SPASON.map = (function () {
    function initializeGoogleMaps(gm_data) {

        if ( typeof gm_data != 'undefined' ) {
            var styles = [
                {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [
                        { "gamma": 0.6 },
                        { "visibility": "on" }
                    ]
                },
                {
                    "featureType": "landscape.natural",
                    "elementType": "geometry.fill",
                    "stylers": [
                        { "saturation": 33 },
                        { "hue": "#c3ff00" },
                        { "gamma": 0.68 },
                        { "weight": 0.1 },
                        { "visibility": "on" }
                    ]
                }
            ];
            var styledMap = new google.maps.StyledMapType(styles, { name: "Styled Map" }),
                myLatlng = new google.maps.LatLng(gm_data[Object.keys(gm_data)[0]].lat, gm_data[Object.keys(gm_data)[0]].lng),
                mapOptions = {
                    zoom: 12,
                    center: myLatlng,
                    panControl: false,
                    zoomControl: false,
                    streetViewControl: false,
                    mapTypeControl: false,
                    mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
                    }
                },
                map = new google.maps.Map(document.getElementById('js-location'), mapOptions);

            map.mapTypes.set('map_style', styledMap);
            map.setMapTypeId('map_style');

            return map;
        }

        return false;
    }

    function initGoogleMapsMarkers(map) {

        var markers = [],
            windows = [],
            latlng  = [];

        if ( typeof gm_data != 'undefined' ) {
            $.each(gm_data, function(i, hotel) {

                var hotelLocation = new google.maps.LatLng(hotel.lat, hotel.lng);
                var marker = new google.maps.Marker({
                    position: hotelLocation,
                    map: map,
                    draggable: false,
                    icon: '/images/marker-default.png',
                    zIndex: 1
                });

                if ( hotel.description ) {
                    marker.info = new google.maps.InfoWindow({
                        maxWidth: 256,
                        content: '<div class="box box-map" style="background-image: url(' + hotel.photo + ')">' +
                                    '<div class="box-info">' +
                                        '<h3 class="box-title">' + hotel.name + '</h3>' +
                                        '<div class="box-panel">' +
                                            '<p class="box-descr">' + hotel.description.slice(0, 120) + '</p>' +
                                            '<h4 class="box-price">Fr. '+ hotel.price +' SEK/person</h4>' +
                                        '</div>' +
                                    '</div>' +
                                    '<a href="' + hotel.url + '" class="box-link"></a>' +
                                    '<a href="' + hotel.booking_url + '" class="box-book btn btn-primary btn-sm">Boka</a>' +
                                '</div>'
                    });

                    google.maps.event.addListener(marker, 'click', function() {
                        $.each(windows, function(i, item) {
                            if (item) {
                                markers[i].setIcon('/images/marker-default.png');
                                item.close();
                            }
                        });

                        marker.setIcon('/images/marker-hover.png');
                        marker.setZIndex(10);
                        marker.info.open(map, marker);
                        map.panTo(marker.getPosition());
                    });
                    google.maps.event.addListener(marker.info, 'closeclick', function(){
                        marker.setIcon('/images/marker-default.png');
                        marker.setZIndex(1);
                    });
                }

                latlng.push(hotelLocation);
                markers[hotel.id] = marker;
                windows[hotel.id] = marker.info;
            });

            var watchMarkersVisibility = function() {
                var markersOutside = [],
                    toggleButton = function() {
                    markersOutside = [];

                    $.each(markers, function(index, item) {
                        if (item) {
                            if (!map.getBounds().contains(item.getPosition())) {
                                markersOutside.push(item);
                            }
                        }
                    });

                    if (markersOutside.length) {
                        $('#find-map-show-markers').addClass('is-visible');
                    } else {
                        $('#find-map-show-markers').removeClass('is-visible');
                    }
                };

                google.maps.event.addListener(map, 'zoom_changed', function() {
                    toggleButton();
                });
                google.maps.event.addListener(map, 'dragend', function() {
                    toggleButton();
                });
            };

            var latlngbounds = new google.maps.LatLngBounds();
            for (var i = 0; i < latlng.length; i++) {
                latlngbounds.extend(latlng[i]);
            }

            if ( latlng.length > 1 ) {
                map.fitBounds(latlngbounds);
                google.maps.event.addListenerOnce(map, 'idle', function() {
                    watchMarkersVisibility();
                });
            }

            $('#find-map-show-markers').on('click', function() {
                map.fitBounds(latlngbounds);
            });

            $('.js-hotel-location').on('mouseover', function(e) {
                var markerId = $(this).attr('data-marker-id');
                var marker = markers[markerId];
                marker.setIcon('/images/marker-hover.png');
                marker.setZIndex(10);
            });

            $('.js-hotel-location').on('mouseout', function(e) {
                var markerId = $(this).attr('data-marker-id');
                var marker = markers[markerId];
                marker.setIcon('/images/marker-default.png');
                marker.setZIndex(1);
            });
        }
    }

    return {
        initializeGoogleMaps: initializeGoogleMaps,
        initGoogleMapsMarkers: initGoogleMapsMarkers
    };
}());
/* Newsletter module
 */


SPASON.namespace('newsletter');
SPASON.newsletter = (function() {
    var $popup = $('#newsletter-popup'),
        $form = $('#newsletter-form'),

        popupOpen = function() {
            var timer = setTimeout(function() {
                $popup.modal('show');
            }, 10000);
        },

        setCookie = function() {
            $.cookie('newsletter', 'true', {
                expires: 99999,
                path: '/'
            });
        };


    $form.bootstrapValidator({
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            }
        }
    });

    if (!$.cookie('newsletter')) {
        popupOpen();
    }

    $popup.on('hide.bs.modal', function() {
        $form.data('bootstrapValidator').resetForm();
        setCookie();
    });

    $form.on('submit', function(e) {
        e.preventDefault();

        if ($form.data('bootstrapValidator').isValid()) {
            var name = $('#newsletter-name').val(),
                email = $('#newsletter-email').val();

            console.log(name);
            console.log(email);

            $.ajax({
                type: 'POST',
                url: '/',
                data: {
                    name: name,
                    email: email
                },
                success: function(resp) {
                    if (!resp.error) {
                        setCookie();
                        $popup.modal('hide');

                        console.log('done');
                    } else {
                        console.log(resp);
                    }
                }
            });
        }
    });
}());


/*
 * Rating system
 */

SPASON.rating = (function() {
    var $rating = $('#review-rating'),
        $ratingItem = $('.js-review-rating-item'),

        ratingUpdate = function($el) {
            $el.addClass('is-check');
            $el.prevAll('.js-review-rating-item').addClass('is-check');
            $el.nextAll('.js-review-rating-item').removeClass('is-check');
        },

        ratingHighlight = function($this) {
            ratingUpdate($this);
        },
        ratingSet = function($this) {
            $ratingItem.removeAttr('data-check');
            $this.attr('data-check', true);

            ratingUpdate($this);
            $('#review-rating-hidden').val($this.data('val'));
        },
        ratingSave = function() {
            if ($('.js-review-rating-item[data-check]').length) {
                ratingUpdate($('.js-review-rating-item[data-check]'));
            } else {
                $ratingItem.removeClass('is-check');
            }
        },

        // get rating value from hidden field and higlight stars
        ratingInit = function() {
            var val = $('#review-rating-hidden').val(),
                $item = $('.js-review-rating-item[data-val="' + val + '"]');

            $item.attr('data-check', true);

            ratingUpdate($item);
        };


    ratingInit();

    $rating.on('mouseover', '.js-review-rating-item', function() {
        ratingHighlight($(this));
    });
    $rating.on('click', '.js-review-rating-item', function() {
        ratingSet($(this));
    });
    $rating.on('mouseleave', '.js-review-rating-item', function() {
        ratingSave();
    });
}());

/*
 * Navigation menues behavior for mobile devices
 */


SPASON.namespace('responsiveNav');
SPASON.responsiveNav = (function () {
    'use strict';

    var nav = {
        open: function(el) {
            $(el).addClass('is-visible');
        },

        close: function(el) {
            $(el).removeClass('is-visible');
        },

        isVisible: function(el) {
            return $(el).hasClass('is-visible');
        }
    };


    var navPrimary = {
        el: {
            menu: '#nav-primary',
            open: '.js-nav-primary-open',
            close: '.js-nav-primary-close'
        },

        events: function() {
            var self = this;

            $(self.el.open).on('click', function(e) {
                e.preventDefault();

                nav.open(self.el.menu);
            });

            $(self.el.close).on('click', function(e) {
                e.preventDefault();

                nav.close(self.el.menu);
            });
        },

        init: function() {
            this.events();
        }
    }.init();


    var navSecondary = {
        el: {
            menu: '#nav-secondary',
            list: '#nav-secondary-list'
        },

        events: function() {
            var self = this;

            $(self.el.list).on('click', function() {
                if ( nav.isVisible(self.el.menu) ) {
                    nav.close(self.el.menu);
                } else {
                    nav.open(self.el.menu);
                }
            });

            $(document).on('click', function(e) {
                var target = $(e.target);

                if (nav.isVisible(self.el.menu) && !target.closest(self.el.menu).length) {
                    nav.close(self.el.menu);
                }
            });
        },

        init: function() {
            this.events();
        }
    }.init();


    var searchForm = {
        el: {
            form: '#search-form',
            open: '#search-form-open'
        },

        events: function() {
            var self = this;

            $(self.el.open).on('click', function(e) {
                e.preventDefault();

                if ( nav.isVisible(self.el.form) ) {
                    nav.close(self.el.form);
                    $(self.el.open).removeClass('is-active');
                } else {
                    nav.open(self.el.form);
                    $(self.el.open).addClass('is-active');
                }
            });

            $(document).on('click', function(e) {
                var target = $(e.target);

                if (nav.isVisible(self.el.form)
                    && !target.closest(self.el.form).length
                    && !target.is(self.el.open)) {
                    nav.close(self.el.form);
                    $(self.el.open).removeClass('is-active');
                }
            });
        },

        init: function() {
            this.events();
        },
    }.init();
}());

/*
 * Find map set sizes to fit the page
 */




SPASON.namespace('scrollSearchMap');
SPASON.scrollSearchMap = (function () {
    'use strict';

    var findMapSize = {
        el: {
            $map: $('#find-map'),
            $box: $('#find-map-box'),
            $panel: $('#header-secondary')
        },

        init: function() {
            if (this.el.$map.length) {
                this.calcWidth();
                $(window).on('resize', this.calcWidth.bind(this));

                this.calcOffsetTop();
                $(window).on('scroll', this.calcOffsetTop.bind(this));
            }
        },

        calcWidth: function() {
            var boxWidth = this.el.$box.offset().left + this.el.$box.width();

            this.el.$map.css({
                'width': boxWidth
            });
        },

        calcOffsetTop: function() {
            var headerHeight = this.el.$panel.offset().top + this.el.$panel.outerHeight(true),
                boxOffset = headerHeight - $(window).scrollTop();

            this.el.$map.css({
                'top': boxOffset
            });
        }
    }.init();
}());

/**
 * Scroll top page
 */


SPASON.namespace('scrollTop');
SPASON.scrollTop = (function() {
    var $link = $('#scroll-top'),
        $win = $(window),

        scrollPage = function() {
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        },

        toggleLink = function() {
            if ($win.scrollTop() > 200) {
                $link.addClass('is-visible');
            } else {
                $link.removeClass('is-visible');
            }
        };

    toggleLink();
    $win.on('scroll', function() {
        toggleLink();
    });

    $link.on('click', function() {
        scrollPage();
    });
}());
/**
 * Search with autocomplete
 */


SPASON.namespace('search');
SPASON.search = (function() {
    var $form = $('#search-form'),
        $field = $('#search-form-field'),

        locationsList = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: 'scripts/data/locations.json'
        }),

        searchLocations = function() {
            console.log('start search');
        };


    locationsList.initialize();
    $field.typeahead(null, {
        name: 'locations',
        displayKey: 'value',
        source: locationsList.ttAdapter(),
        templates: {
            suggestion: function (data) {
                return SPASON.template('<i class="search-icon-<%this.type%>"></i> <%this.value%>', data);
            }
        }
    });


    $form.on('submit', function(e) {
        e.preventDefault();

        searchLocations();
    });
    $field.on('typeahead:selected', function() {
        searchLocations();
    });
}());
