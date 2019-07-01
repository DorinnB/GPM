$(document).ajaxStart(function() {
    $(document.body).css({'cursor' : 'wait'});
}).ajaxStop(function() {
    $(document.body).css({'cursor' : 'auto'});
});

$(document).ready(function() {
  $("#planningTech").click(function(e) {
    $("#carre2").load('controller/planningTech-controller.php');
  });
});
