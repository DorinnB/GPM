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
  }).blur(function() {
      console.log('out');


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
