<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/lab-model.php';
// Création d'une instance
$oTest = new LabModel($db);

$test=$oTest->getCheckList();
// Affichage du résultat
include 'views/checkList-view.php';


$checkRupture=$oTest->getCheckRuptureList();
// Affichage du résultat
include 'views/checkRuptureList-view.php';

$checkDataValue=$oTest->getCheckDataValueList();
// Affichage du résultat
include 'views/checkDataValueList-view.php';
?>
