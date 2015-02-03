//Spason calendar based on Ion.Calendar:

// Ion.Calendar
// version 2.0.1, build: 91
// © 2013 Denis Ineshin | IonDen.com
//
// Project page:    http://ionden.com/a/plugins/ion.calendar/
// GitHub page:     https://github.com/IonDen/ion.calendar
//
// Released under MIT licence:
// http://ionden.com/a/plugins/licence-en.html
// =====================================================================================================================

(function($){
    try {
        var timeNow = moment();
    } catch(e){
        alert("Can't find Moment.js, please read the ion.calendar description.");
        throw new Error("Can't find Moment.js library");
    }

    var timeStart= moment().add(7, "days");

    var methods = {
        init: function(options){
            var settings = $.extend({
                    lang: "sv",
                    sundayFirst: false,
                    fromDate: moment().date(1),
                    toDate: moment().add("M", 6).endOf('month'),
                    daySpan: 4,
                    format: "",
                    clickable: true,
                    startDate: "",
                    hideArrows: false,
                    availableDates: null,
                    onClick: null,
                    onMonthClick: null,
                    onReady: null
                }, options),
                html, i;


            return this.each(function(){
                var $calendar = $(this);

                //prevent overwrite
                if($calendar.data("isActive")) {
                    return;
                }
                $calendar.data("isActive", true);

                var $prev,
                    $next,
                    $month,
                    $year,
                    $day,

                    timeSelected,
                    timeNowLocal = moment(timeNow.lang(settings.lang)),
                    timeForWork,
                    weekFirstDay,
                    weekLastDay,
                    monthLastDay,
                    fromDate,
                    toDate,
                    firstStart = true;



                // public methods
                this.updateData = function(options){
                    settings = $.extend(settings, options);
                    removeHTML();
                };



                // private methods
                var removeHTML = function(){
                    $prev.off();
                    $next.off();
                    $month.off();
                    $year.off();
                    $calendar.empty();

                    prepareData();
                    prepareCalendar();
                };

                var prepareData = function(){
                    // start date
                    if(settings.startDate) {
                        if(settings.format.indexOf("L") >= 0) {
                            timeSelected = moment(settings.startDate, "YYYY.MM.DD").lang(settings.lang);
                        } else {
                            timeSelected = moment(settings.startDate, settings.format).lang(settings.lang);
                        }
                    }

                    fromDate = settings.fromDate;
                    toDate = settings.toDate;
                };

                var prepareCalendar = function(){
                    timeForWork = moment(timeNowLocal);

                    weekFirstDay = parseInt(timeForWork.startOf("month").format("d"));
                    weekLastDay = parseInt(timeForWork.endOf("month").format("d"));
                    monthLastDay = parseInt(timeForWork.endOf("month").format("D"));

                    html  = '<div class="sc__container">';
                    html += '<div class="sc__header">';
                    html += '<div class="sc__prev"><div></div></div>';
                    html += '<div class="sc__next"><div></div></div>';

                    html += "<h4 class='text-center'>" + timeNowLocal.format("MMMM - YYYY") + "</h4>";
                    html += '</div>';
                    // week
                    html += '<table class="sc__week-head"><tr>';
                    for(i = 1; i < 8; i++) {
                        if(i < 7) {
                            html += '<td>' + timeForWork.day(i).format("dd") + '</td>';
                        } else {
                            html += '<td>' + timeForWork.day(0).format("dd") + '</td>';
                        }
                    }
                    html += '</tr></table>';

                    // days
                    html += '<table class="sc__days"><tr>';

                    // empty days
                    if(weekFirstDay > 0) {
                        weekFirstDay = weekFirstDay - 1;
                    } else {
                        weekFirstDay = 6;
                    }
                    for(i = 0; i < weekFirstDay; i++) {
                        html += '<td class="sc__day-empty">&nbsp;</td>';
                    }

                    for(i = 1; i <= monthLastDay; i++) {
                        // current day
                        html += '<td class="sc__day';

                        if (settings.availableDates && !moment(timeNowLocal).date(i).isBefore(timeStart) && (settings.availableDates.indexOf(i) != -1 || settings.availableDates.indexOf(i-1) != -1)) {
                            html += ' sc__day_state_available';
                        }
                        if (timeSelected && moment(timeNowLocal).date(i).isSame(timeSelected, 'day')) {
                            html += ' sc__day_state_selected_start';
                        }
                        if (timeSelected && (moment(timeNowLocal).date(i).isAfter(timeSelected, 'day') && moment(timeNowLocal).date(i).isBefore(moment(timeSelected).add('d', settings.daySpan), 'day'))) {
                            html += ' sc__day_state_selected';
                        }
                        if (timeSelected && moment(timeNowLocal).date(i).isSame(moment(timeSelected).add('d', settings.daySpan), 'day')) {
                            html += ' sc__day_state_selected_end';
                        }
                        if (moment(timeNowLocal).date(i).format("D.M.YYYY") === timeNow.format("D.M.YYYY")) {
                            html += ' sc__day_state_current sc__day_state_disabled';
                        }
                        if (moment(timeNowLocal).date(i).isBefore(timeStart)) {
                            html += ' sc__day_state_disabled';
                        }

                        html += '">' + i + '</td>';

                        // new week - new line
                        if((weekFirstDay + i) / 7 === Math.floor((weekFirstDay + i) / 7)) {
                            html += '</tr><tr>';
                        }
                    }
                    // empty days
                    if(weekLastDay < 1) {
                        weekLastDay = 7;
                    }
                    for(i = weekLastDay - 1; i < 6; i++) {
                        html += '<td class="sc__day-empty">&nbsp;</td>';
                    }
                    html += '</tr></table>';
                    html += '</div>';


                    placeCalendar();
                };

                var placeCalendar = function(){
                    $calendar.html(html);

                    $prev = $calendar.find(".sc__prev");
                    $next = $calendar.find(".sc__next");
                    $month = $calendar.find(".sc__month-select");
                    $year = $calendar.find(".sc__year-select");
                    $day = $calendar.find(".sc__day");

                    if(settings.hideArrows) {
                        $prev[0].style.display = "none";
                        $next[0].style.display = "none";
                    } else {
                        $prev.on("click", function(e){
                            e.preventDefault();
                            settings.availableDates = null;
                            timeNowLocal.subtract("months", 1);
                            if (timeNowLocal.isBefore(fromDate)) {
                                timeNowLocal.add("months", 1);
                            }
                            // trigger callback function
                            if (typeof settings.onMonthClick === "function") {
                                settings.onMonthClick.call(this, timeNowLocal);
                            }
                            removeHTML();
                        });
                        $next.on("click", function(e){
                            e.preventDefault();
                            settings.availableDates = null;
                            timeNowLocal.add("months", 1);
                            if (timeNowLocal.isAfter(toDate)) {
                                timeNowLocal.subtract("months", 1);
                            }
                            // trigger callback function
                            if (typeof settings.onMonthClick === "function") {
                                settings.onMonthClick.call(this, timeNowLocal);
                            }
                            removeHTML();
                        });
                    }

                    if(settings.clickable) {
                        $day.on("click", function(e){
                            e.preventDefault();
                            if (!$(this).hasClass('sc__day_state_disabled')) {
                                var toDay = $(this).text();
                                timeNowLocal.date(parseInt(toDay));
                                timeSelected = moment(timeNowLocal);
                                if(settings.format.indexOf("L") >= 0) {
                                    settings.startDate = timeSelected.format("YYYY-MM-DD");
                                } else {
                                    settings.startDate = timeSelected.format(settings.format);
                                }

                                // trigger callback function
                                if(typeof settings.onClick === "function") {
                                    if(settings.format) {
                                        if(settings.format === "moment") {
                                            settings.onClick.call(this, timeSelected);
                                        } else {
                                            settings.onClick.call(this, timeSelected.format(settings.format));
                                        }
                                    } else {
                                        settings.onClick.call(this, timeSelected.format());
                                    }
                                }

                                removeHTML();
                            }
                        });
                    }

                    // trigger onReady function
                    if(typeof settings.onReady === "function") {
                        if(settings.format) {
                            if(settings.format === "moment") {
                                settings.onReady.call(this, timeNowLocal);
                            } else {
                                settings.onReady.call(this, timeNowLocal.format(settings.format));
                            }
                        } else {
                            settings.onReady.call(this, timeNowLocal.format());
                        }
                    }

                    // go to startDate
                    if(settings.startDate && firstStart) {
                        firstStart = false;
                        timeNowLocal.year(parseInt(timeSelected.format("YYYY")));
                        timeNowLocal.month(parseInt(timeSelected.format("M") - 1));
                        removeHTML();
                    }
                };



                // yarrr!
                prepareData();
                prepareCalendar();
            });
        },
        update: function(options){
            return this.each(function(){
                this.updateData(options);
            });
        }
    };

    $.fn.ionCalendar = function(method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist for jQuery.ionCalendar');
        }
    };
})(jQuery);