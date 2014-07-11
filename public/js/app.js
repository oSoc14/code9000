$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('#backdrop').toggleClass('hidden');
    $('.sidebar-wrapper').toggleClass('sidebar-active');
  });

  if($("#registerSchoolModal").data("errors") == true){
    $('#registerUserModal').modal('hide');
    $('#registerSchoolModal').modal('show');
    $('#loginModal').modal('hide');
  }

  if($("#registerUserModal").data("errors") == true){
    $('#registerUserModal').modal('show');
    $('#registerSchoolModal').modal('hide');
    $('#loginModal').modal('hide');
  }

  if($("#loginModal").data("errors") == true){
    $('#registerUserModal').modal('hide');
    $('#registerSchoolModal').modal('hide');
    $('#loginModal').modal('show');
  }

  if( $('.form-repeat-container').length != 0){
    // If checkbox is already checked, show repeat-container on page load
    if($('#repeat').prop('checked')) {
      $('.form-repeat-container').show();
    };
    $('input#repeat').click(function(){
      $('.form-repeat-container').slideToggle();
    });

  }

  $('.activateUser').on('click', function(ev){
    ev.preventDefault;
    var that = $(this);
    var userid = that.data('userid');
    $.ajax({
      type:"GET",
      url: "user/activate/"+userid,
      cache: false,
      dataType: "json",
      contentType: "application/json",
      beforeSend:function(){
        that.parent().find('.loader').show();
        that.prop('disabled', true);
      },
      success:function(data){
        that.parent().find('.loader').hide();
        that.prop('disabled', false);
      },
      error:function(xhr, status, errorThrown) {
        console.log(status + ', ' + errorThrown);
        that.prop('disabled', false);
      }
    });
  });

  $('.loader').hide();

  $('#repeat_type').change(function(ev){
    calculateRepeats();
  });

  $('#repeat_freq').change(function(ev){
    calculateRepeats();
  });


  $('#datetimepicker1').change(function(ev){
    calculateRepeats();
  });

  $('.linkToPdf').on('click', function(){
    var that = $(this);
    that.siblings('.linkToText').val(that.data('link')).select();
  });

  $('.linkToIcal').on('click', function(){
    var that = $(this);
    that.siblings('.linkToText').val(that.data('link')).select();
  });

  $('.linkToText').on('click', function(){
    $(this).select();
  });

});

jQuery(function(){
  if ($('#datetimepicker1').length != 0){
    jQuery('#datetimepicker1').datetimepicker({
      format:'Y/m/d H:i',
      mask:true,
      onShow:function( ct ){
        if(jQuery('#datetimepicker2').val() != '' && jQuery('#datetimepicker2').val() != '____/__/__ __:__'){
          this.setOptions({
            maxDate:moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD'),
            maxTime:moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('HH:mm')
          })
        }else{
          this.setOptions({
            maxDate:false,
            maxTime:false
          })
        }
      },
      onSelectDate:function(){
        if(jQuery('#datetimepicker2').val() != ''){
          if(moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD') == moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD')){
            this.setOptions({
              maxTime:moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('HH:mm')
            })
          }else{
            this.setOptions({
              maxTime:false
            })
          }
        }else{
          this.setOptions({
            maxTime:false
          })
        }
      },
      timepicker:true,
      defaultSelect:true
    });
    jQuery('#datetimepicker2').datetimepicker({
      format:'Y/m/d H:i',
      mask:true,
      onShow:function( ct ){
        if(jQuery('#datetimepicker1').val() != ''){
          this.setOptions({
            minDate:moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD'),
            minTime:moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('HH:mm')
          })
        }else{
          this.setOptions({
            minDate:false,
            minTime:false
          })
        }

      },
      onSelectDate:function(){
        if(jQuery('#datetimepicker2').val() != ''){
          if(moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD') == moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD')){
            this.setOptions({
              minTime:moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('HH:mm')
            })
          }else{
            this.setOptions({
              minTime:false
            })
          }
        }else{
          this.setOptions({
            minTime:false
          })
        }
      },
      timepicker:true,
      defaultSelect:true
    });

    // Edit event form: get value for dateTimePicker3 when applicable (recurring events)
    var calcDate = null;
    if($('#repeat').prop('checked')) {
      var type = $('#repeat_type').val();
      var startDate = moment($('#datetimepicker1').val(),'YYYY/MM/DD HH:mm');
      var freq = $('#repeat_freq').val();

      for(i=1;i<$('#nr_repeat').val();i++) {
        startDate = startDate.add(type,freq);
      }
      calcDate = moment(startDate,'YYYY/MM/DD').format('YYYY/MM/DD');
    }

    jQuery('#datetimepicker3').datetimepicker({
      format:'Y/m/d',
      mask:true,
      onShow:function( ct ){
        if(jQuery('#datetimepicker2').val() != '' && jQuery('#datetimepicker2').val() != '____/__/__ __:__'){
          this.setOptions({
            minDate:moment(jQuery('#datetimepicker2').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD')
          })
        } else {
          if(jQuery('#datetimepicker1').val() != '' && jQuery('#datetimepicker1').val() != '____/__/__ __:__') {
            this.setOptions({
              minDate:moment(jQuery('#datetimepicker1').val(), "YYYY/MM/DD HH:mm").format('YYYY/MM/DD')
            })
          } else {
            this.setOptions({
              minDate:false
            });
          }
        }
      },
      onSelectDate:function(){
        // calculate stuff
        calculateRepeats();
      },
      timepicker:false,
      defaultSelect:true,
      value:calcDate,
      format:'Y/m/d'
    });
  }
  // When the "register as school" button is clicked, hide the "register as user" modal
  $('#showSchoolRegisterModal').click(function(){
    $('#registerUserModal').modal('hide');
    $('#registerSchoolModal').modal('show');
  });
});

/**
 * Calculates the amount of times an event
 */
function calculateRepeats(){
  var freq = $('#repeat_freq').val();
  var endDate = moment($('#datetimepicker3').val(), 'YYYY/MM/DD');

  if(freq>0 && endDate.isValid() && $('#repeat').prop('checked')) {
    var type = $('#repeat_type').val();
    var startDate = $('#datetimepicker1').val();
    startDate = moment(startDate, 'YYYY/MM/DD');

    if(startDate.isValid() && startDate.isBefore(endDate) && type) {
      $('#nr_repeat').removeAttr('value');
      var i = 1;
      while(startDate.isBefore(moment(endDate))) {
        startDate = startDate.add(type,freq);
        i++;
      }
      $('#nr_repeat').val(i);
    } else {
      $('#nr_repeat').removeAttr('value');
    }

  }
}