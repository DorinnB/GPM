<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/accounting-model.php';
// Création d'une instance
$oAccountings = new AccountingModel($db);

$lstJobs=$oAccountings->getAllAccounting();

//var_dump($lstJobs);

?>
