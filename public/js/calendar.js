//

(function() {

  // Launch calendar
  var calendar = $('#calendar');
  calendar.fullCalendar({
    header: {
      left: 'prev,next title today',
      right: 'month,agendaWeek,agendaDay'
    },
    editable: true,
    firstDay: 1,
    timeFormat:'H:mm',
    eventClick: editor.open,
    dayClick: editor.open
  });


  // Adaptive view based on window width
  var addSources = function(sources) {
    org.cals = sources;
    $.each(sources, function(index, source) {
      source.color = 'hsl(' + (index % 6) * 60 + ', 100%, 30%)';
      calendar.fullCalendar('addEventSource', source);
    })

    editor.init();
    calnav.render(sources)
  };

  //api.get('api/1/orgs/''/calendars', addSources);
  addSources(calendars);
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

  // When resizing the window, get the correct view.
  $(window).resize(adaptView);
  adaptView();

})();
