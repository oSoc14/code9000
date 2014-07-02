$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });

  $('#datetimepicker1').datetimepicker();
  $('#datetimepicker2').datetimepicker();
});