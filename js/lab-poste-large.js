myVar = setTimeout(function(){ document.location.reload(); }, 60000);


var $_GET = $_GET(),
view = $_GET['view'];


//vue differentes selons filtre
if (view=="test") {
  $('.machineView').each(function (index, value) {    $(this).css("display", "block");  });
  $('.foreCastView').each(function (index, value) {    $(this).css("display", "none");  });
  $('.calibrationView').each(function (index, value) {    $(this).css("display", "none");  });
}
else if (view=="comment") {
  $('.machineView').each(function (index, value) {    $(this).css("display", "none");  });
  $('.foreCastView').each(function (index, value) {    $(this).css("display", "block");  });
  $('.calibrationView').each(function (index, value) {    $(this).css("display", "none");  });
}
else if (view=="calibration") {
  $('.machineView').each(function (index, value) {    $(this).css("display", "none");  });
  $('.foreCastView').each(function (index, value) {    $(this).css("display", "none");  });
  $('.calibrationView').each(function (index, value) {    $(this).css("display", "block");  });
}



// Trigger action when the contexmenu is about to be shown
$(".icone").contextmenu(function (event) {

  // Avoid the real one
  event.preventDefault();

  // Show contextmenu
  $(".icone-menu").finish().toggle(100).
  // In the right position (the mouse)
  css({
    top: event.pageY + "px",
    left: event.pageX + "px"
  });
  $('.icone-menu').load('controller/lab-icone-controller.php?type=icone&id_machine='+$(this).attr("data-id"));
});

$(".priorite").contextmenu(function (event) {

  // Avoid the real one
  event.preventDefault();

  // Show contextmenu
  $(".priorite-menu").finish().toggle(100).
  // In the right position (the mouse)
  css({
    top: event.pageY + "px",
    left: event.pageX + "px"
  });
  $('.priorite-menu').load('controller/lab-icone-controller.php?type=priorite&id_machine='+$(this).attr("data-id"));
});

$(".commentaire").contextmenu(function (event) {

  // Avoid the real one
  event.preventDefault();
  // Show contextmenu
  $(".commentaire-menu").finish().toggle(100).
  // In the right position (the mouse)
  css({
    top: event.pageY + "px",
    left: event.pageX + "px"
  });
  $('.commentaire-menu').load('controller/lab-icone-controller.php?type=commentaire&id_machine='+$(this).attr("data-id")+'&commentaire='+$(this).html());
});





// If the menu element is clicked
$(".commentaire").focus(function() {
  console.log('in');
  clearTimeout(myVar);
  //window.stop();
  $(this).attr('rows', '4');
}).blur(function() {
  console.log('out');
  $(this).attr('rows', '1');

  $.ajax({
    type: "POST",
    url: 'controller/updateicone.php',
    dataType: "json",
    data: {
      id_machine : $(this).attr("data-id"),
      commentaire: $(this).val(),
      type : "commentaire"
    },
    success : function(data, statut){
      //$("#priorite_" + data['id_machine']).attr('src',"img/medal_" + data['id_icone']);
      window.location.reload();
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification du commentaire. Veuillez prevenir au plus vite le responsable SI.');
    }
  });

});











// If the document is clicked somewhere
$(document).bind("mousedown", function (e) {

  // If the clicked element is not the menu
  if (!$(e.target).parents(".custom-menu").length > 0) {

    // Hide it
    $(".custom-menu").hide(100);
  }
});
