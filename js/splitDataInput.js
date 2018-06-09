
$(function() {
  //fonction permettant la selection des instructions particulieres
  $(document).on('change', '#special_instruction_file', function() {

    var names = [];
    for (var i = 0; i < $(this).get(0).files.length; ++i) {
      names.push($(this).get(0).files[i].name);
    }
    $("#special_instruction").val(names);
  });


  // Referneces
  var control = $("#special_instruction"),
      clearBn = $("#special_instruction_clear");

  // Setup the clear functionality
  clearBn.on("click", function(){
      control.replaceWith( control.val('').clone( true ) );
  });
    // Some bound handlers to preserve when cloning
  control.on({
      change: function(){ console.log( "Changed" ) },
       focus: function(){ console.log(  "Focus"  ) }
  });





});


function save() {

  $.ajax({
    type: "POST",
    url: 'controller/updateDataInput.php',
    dataType: "json",
    data:  $("#updateData").serialize()
    ,
    success : function(data, statut){
      location.assign("index.php?page=split&id_tbljob="+$("#id_tbljob").val());
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification des données du split. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
}


$( function() {

  $( "#DyT_Cust" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

} );
