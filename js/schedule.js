


$(document).ready(function() {

  $( "#dateSchedule" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });


  $("#save").css('cursor', 'pointer');
  $("#save").click(function(e) {
    //on recupere (en serialize) la liste des eprouvettes, leur nom et les splits associés
    var formScheduleInitial = $.param($('th').map(function() {
      if ($(this).attr('data-id')){
        if ($(this).attr('data-value')!= $(this).attr('data-oldvalue')) {
          return {
            name: $(this).attr('data-io'),
            value: $(this).attr('data-value')
          };
        }
      }
    }));
    var formScheduleSplit = $.param($('th').map(function() {
      if ($(this).attr('data-idjob')){
if ($(this).attr('data-value')!= $(this).attr('data-oldvalue')) {
          return {
            name: $(this).attr('data-idJob') + '-' + $(this).attr('data-io'),
            value: $(this).attr('data-value')
          };
        }
      }
    }));

    e.preventDefault();

    $.ajax({
      type: "POST",
      url: 'controller/updateSchedule.php',
      dataType: "json",
      data: {
        formScheduleInitial : formScheduleInitial,
        formScheduleSplit : formScheduleSplit,
        schedule_commentaire : $('#schedule_commentaire').val(),
        schedule_recommendation : $('#schedule_recommendation').val(),
        dateschedule: $('#dateschedule').val(),
        id_info_job: $('#id_info_job').val()
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


//Un click sur recommendation affiche le div ou le textarea
$("#flipRecommendation").click(function() {
  $('#schedule_recommendation_alt').toggleClass('flip');
  $('#schedule_recommendation').toggleClass('flip');

  $('#schedule_recommendation_alt').html($('#schedule_recommendation').val());
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
    $('#table_ep').DataTable().draw();
  });
