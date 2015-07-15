//

(function() {

  // Launch calendar
  var calendar = $('#calendar');
  calendar.fullCalendar({
    header: {
      left: 'prev,next title today',
      right: 'month,agendaWeek,agendaDay'
    },
    eventSources: [{
            url: 'api/1/orgs/1/events',
            textColor: 'black'  // an option!
        }],
    editable: true,
    firstDay: 1,
    eventClick: renderer.renderEvent,
    dayClick: editor.open
  });

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
  }

  // When resizing the window, get the correct view.
  $(window).resize(adaptView);
  adaptView();

})();
