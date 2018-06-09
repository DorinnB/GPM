<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>

<h3>
	Processing...<br/>
	Make take up to few seconds</br>

</h3>

<div id="spoiler" style="display:none">



<?php


echo $_GET['id_info_job'];
$id_old_tbljob=$_GET['id_info_job'];


// Rendre votre modèle accessible
include '../models/copyJob-model.php';

// Création d'une instance
$oInfoJob = new InfoJob($db, $id_old_tbljob);

//copy de l'info job et récupération du nouvel id
$newInfoJob = $oInfoJob->copyInfoJob();
echo '<br/>new id_info_job : '.$newInfoJob;



//on parcourt les masters de l'infojob et pour chacun, on le copy en enregistrant dans un tableau l'ancien et le nouvel id equivalent
foreach ($oInfoJob->getMasterEprouvettes() as $masterEprouvette) {
  $idMasterEprouvette[$masterEprouvette['id_master_eprouvette']]=$oInfoJob->copyMasterEprouvette($masterEprouvette['id_master_eprouvette']);
}
var_dump($idMasterEprouvette);


//pour chaque tbljob (split)
foreach ($oInfoJob->getTbljobs() as $tbljob) {
  //on copy le split et on recupere l'id du nouveau
  $newIdTbljob = $oInfoJob->copyTbljobs($tbljob['id_tbljob']);
  //pour chaque eprouvette de l'ancien split
  foreach ($oInfoJob->getEprouvettes($tbljob['id_tbljob']) as $eprouvette) {
    //on copy l'eprouvette en changeant l'id du nouveau split et du nouveau masterEprouvette
    $oInfoJob->copyEprouvettes($newIdTbljob,$idMasterEprouvette[$eprouvette['id_master_eprouvette']] ,$eprouvette['id_eprouvette']);
  }
}



 ?>




</div>

<button title="Click to show/hide content" type="button" onclick="if(document.getElementById('spoiler') .style.display=='none') {document.getElementById('spoiler') .style.display=''}else{document.getElementById('spoiler') .style.display='none'}">Show/hide</button>
<br/><br/>
<a href="../index.php?page=updateJob&id_tbljob=<?= $newIdTbljob ?>">click here if you aren't redirected</a>
<br/><br/>
<script type='text/javascript'>document.location.replace('../index.php?page=updateJob&id_tbljob=<?= $newIdTbljob?>');</script>
