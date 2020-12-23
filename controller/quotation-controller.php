<?php
$ini = parse_ini_file('var/config.ini');

// Rendre votre modèle accessible
include 'models/infojob-model.php';

// Création d'une instance
$oJob = new InfoJob($db,0);
$job=$oJob->getInfoJob();


// Rendre votre modèle accessible
include 'models/quotation-model.php';
$oQuotations = new QUOTATION($db);
$quotation=$oQuotations->getQuotationList($_GET['id_quotation']);


// Rendre votre modèle accessible
include 'models/lstContact-model.php';
$lstCustomer = new ContactModel($db);
$ref_customer=$lstCustomer->getAllref_customer();




$quotationlistArray = array();
$quotationlist = array();

parse_str($quotation['quotationlist'], $quotationlistArray); // on récupère les quotationlist dans un array
if ($quotationlistArray) {
  foreach ($quotationlistArray as $key => $value) {            // on les range dans un sub array par ligne
    $name=explode("_", $key);
    $quotationlistNumber[$name[1]][$name[2]]=$value;
  }


  $index = "a";
  foreach($quotationlistNumber as $value)                     //on change l'index en lettre incrémental
  {
     $quotationlist[$index] = $value;
     $index++;
  }
}


// var_dump($quotationlist);






?>
