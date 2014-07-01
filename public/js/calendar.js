$(document).ready(function() {

  var _events = [];
  getEvents();
  function getEvents()
  {
    $('#addEvent').hide();
    var events = [];
    $.ajax({
      type:"GET",
      url: "calendar/api/events",
      cache: false,
      dataType: "json",
      contentType: "application/json",
      success:function(data){
        setTimeout(function(){
          events = data['school']['groups'];
          parseEvents(events);
          $('#preloader').hide();
          $('#addEvent').show();
        },800);
      },
      error:function(xhr, status, errorThrown) {
        alert(status + ', ' + errorThrown);
      }
    });
  }

  function parseEvents(events)
  {
    $.each( events, function( index, value ){
      $.each( value['appointments'], function( i, v ){
        var newItem = {};
        newItem['title'] = v['title'];
        newItem['start'] = v['start_date'];
        newItem['end']   = v['end_date'];
        _events.push(newItem);
      });
    });
    console.log(_events);
    renderEvents();
  }

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
      theme: true,
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
        $('#loading').toggle(bool);
      }
    });
  }
});