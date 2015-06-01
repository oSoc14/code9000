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
        'showDuration': true,
        'timeFormat': 'H:i'
    });

    $('#date-time-picker1 .date').datepicker({
        dateFormat: 'd-m-yy',
        autoclose: true,
        firstDay: 1,
        beforeShowDay: function(date) {
            var weekend = date.getDay() == 6 || date.getDay() == 0;
            return [1, weekend ? 'myweekend' : ''];
        }
    });

    toggleDates('#date-time-picker1');

});

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