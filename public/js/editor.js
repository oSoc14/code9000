'use strict';

var editor = (function() {
  var active = {
    ev: {},
    id: 0
  };
  var $cal = $('#calendar');
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
    active.id = 0;
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
  var init = function() {

  };

  // Delete event by id
  var remove = function() {
    if (!active.id) return;
    api.deleteEvent(active.id).success(function(data) {
      close();
      console.log()
      $cal.fullCalendar('removeEvents', active.ev.id);
    }).error(function(data) {
      close();
    });
  };

  // Update existing event
  var update = function(data) {
    console.log(active.ev);

    // Update existing event
    active.ev.title = data.title;
    active.ev.description = data.description;
    active.ev.location = data.location;
    active.ev.start = Date.parse(data.start);
    active.ev.end = Date.parse(data.end);
    active.ev.calendar_id = data.calendar_id;
    active.ev.allDay = data.allDay;

    // Render
    $cal.fullCalendar('updateEvent', active.ev);

    // Sync with backend
    data.id = active.ev.id;
    api.postEvent(data).success(function(data) {
      $cal.fullCalendar('refetchEvents');
    }).error(function(error) {
      console.log(error)
    });
  };

  // Create new event
  var create = function(e) {
    e.preventDefault();
    var x = $('.popover');

    // Retrieve form values
    var formdata = {
      title: x.find('.input-title').val(),
      description: x.find('.input-descr').val(),
      location: x.find('.input-location').val(),
      start: x.find('.d1').val() + ' ' + x.find('.t1').val() || '00:00',
      end: x.find('.d2').val() + ' ' + x.find('.t2').val() || '00:00',
      allDay: x.find('.t1').val() === '00:00' && x.find('.t2').val() === '00:00',
      calendar: x.find('.input-cals').val(),
    };

    // Validate input?

    // Update
    if (active.ev._id) {
      close();
      update(formdata);
      return;
    }

    // Open calendar that event was added to


    // Apply to backend
    api.postEvent(formdata).success(function(data) {
      close();
      $cal.fullCalendar('refetchEvents');
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

    if (!user.logged_in) return;

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

    var $target = $(jsEvent.target);

    if (ev.editable) {

      d1Options.value = ev.start.format('YYYY-MM-DD');
      t1Options.value = ev.start.format('HH:mm');
      d2Options.value = ev.end.format('YYYY-MM-DD');
      t2Options.value = ev.end.format('HH:mm');
      active.ev = ev;
      active.id = ev.id || 0;

      // Launch popover
      $target.popover(popoverOptions);
      $target.popover('show');

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
    } else {

      // Set input fields
      $('.read-title').text(ev.title);
      $('.read-descr').text(ev.description);
      $('.read-location').text(ev.location);
      $('.read-cal').text('Kalender ' + calendars[ev.calendar_id].name);

      var a = ev.start.format('MMM');
      var b = ev.start.format('DD');
      var c = ev.end.format('MMM');
      var d = ev.end.format('DD');

      // Create readable date
      var readableDate = b;
      if (ev.allDay || ev.start.format('HHmm') === '0000') {
        if (a === c && b == d - 1) {
          readableDate += ev.end.format(' MMMM');
        } else if (a === c) {
          readableDate += ev.end.format(' - DD MMMM');
        } else {
          readableDate += ev.start.format(' MMMM') + ev.end.format(' - DD MMMM');
        }
      } else {
        readableDate += ev.start.format(' MMMM HH:mm');
        if (a === c && b === d) {
          readableDate += ev.end.format(' - HH:mm');
        } else {
          readableDate += ev.end.format(' - DD MMMM HH:mm');
        }
      }
      $('.read-dt').text(readableDate);
      $target.popover({
        container: '#calendar',
        html: true,
        placement: 'auto right',
        content: $('.read-event-template').html()
      });
      $target.popover('show');
    }
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
