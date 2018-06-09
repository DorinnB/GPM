// Recuperation des postes
function lstPoste(idEp) {
  $('#id_poste').load('controller/lstPoste-controller.php?id_Ep='+idEp);
}

// Check Eprouvette
$("#openPrestart").click(function() {
  document.getElementById("newTest").style.display = "none";
  document.getElementById("prestart").style.display = "block";
  document.getElementById("prestart").className += " active";
  document.getElementById("1c").className += " active";
  document.getElementById("1a").style.display = "none";
});

// Hide Consigne/job unchecked and onenote
$("#hideInfo").click(function() {
  $("#Comments").toggle();
  $("#notComments").toggle();
$("#hideInfo_icone").toggleClass("glyphicon-eye-close").toggleClass("glyphicon-eye-open");
});

$(document).ready(function(){

  $( "#eprouvette_InOut_A" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

  $( "#eprouvette_InOut_B" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });


//Si une valeur change, on modifie le bouton save en "new"
  $('.MaJ').change(function() {
    $("#checkAux").val("save");
    $("#d_checked").children('img').attr('src', 'img/nextJob.png');
    $("#d_checked").css('background-color','');
  });

  $('#newTestForm > div > select').each(function() {
    if ($(this).val() != '0') {
      $('#submit_newTest').removeAttr('disabled');    //si la machine est deja presente, on enleve le disabled
    }
  });

  $('#id_prestart').change(function() {
    if ($('#id_prestart').find(':selected').attr('data-customFrequency')==1) {
      document.getElementById("customFreq").style.display = "block";
    }
    else {
      document.getElementById("customFreq").style.display = "none";
    }
    if ($('#id_prestart').find(':selected').attr('data-postePrestart')!=$('#id_prestart').find(':selected').attr('data-posteActuel')) {
      document.getElementById("newTestMsg").style.display = "block";
      document.getElementById("newTestMsg").innerHTML="The Frame Management has been changed since the last Prestart.";
    }
    else {
      document.getElementById("newTestMsg").style.display = "none";
    }
  }).change();


  $('#newTestForm >  select').change(function() {
    var empty = false;
    $('#newTestForm >  select').each(function() {
      if ($(this).val() == '0') {
        empty = true;
      }
    });
    //si l'on a pas d'id_user, on n'autorise pas la validation du formulaire
    if ($('#id_userNewTest').val()=='0') {
      empty = true;
    }

    if (empty) {
      $('#submit_newTest').attr('disabled', 'disabled'); // updated according to http://stackoverflow.com/questions/7637790/how-to-remove-disabled-attribute-with-jquery-ie
    } else {
      $('#submit_newTest').removeAttr('disabled');
    }
  }).change();
});



//newTest
$("#newTestForm").submit(function(e) {
  $.ajax({
    type: "POST",
    url: "controller/newTest-controller.php",
    data: $("#newTestForm").serialize(), // serializes the form's elements.
    success: function(data)
    {
      location.assign("controller/createFT-controller.php?id_ep="+$("#idEp").val());
      $.ajax({
        type: "GET",
        url: "controller/createXML-controller.php",
        data: {id_ep : $("#idEp").val()}
      });
      $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());
    }
  });

  e.preventDefault(); // avoid to execute the actual submit of the form.

});

//Prestart
$("#prestartForm").submit(function(e) {
  $.ajax({
    type: "POST",
    url: "controller/updatePrestart.php",
    data: $("#prestartForm").serialize(), // serializes the form's elements.
    success: function(data)
    {
      //alert(data);
      location.assign("controller/createPrestart-controller.php?id_prestart="+data+"&id_ep="+$('#idEp').val());
      $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());

    }
  });

  e.preventDefault(); // avoid to execute the actual submit of the form.
});

//affichage frame management
$('#id_poste').change(function() {
  $('#showFrameManagement').attr("href", "index.php?page=gestionPoste&id_poste="+  $('#id_poste').find(':selected').val());

}).change();


//prepa
$("#newTestCheck").submit(function(e) {
  $.ajax({
    type: "POST",
    url: "controller/newTestCheck-controller.php",
    data: $("#newTestCheck").serialize(), // serializes the form's elements.
    success: function(data)
    {
      $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());
    }
  });

  e.preventDefault(); // avoid to execute the actual submit of the form.
});




function retest(id_ep, id_tbljob) {
  $.ajax({
    type: "POST",
    url: "controller/retest-controller.php",
    data: 'id_ep=' + id_ep,
    success: function(data)
    {
location.reload();
    }
  });
}

function delTest(id_ep, id_tbljob) {
  $.ajax({
    type: "POST",
    url: "controller/delTest-controller.php",
    data: 'id_ep=' + id_ep,
    success: function(data)
    {
location.reload();
    }
  });
}

