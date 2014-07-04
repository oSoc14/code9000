$(document).ready(function() {

  // Load events out of the database via the API
  var _events = [];
  getEvents();
  function getEvents()
  {
    $('#addEvent').hide();
    $.ajax({
      type:"GET",
      url: "calendar/api/events",
      cache: false,
      dataType: "json",
      contentType: "application/json",
      success:function(data){
        parseEvents(data);
        $('#preloader').hide();
        $('#addEvent').show();
      },
      error:function(xhr, status, errorThrown) {
        alert(status + ', ' + errorThrown);
      }
    });
  }
  // Parse the events gotten from the database and push them to global variable
  function parseEvents(events)
  {
    $.each( events, function( index, value ){
      var newItem = {};
      newItem['title'] = value['title'];
      newItem['start'] = value['start_date'];
      newItem['end']   = value['end_date'];
      newItem['url']    = 'calendar/event/' + value['id'];
      _events.push(newItem);
    });
    renderEvents();
  }
  // Render the calendar and all events on it
  function renderEvents()
  {
    // Full calendar plugin
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      defaultDate: '2014-06-12',
      editable: true,
      events: _events,
      timeFormat: 'H(:mm)',
      theme: false,
      themeButtonIcons: {
        prev: 'circle-triangle-w',
        next: 'circle-triangle-e',
        prevYear: 'seek-prev',
        nextYear: 'seek-next'
      },
      /*{
       url: 'php/get-events.php',
       error: function() {
       $('#script-warning').show();
       }
       }*/
      loading: function(bool) {
        //$('#loading').toggle(bool);
      }
    });
  }
});