$(document).ready(function() {
  // Full calendar plugin
  $('#calendar').fullCalendar({
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    defaultDate: '2014-06-12',
    editable: false,
    events: [
      {
        title: 'Event1',
        start: '2014-06-12',
        url: 'http://www.google.be'
      },
      {
        title: 'Event2',
        start: '2014-06-14'
      }
      // etc...
    ],
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
});