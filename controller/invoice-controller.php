<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



    //UNUSED



// Rendre votre modèle accessible
include_once 'models/invoice-model.php';
// Création d'une instance
$oInvoices = new InvoiceModel($db);

$lstJobs=$oInvoices->getAllInvoice();

//var_dump($lstJobs);

?>
