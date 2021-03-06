<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

if (isset($_POST['idtbljob'])) {

  // Rendre votre modèle accessible
  include '../models/split-model.php';
  $oSplit = new LstSplitModel($db,$_POST['idtbljob']);

  if ($_POST['role']=="revAdd") {
    $oSplit->updateRev();
  }
  elseif ($_POST['role']=="revReset") {
    $oSplit->resetRev();
  }
  elseif ($_POST['role']=="Q") {
    $oSplit->updateCheckQ();
  }
  elseif ($_POST['role']=="TM") {
    $oSplit->updateCheckTM();
  }
  elseif ($_POST['role']=="reportDateSet") {
    $oSplit->updateReportDate($_POST['report_date']);
  }
  elseif ($_POST['role']=="reportDateReset") {
    $oSplit->resetReportDate();
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
}
elseif (isset($_POST['idJob'])) {


  // Rendre votre modèle accessible
  include '../models/infojob-model.php';
  $oInfoJob = new InfoJob($db,$_POST['idJob']);

  if ($_POST['role']=="revAdd") {
    $oInfoJob->updateRev();
  }
  elseif ($_POST['role']=="revReset") {
    $oInfoJob->resetRev();
  }
  elseif ($_POST['role']=="reportDateSet") {
    $oInfoJob->updateReportDate($_POST['report_date']);
  }
  elseif ($_POST['role']=="reportDateReset") {
    $oInfoJob->resetReportDate();
  }
  elseif ($_POST['role']=="Q") {
    $oInfoJob->updateCheckQ();
  }
  elseif ($_POST['role']=="TM") {
    $oInfoJob->updateCheckTM();
  }


  //Update du statut des splits
  include '../models/statut-model.php';
  $oStatut = new StatutModel($db);
  foreach ($oStatut->getJobFromidTblJob($_POST['idJob']) as $key => $value) {
  	$oStatut->id_tbljob=$value['id_tbljob'];
  	$state=$oStatut->findStatut();
  }
}

?>
