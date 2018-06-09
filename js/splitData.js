$(document).ready(function() {
  //activation des tooltip
  $('[data-toggle="tooltip"]').tooltip();
});


function save() {
    $.ajax({
      type: "POST",
      url: 'controller/updateData.php',
      dataType: "json",
      data:  $("#updateData").serialize()
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


  $("#subCRef").click(function(e) {
    $('#refSubC').toggleClass('flip');
    $('#refSubC_alt').toggleClass('flip');

    $('#refSubC_alt').html($('#refSubC').val());
  });

$( function() {
  $( "#DyT_SubC" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });
  $( "#DyT_expected" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });
} );
