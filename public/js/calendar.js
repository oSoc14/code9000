//

(function() {

  // Launch calendar
  var calendar = $('#calendar');
  calendar.fullCalendar({
    header: {
      left: 'title prev,next today',
      right: 'month,agendaWeek,agendaDay'
    },
    editable: true,
    selectable: true,
    unselectCancel: '.popover',
    firstDay: 1,
    timeFormat: 'H:mm',
    eventSources: [root],
    eventClick: editor.open,
    eventDrop: editor.drop,
    eventResize: editor.drop,
    select: editor.select,
    unselect: editor.close
  });

  editor.init();
  calnav.init();

  // Adaptive view based on window width
  var adaptView = function() {
    var h = $('main').height();
    if ($(window).width() < 850) {
      calendar.fullCalendar('changeView', 'agendaDay');
      calendar.fullCalendar('option', 'height', 5000);
    } else if ($(window).width() > 850 && $(window).width() < 1080) {
      calendar.fullCalendar('changeView', 'agendaWeek');
      calendar.fullCalendar('option', 'height', h);
    } else {
      calendar.fullCalendar('changeView', 'month');
      calendar.fullCalendar('option', 'height', h);
    };
  };

  // Generate export
  $('.btn-export').on('click', function(e) {;
    window.location.href = $(this).data('base') + '/' + calnav.active().join('+');
  });

  // Close editor on blur
  $(document.body).on('click', editor.close);
  calendar.on('click', function(e) {
    e.stopPropagation();
  });

  // When resizing the window, get the correct view.
  $(window).resize(adaptView);
  adaptView();

})();
