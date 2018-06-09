<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEp']);


$oEprouvette->id_controleur=$_POST['checker'];

//creation du flag techSplit
$oEprouvette->createTechSplit($_POST['checker']);

$eprouvette=$oEprouvette->newTestCheck();
?>
