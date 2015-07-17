'use strict';

var editor = (function() {
  var active = {
    ev: {},
    id: 0
  };
  var saving = false;
  var success = false;
  var popoverOptions = {
    container: '#calendar',
    html: true,
    placement: 'auto right',
    content: $('.new-event').html()
  };

  // Hide currently open popover
  var close = function() {
    $('.input-date.d2').datetimepicker('destroy');
    $('.input-date.d1').datetimepicker('destroy');
    $('.input-time.t2').datetimepicker('destroy');
    $('.input-time.t1').datetimepicker('destroy');
    $('.popover').remove();
  };

  var calById = function(id) {
    for (var i = 0, len = calendars.length; i < len; i++) {
      if (calendars[i].id === id)
        return calendars[i];
    }
    return null;
  };

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
  };

  // Initialize popover
  var init = function(callback) {
    var $year = $('.select-year');
    var $cals = $('.select-cals');
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
  };

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
  };

  // Create new event
  var create = function(e) {
    e.preventDefault();
    var x = $('.popover');
    var values = {
      title: x.find('.input-title').val(),
      name: x.find('.input-title').val(),
      description: x.find('.input-descr').val(),
      location: x.find('.input-location').val(),
      start: x.find('.d1').val() + ' ' + x.find('.t1').val() || '00:00',
      end: x.find('.d2').val() + ' ' + x.find('.t2').val() || '00:00',
      calendar: x.find('.input-cals').val(),
    };

    if (active.id) {
      values.id = active.id;
    }

    // Apply to backend
    api.postEvent(values).success(function(data) {
      close();
      if (active.id) {
        $.extend(active.ev, data);
        $('#calendar').fullCalendar('updateEvent', active.ev);
      } else {
        active.id = data.id;
        $('#calendar').fullCalendar('renderEvent', data);
      }
    }).error(function(error) {
      console.log(error)
    });
  };

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

  // Show popover to create event
  var select = function(start, end, jsEvent, view) {

    // Close current popover
    close();

    // Create new event or edit existing event
    d1Options.value = start.format('YYYY-MM-DD');
    t1Options.value = start.format('HH:mm');
    d2Options.value = end.format('YYYY-MM-DD');
    t2Options.value = end.format('HH:mm');

    // Launch popover
    var $target = $(jsEvent.target);
    $target.popover(popoverOptions);
    $target.popover('show');
    $('.popover').on('click', function(e) {
      e.stopPropagation();
    });

    // Launch datetimepicker
    $('.input-date.d2').datetimepicker(d2Options);
    $('.input-date.d1').datetimepicker(d1Options);
    $('.input-time.t2').datetimepicker(t2Options);
    $('.input-time.t1').datetimepicker(t1Options);

    $('.popover .btn-danger').addClass('invisible');
  };

  var open = function(ev, jsEvent, view) {

    // Close current popover
    close();

    d1Options.value = ev.start.format('YYYY-MM-DD');
    t1Options.value = ev.start.format('HH:mm');
    d2Options.value = ev.end.format('YYYY-MM-DD');
    t2Options.value = ev.end.format('HH:mm');
    active.ev = ev;

    // Launch popover
    var $target = $(jsEvent.target);
    $target.popover(popoverOptions);
    $target.popover('show');
    active.id = ev.id || 0;

    // Launch datetimepicker
    $('.input-date.d2').datetimepicker(d2Options);
    $('.input-date.d1').datetimepicker(d1Options);
    $('.input-time.t2').datetimepicker(t2Options);
    $('.input-time.t1').datetimepicker(t1Options);

    // Set input fields
    $('.input-title').val(ev.title);
    $('.input-descr').val(ev.description);
    $('.input-location').val(ev.location);
    $('.input-cals option[value="' + ev.calendar_id + '"]').prop('selected', true);
  };

  window.addEventListener('submit', create);

  return {
    open: open,
    remove: remove,
    close: close,
    active: active,
    select: select,
    init: init,
    logic: logic
  };
})();
