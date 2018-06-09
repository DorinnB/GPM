<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



//var_dump($_POST);
if (!isset($_POST['planningLab']) OR $_POST['planningLab']=='') {
  exit;
}


// Rendre votre modèle accessible
include '../models/planningLab-model.php';
$oPlanningLab = new PLANNINGLAB($db);

//on decoupe le POST en element jours
$jours=explode("&", $_POST['planningLab']);


foreach ($jours as $key => $value) {
  //on découpe les jours en date, machine, id_tbljob
  $date=explode("_", explode("=", $value)[0])[0];
  $id_machine=explode("_", explode("=", $value)[0])[1];
  $id_tbljob=explode("=", $value)[1];

  $oPlanningLab->date=$date;  //je ne comprend pas, la premiere date est entouré de ''
  $oPlanningLab->date=$date;  

  $oPlanningLab->id_machine=$id_machine;
  $oPlanningLab->id_tbljob=$id_tbljob;


  if ($id_tbljob=='') {
    $oPlanningLab->deletePlanningLab();
  }
  else {
    $oPlanningLab->updatePlanningLab();
  }

}


?>
