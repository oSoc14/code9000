'use strict';

var editor = (function() {
  var active = {
    id: 0
  };
  var saving = false;
  var success = false;
  var popoverOptions = {
    container: '#calendar',
    html: true,
    placement: 'auto bottom',
    content: $('.new-event').html()
  };

  // Hide currently open popover
  var close = function() {
    if (active.el) {
      active.el.removeClass('event-editing');
      active.el.popover('destroy');
      active.el = null;
    }
  }

  var calById = function(id) {
    for (var i = 0, len = calendars.length; i < len; i++) {
        if (calendars[i].id === id)
            return calendars[i];
    }
    return null;
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
  }

  // Initialize popover
  var init = function(callback) {
    var $year = $('.select-year');
    var $cals = $('.select-cals');
    console.log(org.cals)
    var $glob = org.cals[0].id
    $.each(org.cals, function(index, cal) {
      if (cal.parent_id !== $glob) return;
      var y = document.createElement('span');
      var $y = $(y);
      $y.text(cal.name.substring(0, 1))
      $y.data('slug', cal.slug)
      $y.on('click', function() {
        console.log('wut')
      })
      $year.append($y);
    });
  }

  // Delete event by id
  var remove = function() {
    if (!active.id) return;
    api.deleteEvent(active.id).success(function(data) {
      close();
      console.log()
      $('#calendar').fullCalendar('removeEvents', active.ev.id);
    }).error(function(data) {
      close();
    });
  }

  // Hide currently open popover
  var create = function(callback) {
    if (!active.id == 0) return;
    var x = $('.popover');
    var event = {
      title: x.find('.input-title').val(),
      name: x.find('.input-title').val(),
      description: x.find('.input-descr').val(),
      location: x.find('.input-location').val(),
      start: x.find('.d1').val() + ' ' + x.find('.t1').val() || '00:00',
      end: x.find('.d2').val() + ' ' + x.find('.t2').val() || '00:00',
      calendar: x.find('.input-cals').val(),
    };
    api.postEvent(event).success(function(data) {
      console.log(data)
      close();
      $('#calendar').fullCalendar('addEventSource', [event]);
    }).error(function(data) {
      console.log(data)
      close();
      $('#calendar').fullCalendar('addEventSource', [event]);
    });
  }

  var open = function(clicked, event, view) {

    // Close current popover
    close();

    // Only show popover in month view
    if (!view.type == 'month') {
      return;
    }

    // Default options
    var d1Options = {
      format: 'Y-m-d',
      timepicker: false,
      onChangeDateTime: logic,
      onShow: logic
    };
    var d2Options = $.extend({}, d1Options);
    var t1Options = {
      format: 'H:i',
      datepicker: false,
      onChangeDateTime: logic,
      onShow: logic
    };
    var t2Options = $.extend({}, t1Options);

    // Create new event or edit existing event
    if (clicked._isAMomentObject) {
      d1Options.value = clicked.format('YYYY-MM-DD');
      t1Options.value = moment().format('HH:00');
      d2Options.value = clicked.format('YYYY-MM-DD');
      t2Options.value = moment().format('HH:00');
    } else {
      d1Options.value = clicked.start.format('YYYY-MM-DD');
      t1Options.value = clicked.start.format('HH:mm');
      d2Options.value = clicked.end.format('YYYY-MM-DD');
      t2Options.value = clicked.end.format('HH:mm');
      active.ev = clicked;
    }

    // Launch popover
    active.el = $(event.target);
    if (!active.el) return;
    active.el.popover(popoverOptions);
    active.el.popover('show');
    active.el.addClass('event-editing');
    active.id = clicked.id || 0;

    // Launch datetimepicker
    $('.input-date.d2').datetimepicker(d2Options);
    $('.input-date.d1').datetimepicker(d1Options);
    $('.input-time.t2').datetimepicker(t2Options);
    $('.input-time.t1').datetimepicker(t1Options);

    // Button eventhandlers
    $('.popover .btn-success').on('click', create);
    $('.popover .btn-cancel').on('click', close);
    $('.popover .btn-danger').on('click', remove);

    if(!active.id){
      $('.popover .btn-danger').addClass('invisible');
    }
  }

  return {
    open: open,
    active: active,
    init: init,
    logic: logic
  };
})();
