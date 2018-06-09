$(document).ready(function()
{
  // If the menu element is clicked
  $(".ulicone-menu li").click(function(e){

    $.ajax({
      type: "POST",
      url: 'controller/updateicone.php',
      dataType: "json",
      data: {
        id_machine : $(this).attr("data-id_machine"),
        id_icone: $(this).attr("data-id_icone"),
        type : "icone"
      },
      success : function(data, statut){
        $("#icone_" + data['id_machine']).attr('src',"img/" + data['icone_file']);
      },
      error : function(resultat, statut, erreur) {
        //console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification du l\'icone. Veuillez prevenir au plus vite le responsable SI.');
      }
    });

    // Hide it AFTER the action was triggered
    $(".icone-menu").hide(100);
  });

  // If the menu element is clicked
  $(".ulpriorite-menu li").click(function(e){

      $.ajax({
    		type: "POST",
    		url: 'controller/updateicone.php',
    		dataType: "json",
        data: {
          id_machine : $(this).attr("data-id_machine"),
          id_icone: $(this).attr("data-id_icone"),
          type : "priorite"
        },
        success : function(data, statut){
          $("#priorite_" + data['id_machine']).attr('src',"img/medal_" + data['id_icone']);
        },
        error : function(resultat, statut, erreur) {
          //console.log(Object.keys(resultat));
          alert('ERREUR lors de la modification du l\'icone. Veuillez prevenir au plus vite le responsable SI.');
        }
      });

    // Hide it AFTER the action was triggered
    $(".priorite-menu").hide(100);
  });

  
});
