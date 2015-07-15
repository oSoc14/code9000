'use strict';

var editor = (function() {
  var active;
  var saving = false;
  var success = false;

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

  // Hide currently open popover
  var create = function(callback) {
    if (!active) return;
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

  var open = function(date, event, view) {
    console.log(date)
    close();

    if (!view.type == 'month') {
      return;
    }

    // Create new popover
    var options = {
      container: '#calendar',
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
      value: date.format('YYYY-MM-DD'),
      timepicker: false,
      onChangeDateTime: logic,
      onShow: logic
    });
    $('.input-time').datetimepicker({
      format: 'H:i',
      value: moment().format('HH:00'),
      datepicker: false,
      onChangeDateTime: logic,
      onShow: logic
    });
    $('.event-create').on('click', create);
  }
  return {
    open: open,
    active: active,
    init: init,
    logic: logic
  };
})();
