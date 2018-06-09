
//fonction d'ouverture d'un split
function gotoMARCHEPLUS(Page,type,id,modif) {
  $('#pageunique').load('controller/' + Page + '-controller.php?'+type+'='+id+'&modif='+modif);
  document.getElementById("carre").style.display = "none";
  document.getElementById("pageunique").style.display = "block";
}



//Un click sur une ligne du tableau des jobs ouvre le split correspondant
$("#table_id >tbody > tr").click(function(e) {
  goto('split','id_tbljob',$(this).data("id_tbljob"),'noModif');
});


function gestionEp(idEp) {
  $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+idEp);
}
