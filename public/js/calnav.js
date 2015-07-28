'use strict';

var calnav = (function() {

  var nav = $('.navcals');

  var renderCal = function(cal, level) {
    var input = document.createElement('input');
    input.type = 'checkbox';
    input.checked = 'checked';
    $(input).on('change', function(e) {
      var active = $(this).is(":checked");
      var l = $(this).parent('label');
      l.toggleClass('active', active);

      // Apply to calendar
      $('#calendar').fullCalendar((active ? 'add' : 'remove') + 'EventSource', l.data('url'))
    })

    var label = document.createElement('label');
    var $label = $(label);
    $label.append(input).append(cal.name);
    $label.data('id', cal.id);
    $label.data('slug', cal.slug);
    $label.data('url', cal.url);
    $label.addClass('level--' + level);
    $label.addClass('active');
    nav.append($label);
  };

  var active = function() {
    var list = [];
    for (var cal in calendars) {
      if (calendars.hasOwnProperty(cal)) {
        var c = calendars[cal];
        if (c.active)
          list.push(c.slug);
      }
    }
    return list.length ? list : ['all'];
  };

  var toggle = function(e) {
    e.stopPropagation();
    var $target = $(e.target);
    if ($target.hasClass('level--1')) {
      console.log('toggle')
      nav.find('.level--0[data-parent="' + $target.data('cal') + '"]').toggle();
    }
  };

  var change = function(e) {
    e.stopPropagation();
    var $target = $(e.target);
    var $label = $target.closest('label');
    var id = $label.data('cal');
    var activate = $target.is(":checked");
    var parent = calendars[calendars[id].parent_id];
    console.log('change', id)

    // Apply to calendar
    if (activate) {
      calendars[id].active = true;
      $('#calendar').fullCalendar('addEventSource', calendars[id]);
      $label.addClass('active');
      $label.find('.checkbox').css('background', $label.data('color'));
    } else {
      calendars[id].active = false;
      $('#calendar').fullCalendar('removeEventSource', calendars[id].url);
      $label.removeClass('active');
      $label.find('.checkbox').css('background', 'rgba(0,0,0,0)');
    }

    // Toggle year eventsource
    var activeSiblings = 0;
    for (var cal in calendars) {
      if (calendars.hasOwnProperty(cal)) {
        var c = calendars[cal];
        if (c.parent_id === calendars[id].parent_id && c.active)
          activeSiblings++;
      }
    }
    if (activate && activeSiblings === 1) {
      $('#calendar').fullCalendar('addEventSource', parent);
    } else if (!activate && !activeSiblings) {
      $('#calendar').fullCalendar('removeEventSource', parent.url);
    }
  };

  var init = function(e) {

    // Toggle by year
    nav.on('click', toggle);

    // Toggle visibility
    nav.find('input').on('change', change);

  };

  return {
    init: init,
    active: active
  };
})();
