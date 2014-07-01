$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });

  $('#datetimepicker1').datepicker();
  $('#datetimepicker2').datepicker();
});