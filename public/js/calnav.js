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
    var id = $target.parent('label').data('cal');
      console.log('change', id)

    // Apply to calendar
    if ($target.is(":checked")) {
      $('#calendar').fullCalendar('addEventSource', calendars[id]);
      $target.parent('label').addClass('active');
    } else {
      $('#calendar').fullCalendar('removeEventSource', calendars[id].url);
      $target.parent('label').removeClass('active');
    }
  };

  var init = function(e) {

    // Toggle by year
    nav.on('click', toggle);

    // Toggle visibility
    nav.find('input').on('change', change);

  };

  return {
    init: init
  };
})();
