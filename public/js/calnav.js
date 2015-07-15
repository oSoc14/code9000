'use strict';

var calnav = (function() {

  var nav = $('.nav-cals');

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

  var render = function(sources) {
    var levels = {
      1: 1
    };
    var glob = sources[0];
    $.each(sources, function(index, cal) {
      if (!levels[cal.parent_id]) {
        levels[cal.id] = 1;
      } else {
        levels[cal.id] = levels[levels[cal.parent_id]] + 1;
      }
    })

    $.each(sources, function(index, cal) {
      renderCal(cal, levels[cal.parent_id]);
    })

  };

  return {
    render: render
  };
})();
