<?php


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




// Affichage du résultat
include 'views/invoiceJob-view.php';
