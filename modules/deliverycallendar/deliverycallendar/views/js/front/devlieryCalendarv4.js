var CalledarActive = false;
var disableNextAllowedDay = false;
if (typeof callendar_disabled_dates == typeof undefined) {
    callendar_disabled_dates = '';
}

var tmpDisabledDated = callendar_disabled_dates.split(',');
callendar_disabled_dates = [];
for (var i = 0; i < tmpDisabledDated.length; i++) {
    callendar_disabled_dates[i] = tmpDisabledDated[i].trim();
}

var initTimePicker = function () {
    var TimeSelect = $('#delivery_time');
    var timeRanges = callendar_time_ranges.split(',');
    for (var i = 0; i < timeRanges.length; i++) {
        var timeRange = timeRanges[i].trim();
        TimeSelect.append('<option value="' + timeRange + '"' + (i == 0 ? ' selected="true"' : '') + '>' + timeRange + '</option>');
    }

    TimeSelect.on('change', function () {
        selectCallendarDateTime($('#delivery_callendar').data('datepicker'));
    });
};

var initCallendar = function () {
    if (typeof $.fn.datepicker == typeof undefined) {
        window.setTimeout(function () {
            initCallendar();
        }, 300);
        return;
    }

    $('#hook-display-after-carrier #delivery_callendar_wrapper').insertAfter($('#js-delivery .form-fields'));


    $.datepicker.regional.pl = {
        monthNames: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
        monthNamesShort: ['Sty', 'Lut', 'Mar', 'Kwi', 'Maj', 'Cze', 'Lip', 'Sie', 'Wrz', 'Paź', 'Lis', 'Gru'],
        dayNames: ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'],
        dayNamesShort: ['Nd', 'Pon', 'Wt', 'Śr', 'Czw', 'Pt', 'Sob'],
        dayNamesMin: ['Nd', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'Sb'],
        firstDay: 1
    };
    $.datepicker.setDefaults($.datepicker.regional.pl);


    $('#delivery_callendar').datepicker({
        minDate: getCallendarMinDate(),
        onSelect: function (date, callendar) {
            selectCallendarDateTime(callendar);
        },
        beforeShowDay: function (date) {
            return callendarOnRenderCell(date);
        },
    });

    initTimePicker();


    if (selectedDateTime != '') {
        var selectedTime = selectedDateTime.split(' ')[1];
        var selectedDate = selectedDateTime.split(' ')[0];
        var minAllowedDate = getCallendarMinAllowedDate();
  
        if(getTextDateAsDate(selectedDate).getTime() < minAllowedDate.getTime()) {
            selectedDate = getDateAsText(minAllowedDate);
        }
        
        $('#delivery_callendar').datepicker('setDate', getTextDateAsDate(selectedDate));
        $('#delivery_time option[value="' + selectedTime + '"]').attr('selected', 'true');
        $('#delivery_time option[value="' + selectedTime + '"]').prop('selected', 'true');

        setAfterCallendarText('Wybrano datę dostawy: ' + selectedDateTime);
    } else {
        $('#delivery_callendar').datepicker('setDate', getCallendarMinAllowedDate());
        setAfterCallendarText('Wybrano datę dostawy: ' + getDateAsText(getCallendarMinAllowedDate()));
    }



    if ($('td .ui-state-active').length < 1) {
        $('#delivery_callendar').datepicker('setDate', getCallendarMinAllowedDate());
        setAfterCallendarText('Wybrano datę dostawy: ' + getDateAsText(getCallendarMinAllowedDate()));
    }

    checkCallendarVisibility();
    selectCallendarDateTime($('#delivery_callendar').data('datepicker'));

    $('input[id^="delivery_option_"]').on('change', function () {
        checkCallendarVisibility();
        reInitCallendar();
    });

    $(document).click(function (e) {
        if (
            ($(e.target).is('.ui-datepicker-next') || $(e.target).is('.ui-datepicker-prev'))
            || ($($(e.target).parent()).is('.ui-datepicker-next') || $($(e.target).parent()).is('.ui-datepicker-prev'))
        ) {
            window.setTimeout(function () {
                $('#checkout-delivery-step').addClass('-current js-current-step');
            }, 0);
        }
    });
};

var reInitCallendar = function () {
    $('#delivery_callendar').remove();
    $('<div id="delivery_callendar"></div>').insertAfter($('#delivery_callendar_wrapper #callendar-before'));
    initCallendar();
};