function cancelTest(id_ep, id_tbljob) {
  $.ajax({
    type: "POST",
    url: "controller/cancelTest-controller.php",
    data: 'id_ep=' + id_ep,
    success: function(data)
    {
location.reload();
    }
  });
}




$("#check_rupture").click(function(e) {
  //si le user n'est pas celui qui a inseré le dernier rupture/fracture, il peut valider
  if (-$("#check_rupture").attr('data-check_rupture')!=iduser) {
    $.ajax({
      type: "POST",
      url: 'controller/updateCheckRupture.php',
      dataType: "json",
      data: $("#update_Rupture").serialize(),
      success : function(data, statut){
        $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la l\insertion des rupture/fracture ou du check. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez d\'inserer une non-valeur.');
      }
    });
  }
});

$("#d_checked").click(function(e) {
  //update que si c'est un save, ou bien un check d'un autre opérateur
  //  if ($("#checkAux").val()=='save' || ( $("#checkAux").val()=='check' && -$("#d_checked").attr('data-d_checked')!=iduser)) {

  confirmation=true;
  if ($("#checkAux").val()=='cancel') {
    var confirmation = confirm('Cancel this task ? All check will be canceled');
  }
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updateAux.php',
      dataType: "json",
      data: $("#update_Aux").serialize(),
      success : function(data, statut){
        $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la l\'insertion des valeurs ou du check. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez d\'inserer une non-valeur.');
      }
    });
  }






});


$("#flagQualite").click(function(e) {
  flagQualite($("#flagQualite"));
  $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$("#idEp").val());
});

$("#flecheUp").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextTest.php',
    dataType: "json",
    data:  {
      idEp:$("#idEp").val(),
      sens:'<',
      type:'machine'
    }
    ,
    success : function(data, statut){
      if (data['id_eprouvette']!== undefined) {
        $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+data['id_eprouvette']);
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche d\'essai. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});
$("#flecheDown").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextTest.php',
    dataType: "json",
    data:  {
      idEp:$("#idEp").val(),
      sens:'>',
      type:'machine'
    }
    ,
    success : function(data, statut){
      if (data['id_eprouvette']!== undefined) {
        $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+data['id_eprouvette']);
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche d\'essai. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});
$("#flecheUp2").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextTest.php',
    dataType: "json",
    data:  {
      idEp:$("#idEp").val(),
      sens:'<',
      type:'split'
    }
    ,
    success : function(data, statut){
      if (data['id_eprouvette']!== undefined) {
        $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+data['id_eprouvette']);
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche d\'essai. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});
$("#flecheDown2").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextTest.php',
    dataType: "json",
    data:  {
      idEp:$("#idEp").val(),
      sens:'>',
      type:'split'
    }
    ,
    success : function(data, statut){
      if (data['id_eprouvette']!== undefined) {
        $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+data['id_eprouvette']);
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche d\'essai. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});
$("#flecheUp3").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextTest.php',
    dataType: "json",
    data:  {
      idEp:$("#idEp").val(),
      sens:'<',
      type:'specimen'
    }
    ,
    success : function(data, statut){
      if (data['id_eprouvette']!== undefined) {
        $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+data['id_eprouvette']);
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche d\'essai. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});
$("#flecheDown3").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextTest.php',
    dataType: "json",
    data:  {
      idEp:$("#idEp").val(),
      sens:'>',
      type:'specimen'
    }
    ,
    success : function(data, statut){
      if (data['id_eprouvette']!== undefined) {
        $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+data['id_eprouvette']);
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche d\'essai. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});


$("#save_d_commentaire").click(function(e) {

  e.preventDefault();
  //$('#d_commentaire').val($('#d_commentaire_content').html());
  $.ajax({
    type: "POST",
    url: 'controller/updateDCcommentaire.php',
    dataType: "json",
    data: $("#update_d_commentaire").serialize() + "&iduser=" + iduser
    ,
    success : function(data, statut){
      //$('#gestionEp').modal('hide');
              $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification des commentaires labo. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});




$(".document").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/document-controller.php',
    data:
    {
      tbl:"eprouvettes",
      id_tbl:$("#idEp").val(),
      type:$(this).attr('data-type')
    }
    ,
    success : function(data, statut){
      $("#doc_ep").html(data);
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de l\'affichage des documents. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});


$("#createXML").click(function(e) {
  $.ajax({
    type: "GET",
    url: 'controller/createXML-controller.php',
    data:
    {
      id_ep:$("#idEp").val()
    }
    ,
    success : function(data, statut){
//      alert('ca marche');
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la création du XML. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});
