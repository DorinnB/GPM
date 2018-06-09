<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/split-model.php';

// Création d'une instance
$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);

if ($_POST['type']=="delete") {
  $oSplit->deleteTechSplit();
}
elseif ($_POST['type']=="create") {
  $oSplit->createTechSplit($_COOKIE['id_user']);
}


?>
