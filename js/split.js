$("#deleteTechSplit").click(function(e) {
  var confirmation = confirm('Force a re-check ?');
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updateTechSplit.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#table_ep').attr('data-idJob'),
        type : 'delete'
      }
      ,
      success : function(data, statut){
        //location.reload();
        $("#deleteTechSplit").html('Done');
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l insertion au planning. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }
});

$("#check.check0").click(function(e) {
  var confirmation = confirm('Check this item ?');
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updateCheck.php',
      dataType: "json",
      data:  {
        id_tbljob:$('#table_ep').attr('data-idJob')
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification des données du split. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }
});

$("#planning").click(function(e) {
  var confirmation = confirm('Update Planning Insertion?');
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updatePlanning.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#table_ep').attr('data-idJob'),
        id_planning : $('#planning').attr('data-planning')
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l insertion au planning. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }
});


$("#email").click(function(e) {

  $.ajax({
    type: "POST",
    url: 'controller/updateStatut.php',
    dataType: "json",
    data:  {
      id_tbljob : $('#table_ep').attr('data-idJob'),
      id_statut : "10"
    }
    ,
    success : function(data, statut){
      location.reload();
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      location.reload();  //updateStatut ne renvoi rien, donc sort en "erreur" ici
      //alert('ERREUR lors de l insertion au planning. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });

});





function createReport(type='Annexe',language='FR',specific='Std') {
  window.location='controller/createReport-controller.php?id_tbljob='+$('#table_ep').attr('data-idJob')+'&type='+type+'&language='+language+'&specific='+specific;
}


$("#report").click(function(e) {
  //alert('merci de faire un click droit');
  createReport('Short','FR','Std');
});

//menu contextuel pour la langue du rapport
$("#report").contextmenu(function (event) {

    // Avoid the real one
    event.preventDefault();

    // Show contextmenu
    $("#report-contextual-menu").finish().toggle(100).

    // In the right position (the mouse)
    css({
        bottom: " 0px",
        right: "150px"
    });
        $('#report-contextual-menu').load('controller/report-icone-controller.php?id_tbljob='+$('#id_tbljob').val());
});

$("#flecheUpJob").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextJob.php',
    dataType: "json",
    data:  {
      id_tbljob:$("#id_tbljob").val(),
      sens:'>'
    }
    ,
    success : function(data, statut){
      if (data['id_tbljob']!== undefined) {
        window.location.href = 'index.php?page=split&id_tbljob='+data['id_tbljob'];
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche de Job. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});
$("#flecheDownJob").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextJob.php',
    dataType: "json",
    data:  {
      id_tbljob:$("#id_tbljob").val(),
      sens:'<'
    }
    ,
    success : function(data, statut){
      if (data['id_tbljob']!== undefined) {
        window.location.href = 'index.php?page=split&id_tbljob='+data['id_tbljob'];
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche de Job. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});


$("#save").click(function() {
  save();
});


$(".openDocument").click(function(e) {
// on ouvre dans une fenêtre le fichier passé en paramètre.
window.open("controller/openDocument-controller?file_type="+$(this).attr('data-type')+"&file_name="+$(this).attr('data-file'),'Document','width=670,height=930,top=50,left=50');
});


//Selon le navigateur utilisé, on detecte le style de transition utilisé
function whichTransitionEvent(){
  var t,
  el = document.createElement("fakeelement");

  var transitions = {
    "transition"      : "transitionend",
    "OTransition"     : "oTransitionEnd",
    "MozTransition"   : "transitionend",
    "WebkitTransition": "webkitTransitionEnd"
  }

  for (t in transitions){
    if (el.style[t] !== undefined){
      return transitions[t];
    }
  }
}

var transitionEvent = whichTransitionEvent();

//On retracte le tbl des jobs, et une fois retracté, on redessine le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    //$('#table_ep').DataTable().draw();
  });
