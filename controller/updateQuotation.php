<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php


//var_dump($_POST);




// Rendre votre modèle accessible
include '../models/quotation-model.php';
$oQuotations = new QUOTATION($db);


$oQuotations->id=($_POST['id']==0)?"":$_POST['id'];
$oQuotations->titre=$_POST['titre'];
$oQuotations->RFQ=$_POST['RFQ'];
$oQuotations->ver=$_POST['ver'];
$oQuotations->ref_customer=$_POST['ref_customer'];
$oQuotations->id_contact=$_POST['id_contact'];
$oQuotations->id_preparer=$_POST['id_preparer'];
$oQuotations->id_checker=$_POST['id_checker'];
$oQuotations->date=$_POST['date'];
$oQuotations->lang=isset($_POST['lang'])?"1":"0";
$oQuotations->currency=isset($_POST['currency'])?"1":"0";

$oQuotations->quotationlist=$_POST['quotationlist'];
$oQuotations->endComments=$_POST['endComments'];

$quotation=$oQuotations->updateQuotation();

?>
