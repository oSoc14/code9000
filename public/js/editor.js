'use strict';

var editor = (function() {
  var active;

  // Hide currently open popover
  var close = function() {
    if (active) {
      active.removeClass('event-editing');
      active.popover('hide');
      active = null;
    }
  }

  // Fixes impossible date ranges
  var logic = function() {
    var x = $('.popover');
    if (!x) return;
    var d1 = x.find('.d1').val();
    var t1 = x.find('.t1').val() || '00:00';
    var d2 = x.find('.d2').val();
    var t2 = x.find('.t2').val() || '00:00';
    if (!d2 || moment(d1 + 'T00:00').isAfter(d2 + 'T00:00')) {
      x.find('.d2').val(d1);
      d2 = d1;
    }
    if (!t2 || moment(d1 + 'T' + t1).isAfter(d2 + 'T' + t2)) {
      x.find('.t2').val(t1);
      t2 = t1;
    }
    this.setOptions({
      minDate:d1
    });
  }

  var open = function(date, event, view) {

    close();

    if (!view.type == 'month') {
      return;
    }

    // Create new popover
    var options = {
      container: "#calendar",
      html: true,
      placement: 'auto left',
      content: $('.new-event').html()
    };
    active = $(event.target);
    active.addClass('event-editing');
    if (!active) {
      return;
    }
    active.popover(options);
    active.popover('show');
    $('#backdrop').addClass('visible');

    $('.input-date').datetimepicker({
      format: 'Y-m-d',
      timepicker: false,
      onChangeDateTime: logic,
      onShow: logic
    });
    $('.input-time').datetimepicker({
      format: 'H:i',
      datepicker: false,
      onChangeDateTime: logic,
      onShow: logic
    });
  }
  return {
    open: open,
    active: active,
    logic: logic
  };
})();
