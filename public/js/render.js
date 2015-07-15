'use strict';

var renderer = (function() {

  var renderEvent = function(calEvent, jsEvent, view) {

      console.log('eventclick')
    // Add the correct values to the corresponding fields in the eventDetail modal
    $(this).attr('data-toggle', 'modal');
    $(this).attr('data-target', '#eventModal');

    // CalEvent description
    $('#eventTitle').text(calEvent.title);
    $('#eventDescription').text(calEvent.description);

    if (calEvent.allDay) {
      $('#eventStart').text((calEvent.start).format('YYYY/MM/DD') + ' (all day)');
    } else {
      $('#eventStart').text((calEvent.start).format('YYYY/MM/DD HH:mm'));
    }

    // If end-date is specified, show the part of the modal, otherwise hide it.
    if (calEvent.end) {
      $('#eventEnd').text((calEvent.end).format('YYYY/MM/DD HH:mm'));
      $('#eventEnds').show();
    } else {
      $('#eventEnds').hide();
    }

    $('#groupName').text(calEvent.groupName);

    // Fix links
    $('#editEvent').attr('href', 'calendar/event/edit/' + calEvent.id);
    $('#icalEvent').attr('href', 'export/appointment/find/' + calEvent.id);
    $('#deleteEvent').attr('data-href', 'calendar/event/delete/' + calEvent.id);
  };

  return {
    renderEvent: renderEvent
  };
})();
