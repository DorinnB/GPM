<?php

if (isset($_GET['id_infojob'])) {
  // Rendre votre modèle accessible
  include 'models/lstJobs-model.php';
  // Création d'une instance
  $oJob = new LstJobsModel($db);
  $split=$oJob->getFirstSplitIdJob($_GET['id_infojob']);
  echo "<script type='text/javascript'>document.location.replace('index.php?page=invoiceJob&id_tbljob=".$split['id_tbljob']."');</script>";
}
elseif (isset($_GET['job'])) {
  // Rendre votre modèle accessible
  include 'models/lstJobs-model.php';
  // Création d'une instance
  $oJob = new LstJobsModel($db);
  $split=$oJob->getFirstSplitJob($_GET['job']);
  echo "<script type='text/javascript'>document.location.replace('index.php?page=invoiceJob&id_tbljob=".$split['id_tbljob']."');</script>";
}
elseif (!isset($_GET['id_tbljob'])) {
  exit;
}

// Rendre votre modèle accessible
include 'models/split-model.php';
// Création d'une instance
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);
$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include 'models/workflow.class.php';
// Création d'une instance
$oWorkflow = new WORKFLOW($db,$_GET['id_tbljob']);
$splits=$oWorkflow->getAllSplit();


// Rendre votre modèle accessible
include 'models/invoice-model.php';
// Création d'une instance
$oInvoices = new InvoiceModel($db);



//adresse
$i=0;
if (isset($split['entreprise'])) {
  $adresse[$i]='entreprise';
  $i++;
}
if (isset($split['billing_rue1'])) {
  $adresse[$i]='billing_rue1';
  $i++;
}
if (isset($split['billing_rue2'])) {
  $adresse[$i]='billing_rue2';
  $i++;
}
if (isset($split['billing_ville'])) {
  $adresse[$i]='billing_ville';
  $i++;
}
if (isset($split['billing_pays'])) {
  $adresse[$i]='billing_pays';
  $i++;
}

//var_dump($split);

//var_dump($splits);
$sumInvoicesMRSAS=0;
$sumInvoicesSubC=0;
foreach ($oInvoices->getAllInvoiceRecorded($split['id_tbljob']) as $inv) {
  $sumInvoicesMRSAS+=$inv['inv_mrsas'];
  $sumInvoicesSubC+=$inv['inv_subc'];
}
$sumPayables=0;
foreach ($oInvoices->getAllPayablesJob($split['id_tbljob']) as $payable) {
  $sumPayables+=round((($payable['USD']>0)?$payable['USD']*$payable['taux']:$payable['HT']),2);
}


// Affichage du résultat
include 'views/invoiceJob-view.php';