var callendarOnRenderCell = function (CellDate) {
    var arr = [true, ''];
    var TodayDate = new Date();
    //var TodayDate = new Date(2020, 08, 18, 14, 40, 30);

    var daysDifference = Math.ceil(Math.abs(CellDate.getTime() - TodayDate) / (1000 * 60 * 60 * 24));
    var daysLimit = callendar_days_limit;

    if (getDateAsText(CellDate) == getDateAsText(TodayDate)) daysDifference = 0;
    
    if(getDateAsText(CellDate) == '22.09.2020') {
        return arr;
    }
    
    if (daysDifference == 0) {
        CarrerData = getCarrerData();
        if (isLastCarrierDay(TodayDate.getDay(), CarrerData.days)) {
            if (TodayDate.getHours() >= callendar_last_day_max_day_hour.split(':')[0]) {
                if (TodayDate.getMinutes() > callendar_last_day_max_day_hour.split(':')[1]) {
                    disableNextAllowedDay = true;
                }
            }
        }
    }

    if (CellDate.getTime() >= (getCallendarMinDate() - (1000 * 60 * 60 * 24))) {
        var dayNbr = CellDate.getDay();
        CarrerData = getCarrerData();

        if (CarrerData !== false && CarrerData.days[dayNbr] != 1 && CarrerData.days[dayNbr] != true) {
            return {
                disabled: true
            };
        }

        if (disableNextAllowedDay == true) {
            disableNextAllowedDay = false;
            return {
                disabled: true
            };
        }


        if (daysDifference > daysLimit) {
            return {
                disabled: true
            };
        }

        if (callendar_disabled_dates.indexOf(getDateAsText(CellDate)) > -1) {
            return {
                disabled: true
            };
        }
    }
    return arr;
};

var isLastCarrierDay = function (dayNbr, CarrerDataDays) {
    for (let i = (dayNbr + 1); i < CarrerDataDays.length; i++) {
        if (!(CarrerData !== false && CarrerData.days[dayNbr] != 1 && CarrerData.days[dayNbr] != true)) {
            return false;
        }
    }
    return true;
};

var selectCallendarDateTime = function (Callendar) {
    if (!CalledarActive) {
        return false;
    }
    var textDate = getTextDateFromCallendar(Callendar);
    var TimeRange = getSelectedTimeRange();
    var selectedCallendarDateTime = textDate.trim() + ' ' + TimeRange.trim();

    setSubmitAs("disabled");
    setAfterCallendarText('Wybrano termin dostawy: ' + selectedCallendarDateTime);

    $.ajax({
        url: window.location.href.split('?')[0].split('#')[0] + '?saveCallendarDate=' + selectedCallendarDateTime,
        success: function (res) {
            if (res == '1') {
                setSubmitAs("active");
            }
        },
        error: function (e, res) {
            setAfterCallendarText('Wystąpił problem z wyborem daty dostawy!');
        }
    });
};

var getSelectedTimeRange = function () {
    return $('#delivery_time option:selected').attr('value');
};

var setAfterCallendarText = function (text) {
    $('#callendar-after').text(text);
};

var checkCallendarVisibility = function () {
    var CarrerData = getCarrerData();

    if (CarrerData == false) {
        $('#delivery_callendar_wrapper').hide();
        CalledarActive = false;
    } else {
        $('#delivery_callendar_wrapper').show();
        CalledarActive = true;
    }
};

var getCarrerData = function (CarrerID) {
    var SelectedCarrerID = getSelectedCarrerID();

    for (var key in CarrersData) {
        if (Object.prototype.hasOwnProperty.call(CarrersData, key)) {
            if (CarrersData[key].id == SelectedCarrerID) {
                return CarrersData[key];
            }
        }
    }
    return false;
};

var getSelectedCarrerID = function () {
    return parseInt($('input[id^="delivery_option_"]:checked').attr('id').replace('delivery_option_', ''));
};

var getCallendarMinDate = function () {
    var TodayDate = new Date();
    //var TodayDate = new Date(2020, 08, 18, 14, 40, 30);
    TodayDate.setDate(TodayDate.getDate() + callendar_days_offset);

    if (TodayDate.getHours() >= callendar_max_day_hour) {
        TodayDate.setDate(TodayDate.getDate() + 1);
    }

    return TodayDate;
};

var getCallendarMinAllowedDate = function () {
    var TodayDate = getCallendarMinDate();
 
    CarrerData = getCarrerData(getSelectedCarrerID());   
    for (let i = 0; true; i++) {
        if(CarrerData.days[TodayDate.getDay()] != true) {
            TodayDate.setDate(TodayDate.getDate() + 1);
        } else {
            break;
        }
    } 

    return TodayDate;
};

var getDateAsText = function (Date) {
    var Day = Date.getDate();
    var Month = Date.getMonth() + 1;
    var Year = Date.getFullYear();

    if (Day < 10) {
        Day = '0' + Day;
    }
    if (Month < 10) {
        Month = '0' + Month;
    }

    return (Day + '.' + Month + '.' + Year);
};

var getTextDateAsDate = function (textDate) {
    var date_parts = textDate.split('.');
    return new Date(parseInt(date_parts[2]), (parseInt(date_parts[1]) - 1), parseInt(date_parts[0]));
};

var getTextDateFromCallendar = function (Callendar) {
    var Day = Callendar.selectedDay;
    var Month = Callendar.selectedMonth + 1;
    var Year = Callendar.selectedYear;
    if (Day < 10) {
        Day = '0' + Day;
    }
    if (Month < 10) {
        Month = '0' + Month;
    }
    return (Day + '.' + Month + '.' + Year);
};

var setSubmitAs = function (type) {
    if (type == "disabled") {
        $('#js-delivery [type="submit"]').attr("disabled", "disabled");
    } else if (type == "active") {
        $('#js-delivery [type="submit"]').removeAttr("disabled");
    } else {
        console.log("bad type");
    }
};


$(document).ready(function () {
    if (typeof callendar_days_offset != typeof undefined) {
        initCallendar();
    }

});