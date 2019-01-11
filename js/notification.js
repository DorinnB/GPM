$(document).ready(function(){

  $(".reply").click(function(e) {
    $('.nav-pills a[href="#new"]').tab('show'); //affichage "new"
    $('#subject').val( 'TR :'+ $('#received_' + $(this).attr('data-id_notification')).find('.subject').text()); //modification subject
    $('#text').text('----------\n' + $('#received_' + $(this).attr('data-id_notification')).find('.text').text() + '----------\n'); //modification textarea

    $('#id_receiver_user').val($('#received_' + $(this).attr('data-id_notification')).find('.transmitter').attr('data-id_transmitter')); // Select the option with a value of '1'
    $('#id_receiver_user').trigger('change'); // Notify any JS components that the value changed
  });

  $(".notificate").click(function(e) {
    $.ajax({
      type: "POST",
      url: "controller/updateNotification.php",
      data:  {
        id_notification : $(this).attr('data-id_notification'),
        type:'notificate'
      },
      success: function(data)
      {
        $('#notification').load('controller/notification-controller.php');
      }
    });
  });

  $(".deleteNotification").click(function(e) {
    $.ajax({
      type: "POST",
      url: "controller/updateNotification.php",
      data:  {
        id_notification : $(this).attr('data-id_notification'),
        type:'delete'
      },
      success: function(data)
      {
        $('#notification').load('controller/notification-controller.php');
      }
    });
  });

  $(".sendNotification").click(function(e) {
    $.ajax({
      type: "POST",
      url: "controller/updateNotification.php",
      data: $("#newNotification").serialize(), // serializes the form's elements.
      success: function(data)
      {
        $('#notification').load('controller/notification-controller.php');
      }
    });
  });


  $("#id_receiver_frame").select2({ width: "100%" });
  $("#id_receiver_user").select2({ width: "100%" });

  $('#receiver_type').change(function() {
    $('.receiver_frame').toggle() ;
    $('.receiver_user').toggle() ;
  });
});
