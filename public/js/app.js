$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });

  if($("#registerSchoolModal").data("errors") == true){
    $('#registerUserModal').modal('hide');
    $('#registerSchoolModal').modal('show');
  }

  if($("#registerUserModal").data("errors") == true){
    $('#registerUserModal').modal('show');
    $('#registerSchoolModal').modal('hide');
  }
});

jQuery(function(){
  if ($('#datetimepicker1').length != 0){
    jQuery('#datetimepicker1').datetimepicker({
      format:'Y/m/d H:i',
      mask:true,
      onShow:function( ct ){
        if(jQuery('#datetimepicker2').val() != ''){
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
  }
  // When the "register as school" button is clicked, hide the "register as user" modal
  $('#showSchoolRegisterModal').click(function(){
    $('#registerUserModal').modal('hide');
    $('#registerSchoolModal').modal('show');
  });
});