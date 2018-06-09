$("#wrapper").addClass("toggled");


//Un click sur une ligne du tableau des jobs ouvre le split correspondant
$("#table_id >tbody > tr").click(function(e) {
  window.location.href = 'index.php?page=split&id_tbljob='+$(this).data("id_tbljob");
});


$(document).ready(function() {

  //pour chaque machine, si on click sur le forecast, on affiche l'etat ctuel
  $( ".foreCast" ).each(function(index) {
    $(this).children('.nMachine').on("click", function(){
      $(this).parent('.foreCast').css('display','none');
      $(this).parent('.foreCast').closest('.lab').children('.machine').css('display','block')
    });
  });
  //pour chaque machine, si on click sur la machine, on affiche le forecast
  $( ".machine" ).each(function(index) {
    $(this).on("click", function(evt){
      //For descendants of machineNoClick being clicked, remove this check if you do not want to put constraint on descendants.
      if($(evt.target).closest('.machineNoClick').length)
         return;


      $(this).css('display','none');
      $(this).closest('.lab').children('.foreCast').css('display','block')
    });
  });
});
