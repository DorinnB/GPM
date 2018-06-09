<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/qualite-model.php';
// Création d'une instance
$oQualite = new QualiteModel($db);

// Rendre votre modèle accessible
include_once 'models/inOut-model.php';
// Création d'une instance
$oInOut = new INOUT($db);



$uncheckedJob=$oQualite->getUncheckedJob();
$uncheckedStartedJob=$oQualite->getUncheckedStartedJob();
$flag=$oQualite->getFlagJob();
// Affichage du résultat
include 'views/qualiteList-view.php';

?>
