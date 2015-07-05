/*
 * MultiCal
 * Author: Sander Meert
 */
$(document).ready(function () {
    var today = new Date();
    var y = today.getFullYear();
    $('#full-year').multiDatesPicker({
        numberOfMonths: [4,3],
        // TODO: Handle years better, add ignored dates
        defaultDate: '9/1/'+(y-1),
        firstDay: 1,
        beforeShowDay: function(date) {
            var weekend = date.getDay() == 6 || date.getDay() == 0;
            return [1, weekend ? 'myweekend' : ''];
        },
        onSelect: function() {
            $('#repeat-dates').val($('#full-year').multiDatesPicker('getDates'));
        }
    });

    $('input#repeat').click(function () {
        toggleDates('#date-time-picker1', true);
    });

    $('#date-time-picker1 .time').timepicker({
        timeFormat: 'H:i',
        step: 10
    });

    $('#date-time-picker1 .date').datepicker({
        dateFormat: 'dd-mm-yy',
        autoclose: true,
        firstDay: 1,
        beforeShowDay: function(date) {
            var weekend = date.getDay() == 6 || date.getDay() == 0;
            return [1, weekend ? 'myweekend' : ''];
        },
        onClose: function(date) {
            var that = $(this);
            if(that.hasClass('start') && that.next().val() == '') {
                that.next().val(that.val());
            }
        }
    });

    // Update second time to default to current time + 1h
    $('#date-time-picker1 .time').on('changeTime', function() {
        var that = $(this);
        if(that.hasClass('start')) {
            var curT = that.val();
            var d = curT.split(":");
            var dd = new Date();
            dd.setHours(d[0]);
            dd.setHours(dd.getHours()+1);
            dd.setMinutes(d[1]);
            var newS = pad(dd.getHours()) + ':' + pad(dd.getMinutes());
            that.next().val(newS);
        }
    });

    toggleDates('#date-time-picker1');
});

function pad(val) {
    if(val < 10) {
        return '0' + val;
    } else {
        return val;
    }
}

function toggleDates(tar, modal) {
    if ($('#repeat').prop('checked')) {
        $(tar + ' .date').hide();
        $(tar + ' .date-addon').hide();
    } else {
        $(tar + ' .date').show();
        $(tar + ' .date-addon').show();
    }

    if(modal) {
        $('#year-modal').modal('show');
    }
}