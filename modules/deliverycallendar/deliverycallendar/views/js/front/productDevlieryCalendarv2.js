var disableNextAllowedDay = false;

var productCallendarOnRenderCell = function (CellDate) {
    var TodayDate = new Date();
    //var TodayDate = new Date(2020, 08, 18, 14, 40, 30);
    var arr = [true, ''];
    var daysDifference = Math.ceil(Math.abs(CellDate - TodayDate) / (1000 * 60 * 60 * 24));
    var daysLimit = callendar_days_limit;
    if (getDateAsText(CellDate) == getDateAsText(TodayDate)) daysDifference = 0;

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

    if (CellDate >= getCallendarMinDate() - (1000 * 60 * 60 * 24)) {
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

var getCallendarMinDate = function () {
    var TodayDate = new Date();
    //var TodayDate = new Date(2020, 08, 18, 14, 40, 30);
    var newDate = TodayDate;
    newDate.setDate(newDate.getDate() + callendar_days_offset);
    if (TodayDate.getHours() >= callendar_max_day_hour) {
        newDate.setDate(newDate.getDate() + 1);
    }
    return newDate;
};

var isLastCarrierDay = function (dayNbr, CarrerDataDays) {
    for (let i = (dayNbr + 1); i < CarrerDataDays.length; i++) {
        if (!(CarrerData !== false && CarrerData.days[dayNbr] != 1 && CarrerData.days[dayNbr] != true)) {
            return false;
        }
    }
    return true;
};

var getCarrerData = function (CarrerID) {
    var SelectedCarrerID = getSelectedCarrerID();
    for (var key in CarrersData) {
        if (Object.prototype.hasOwnProperty.call(CarrersData, key)) {
            if (key == SelectedCarrerID) {
                return CarrersData[key];
            }
        }
    }
    return false;
};

var getSelectedCarrerID = function () {
    return this_order_data[0].carrer_reference;
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

$(document).ready(function () {
    if (typeof $.datepicker != typeof undefined) {
        $.datepicker.regional.pl = {
            monthNames: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
            monthNamesShort: ['Sty', 'Lut', 'Mar', 'Kwi', 'Maj', 'Cze', 'Lip', 'Sie', 'Wrz', 'Paź', 'Lis', 'Gru'],
            dayNames: ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'],
            dayNamesShort: ['Nd', 'Pon', 'Wt', 'Śr', 'Czw', 'Pt', 'Sob'],
            dayNamesMin: ['Nd', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'Sb'],
            firstDay: 1
        };
        $.datepicker.setDefaults($.datepicker.regional.pl);
    }

    if ($('.product-customization .datepicker').length) {

        $('.product-customization .datepicker').datepicker({
            minDate: getCallendarMinDate(),
            beforeShowDay: function (date) {
                return productCallendarOnRenderCell(date);
            },
            dateFormat: 'dd.mm.yy'
        });


        var TimeSelect = $('.time_ranges_select');
        var SelectedTime = TimeSelect.attr('data-selected');
        var timeRanges = callendar_time_ranges.split(',');
        for (var i = 0; i < timeRanges.length; i++) {
            var timeRange = timeRanges[i].trim();
            TimeSelect.append('<option value="' + timeRange + '"' + (((SelectedTime == '' && i == 0) || SelectedTime == timeRange) ? ' selected="true"' : '') + '>' + timeRange + '</option>');
        }

    }
});