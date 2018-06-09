<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



//var_dump($_POST);



// Rendre votre modèle accessible
include '../models/lstJobs-model.php';
$oJob = new LstJobsModel($db);

foreach ($_POST as $key => $value) {
  //on découpe le post pour récupérer le n° idtbljob
  $id=explode("_", $key)[1];
  $oJob->updateWeeklyReport($id, $value);
}


?>
