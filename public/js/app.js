/*
 * When the page is loaded run the following code
 */
$(document).ready(function () {

  // When the menu-toggle is clicked open the sidebar.
  $('[data-toggle=offcanvas]').click(function () {
    $('#backdrop').toggleClass('hidden');
    $('.sidebar-wrapper').toggleClass('sidebar-active');
  });

  // When a delete button is clicked open op the confirmation modal and fill in the correct url
  $('#confirm-delete').on('show.bs.modal', function (e) {
    $(this).find('.btn-educal-danger').attr('href', $(e.relatedTarget).data('href'));
  });



  // Shows the correct modal on the landing page if an error has occured.


  if($("#registerSchoolModal").data("errors") == true){
    $('#registerUserModal').modal('hide');
    $('#registerSchoolModal').modal('show');
    $('#loginModal').modal('hide');
    $('#requestResetPasswordLink').modal('hide');
    $('#requestResetPasswordLinkSuccess').modal('hide');
  }

  if($("#email-reset").val() != ''){
      $('#registerUserModal').modal('hide');
      $('#registerSchoolModal').modal('hide');
      $('#loginModal').modal('hide');
      $('#requestResetPasswordLink').modal('show');
      $('#requestResetPasswordLinkSuccess').modal('hide');
  }

  if($("#email-success").val() != ''){
      $('#registerUserModal').modal('hide');
      $('#registerSchoolModal').modal('hide');
      $('#loginModal').modal('hide');
      $('#requestResetPasswordLink').modal('hide');
      $('#requestResetPasswordLinkSuccess').modal('show');
  }

  if ($("#registerUserModal").data("errors") == true) {
    $('#registerUserModal').modal('show');
    $('#registerSchoolModal').modal('hide');
    $('#loginModal').modal('hide');
    $('#requestResetPasswordLink').modal('hide');
    $('#requestResetPasswordLinkSuccess').modal('hide');
  }

  if ($("#loginModal").data("errors") == true) {
    $('#registerUserModal').modal('hide');
    $('#registerSchoolModal').modal('hide');
    $('#loginModal').modal('show');
    $('#requestResetPasswordLink').modal('hide');
    $('#requestResetPasswordLinkSuccess').modal('hide');
  }


  // If the checkbox for repeating events is checked at edit/appointment/{id}, show the repeat input fields.
  if( $('.form-repeat-container').length != 0){
    // If checkbox is already checked, show repeat-container on page load
    if ($('#repeat').prop('checked')) {
      $('.form-repeat-container').show();
    }
    ;
    $('input#repeat').click(function () {
      $('.form-repeat-container').slideToggle();
    });
  }

  // When the user activated checkbox is changed, call the user/activate/{id} route.
  $('.activateUser').on('click', function (ev) {
    ev.preventDefault;
    var that = $(this);
    // Get the id for the user from the checkbox
    var userid = that.data('userid');
    $.ajax({
      type: "GET",
      url: "user/activate/" + userid,
      cache: false,
      dataType: "json",
      contentType: "application/json",
      // While the server is activating the user disable the checkbox.
      beforeSend: function () {
        that.prop('disabled', true);
      },
      success: function (data) {
        that.prop('disabled', false);
      },
      error: function (xhr, status, errorThrown) {
        console.log(status + ', ' + errorThrown);
        that.prop('disabled', false);
      }
    });
  });

  // When a field for the repeating events is changed, calculate the nummber of repeats.
  $('#repeat_type').change(function (ev) {
    calculateRepeats();
  });

  $('#repeat_freq').change(function (ev) {
    calculateRepeats();
  });

  $('#datetimepicker1').change(function (ev) {
    calculateRepeats();
  });

  // When the type of export is changed in the group list, select the content.
  $('.linkTo').on('click', function () {
    var that = $(this);
    var id = that.data('group-id');
    $('.linkToText_' + id).val(that.data('link')).fadeOut(300).fadeIn(300).select();
  });

  $('.linkToText').on('click', function () {
    $(this).select();
  });

});

/*
 * When the page is loaded initialize the datetimepickers
 */
