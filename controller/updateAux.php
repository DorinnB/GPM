<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEprouvette']);


if($_POST['iduser']!=0){

  $oEprouvette->iduser=$_POST['iduser'];

  //On supprime les POST n'etant pas des valeurs Aux
  $data = array_diff_key($_POST, [
    'checkAux' => "***",
    'idEprouvette' => "***",
    'iduser' => "***"]
  );




  if ($_POST['checkAux']=="save") {
    //met check_rupture et d_checked à -user --- ajoute tous les champs de l'opération
    $oEprouvette->updateAux($data);
    $oEprouvette->createTechSplit($_POST['iduser']);
  }
  elseif ($_POST['checkAux']=="valid") {
    //met check_rupture et d_checked à +user
    $oEprouvette->updateAuxValid();
    $oEprouvette->createTechSplit($_POST['iduser']);
  }
  elseif ($_POST['checkAux']=="cancel") {
    //met check_rupture et d_checked à -user
    $data=array();
    $oEprouvette->updateAux($data);
  }


}

?>
