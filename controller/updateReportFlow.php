<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/split-model.php';

// Création d'une instance
$oSplit = new LstSplitModel($db,$_POST['idtbljob']);

if ($_POST['role']=="rev") {
  $oSplit->updateRev();
}
elseif ($_POST['role']=="Q") {
  $oSplit->updateCheckQ();
}
elseif ($_POST['role']=="TM") {
  $oSplit->updateCheckTM();
}
elseif ($_POST['role']=="RawData") {
  $oSplit->updateRawData();
}
elseif ($_POST['role']=="invoice") {
  $oSplit->invoice_type=isset($_POST['invoice_type'])?$_POST['invoice_type']:"";
  $oSplit->invoice_date=isset($_POST['invoice_date'])?$_POST['invoice_date']:"";
  $oSplit->invoice_commentaire=isset($_POST['invoice_commentaire'])?$_POST['invoice_commentaire']:"";
  $oSplit->updateInvoice();
}


//Update du statut du job
include '../models/statut-model.php';
$oStatut = new StatutModel($db);
$oStatut->id_tbljob=$_POST['idtbljob'];
$state=$oStatut->findStatut();
?>
