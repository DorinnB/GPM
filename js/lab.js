


$(document).ready(function() {


    $('.toForCast').on("click", function(){
      $(this).closest('.lab').find('.foreCastView').first().css('display','block');
      $(this).closest('.lab').find('.machineView').first().css('display','none');
    });


    $( ".toMachine" ).on("click", function(){
      $(this).closest('.lab').find('.foreCastView').first().css('display','none');
      $(this).closest('.lab').find('.machineView').first().css('display','block');
    });








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
