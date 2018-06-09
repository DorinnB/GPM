<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEp']);

if(isset($_COOKIE['id_user'])){
  if ($_POST['flagQualite']<1) {
    $oEprouvette->addFlagQualite();
  }
  else {
    $oEprouvette->removeFlagQualite();
  }
}

?>
