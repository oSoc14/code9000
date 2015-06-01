/*
 * MultiCal
 * Author: Sander Meert
 */
$(document).ready(function () {
    var today = new Date();
    var y = today.getFullYear();
    $('#full-year').multiDatesPicker({
        addDates: ['10/14/'+y, '02/19/'+y, '01/14/'+y, '11/16/'+y],
        numberOfMonths: [3,4],
        defaultDate: '1/1/'+y
    });

});