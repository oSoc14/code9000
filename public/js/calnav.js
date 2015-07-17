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
  };

  return {
    render: render
  };
})();
