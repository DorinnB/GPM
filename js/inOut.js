$( function() {

  $( "#dateInOut" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

} );


//Un click sur recommendation affiche le div ou le textarea
$("#flipRecommendation").click(function() {
  $('#inOut_recommendation_alt').toggleClass('flip');
  $('#inOut_recommendation').toggleClass('flip');

  $('#inOut_recommendation_alt').html($('#inOut_recommendation').val());
});


$(document).ready(function() {
  $("#save").css('cursor', 'pointer');
  $("#save").click(function(e) {
    //on recupere (en serialize) la liste des eprouvettes, leur nom et les splits associés
    var formInOutMaster = $.param($('td').map(function() {
      if ($(this).attr('data-idmaster')){
        if ($(this).css("background-color")=="rgb(128, 0, 128)"){
          return {
            name: $(this).attr('data-io'),
            value: $(this).attr('data-idmaster') + '_' + $(this).html()
          };
        }
      }
    }));
    var formInOutEp = $.param($('td').map(function() {
      if ($(this).attr('data-id')){
        if ($(this).css("background-color")== "rgb(128, 0, 128)"){
          return {
            name: $(this).attr('data-io'),
            value: $(this).attr('data-id') + '_' + $(this).html()
          };
        }
      }
    }));

    e.preventDefault();

    $.ajax({
      type: "POST",
      url: 'controller/updateInOut.php',
      dataType: "json",
      data: {
        formInOutMaster : formInOutMaster,
        formInOutEp : formInOutEp,
        inOut_commentaire : $('#inOut_commentaire').val(),
        inOut_recommendation : $('#inOut_recommendation').val(),
        dateInOut: $('#dateInOut').val(),
        id_info_job: $('#id_info_job').val(),
        id_tbljob: $('#id_tbljob').val()
      },
      success : function(data, statut){
        //alert('yes');
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification des données du split. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  });
});


//hachurage des eprouvettes ayant la date de l'history en hover
$('.dateHighlight').hover(function(){
  val=$(this).attr('data-date');
  $('.selectable').each(function() {
      if ($(this).attr('data-oldvalue')==val) {
        $(this).toggleClass('highlight');
      }
 });
})


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
    $('#table_ep').DataTable().draw();
  });
