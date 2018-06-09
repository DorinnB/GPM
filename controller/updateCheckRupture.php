<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEp']);

if($_POST['iduser']!=0){

  $oEprouvette->iduser=$_POST['iduser'];

  if ($_POST['typeRupture']=="save") {
    if (isset($_POST['Rupture']) AND $_POST['Rupture']!="") {
      $oEprouvette->Rupture=$_POST['Rupture'];
      $oEprouvette->update_Rupture();
    }
    if (isset($_POST['Fracture']) AND $_POST['Fracture']!="") {
      $oEprouvette->Fracture=$_POST['Fracture'];
      $oEprouvette->update_Fracture();
    }
      $maReponse = array('id_eprouvette' => $_POST['idEp']);
      echo json_encode($maReponse);

  }
  else {
    $oEprouvette->updateCheckRupture($_POST['iduser']);
  }


}

?>
