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

    $('#basicExample .time').timepicker({
        'showDuration': true,
        'timeFormat': 'g:ia'
    });

    $('#basicExample .date').datepicker({
        'format': 'm/d/yyyy',
        'autoclose': true
    });

    // initialize datepair
    $('#basicExample').datepair();
});