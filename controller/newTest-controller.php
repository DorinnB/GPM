<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEp']);



//creation du flag techSplit
$oEprouvette->createTechSplit($_POST['id_user']);

$oEprouvette->id_operateur=$_POST['id_user'];
$oEprouvette->id_prestart=$_POST['id_prestart'];
$oEprouvette->id_controleur=$_POST['checker'];
$oEprouvette->custom_frequency=$_POST['custom_frequency'];
//$oEprouvette->id_controleur=0;

$eprouvette=$oEprouvette->newTest();

include 'createTestList-controller.php';



//Update du statut du job
include '../models/statut-model.php';
$oStatut = new StatutModel($db);
$oStatut->id_tbljob=$oStatut->getJobFromEp($_POST['idEp'])['id_job'];
$state=$oStatut->findStatut();
?>
