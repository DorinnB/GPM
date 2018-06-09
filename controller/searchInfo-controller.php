<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/lstJobs-model.php';
// Création d'une instance
$oJobs = new LstJobsModel($db);
$searchJobs=$oJobs->searchJob($_GET['searchInfo']);
$searchSpecification=$oJobs->searchSpecification($_GET['searchInfo']);
$searchPO=$oJobs->searchPO($_GET['searchInfo']);
$searchInst=$oJobs->searchInst($_GET['searchInfo']);
$searchEprouvettes=$oJobs->searchEp($_GET['searchInfo']);
$searchPrefixe=$oJobs->searchPrefixe($_GET['searchInfo']);
$searchFile=$oJobs->searchFile($_GET['searchInfo']);
?>
