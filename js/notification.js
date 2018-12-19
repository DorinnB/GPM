$(document).ready(function(){

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