jQuery(function () {
  if ($('#datetimepicker1').length != 0) {
    jQuery('#datetimepicker1').datetimepicker({
      format: 'Y/m/d H:i',
      mask: true,
      onShow: function (ct) {
        // If the value off the datetimepicker isn't empty or the placeholder, set the max date and time for the startdate
        if (jQuery('#datetimepicker2').val() != '' && jQuery('#datetimepicker2').val() != '____/__/__ __:__') {
          this.setOptions({
            maxDate: moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD'),
            maxTime: moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('HH:mm')
          })
        } else {
          this.setOptions({
            maxDate: false,
            maxTime: false
          })
        }
      },
      onSelectDate: function () {
        // When the end date isn't set, set the max time for the startdate
        if (jQuery('#datetimepicker2').val() != '') {
          if (moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD') == moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD')) {
            this.setOptions({
              maxTime: moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('HH:mm')
            })
          } else {
            this.setOptions({
              maxTime: false
            })
          }
        } else {
          this.setOptions({
            maxTime: false
          })
        }
      },
      timepicker: true,
      defaultSelect: true
    });

    jQuery('#datetimepicker2').datetimepicker({
      format: 'Y/m/d H:i',
      mask: true,
      onShow: function (ct) {
        // If the value off the datetimepicker isn't empty, set the min date and time for the enddate
        if (jQuery('#datetimepicker1').val() != '') {
          this.setOptions({
            minDate: moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD'),
            minTime: moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('HH:mm')
          })
        } else {
          this.setOptions({
            minDate: false,
            minTime: false
          })
        }
      },
      onSelectDate: function () {
        // When the end date isn't set, set the min time for the startdate
        if (jQuery('#datetimepicker2').val() != '') {
          if (moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD') == moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD')) {
            this.setOptions({
              minTime: moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('HH:mm')
            })
          } else {
            this.setOptions({
              minTime: false
            })
          }
        } else {
          this.setOptions({
            minTime: false
          })
        }
      },
      timepicker: true,
      defaultSelect: true
    });

    // Edit event form: get value for dateTimePicker3 when applicable (recurring events)
    var calcDate = null;
    if ($('#repeat').prop('checked')) {
      var type = $('#repeat_type').val();
      var startDate = moment($('#datetimepicker1').val(), 'YYYY/MM/DD HH:mm');
      var freq = $('#repeat_freq').val();

      for (i = 1; i < $('#nr_repeat').val(); i++) {
        startDate = startDate.add(type, freq);
      }
      calcDate = moment(startDate, 'YYYY/MM/DD').format('YYYY/MM/DD');
    }

    jQuery('#datetimepicker3').datetimepicker({
      format: 'Y/m/d',
      mask: true,
      onShow: function (ct) {
        // Set the min date for the end repeat date
        if (jQuery('#datetimepicker2').val() != '' && jQuery('#datetimepicker2').val() != '____/__/__ __:__') {
          this.setOptions({
            minDate: moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD')
          })
        } else {
          if (jQuery('#datetimepicker1').val() != '' && jQuery('#datetimepicker1').val() != '____/__/__ __:__') {
            this.setOptions({
              minDate: moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD')
            })
          } else {
            this.setOptions({
              minDate: false
            });
          }
        }
      },
      onSelectDate: function () {
        // Calculate the nummber of repeating events
        calculateRepeats();
      },
      timepicker: false,
      defaultSelect: true,
      value: calcDate,
      format: 'Y/m/d'
    });
  }

  // When the "register as school" button is clicked, hide the "register as user" modal
  $('#showSchoolRegisterModal').click(function () {
    $('#registerUserModal').modal('hide');
    $('#registerSchoolModal').modal('show');
  });
});

/**
 * Calculates the amount of times an event
 */
function calculateRepeats() {
  // Get values from input fields
  var freq = $('#repeat_freq').val();
  var endDate = moment($('#datetimepicker3').val(), 'YYYY/MM/DD');

  // Check if we have to calculate the repeat count
  if (freq > 0 && endDate.isValid() && $('#repeat').prop('checked')) {

    // Get values from input fields
    var type = $('#repeat_type').val();
    var startDate = $('#datetimepicker1').val();
    startDate = moment(startDate, 'YYYY/MM/DD');

    // Calculate repeat count
    if (startDate.isValid() && startDate.isBefore(endDate) && type) {
      $('#nr_repeat').removeAttr('value');
      var i = 0;
      while (startDate.isBefore(moment(endDate).add(23, 'hours'))) {
        startDate = startDate.add(type, freq);
        i++;
      }

      // Put the repeat count in a hidden field
      $('#nr_repeat').val(i);

    } else {
      // If we don't have to calculate the repeat count, reset the hidden field
      $('#nr_repeat').removeAttr('value');
    }
  }
}