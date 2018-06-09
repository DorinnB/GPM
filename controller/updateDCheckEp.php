<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEp']);

if(isset($_POST['iduser']) AND $_POST['iduser']!=0){
  if ($_POST['dchecked']==0) {
    $oEprouvette->updateDCheck($_POST['iduser']);
  }
  else {
    $oEprouvette->updateRemoveDCheck($_POST['iduser']);
  }
}

//Update du statut du job
include '../models/statut-model.php';
$oStatut = new StatutModel($db);
$oStatut->id_tbljob=$oStatut->getJobFromEp($_POST['idEp'])['id_job'];
$state=$oStatut->findStatut();

?>
