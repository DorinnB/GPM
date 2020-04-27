<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/lab-model.php';
// Création d'une instance
$oTest = new LabModel($db);

$test=$oTest->getCheckList();
$checkRupture=$oTest->getCheckRuptureList();
$checkDataValue=$oTest->getCheckDataValueList();
$awaitingTechnician=$oTest->getAwaitingTechnicianList();

// Rendre votre modèle accessible
include_once 'models/lstPlanningUsers-model.php';
$oPlanningUsers = new PlanningUsersModel($db);
$nbModifPlanning=$oPlanningUsers->getAllManagedAwaiting();

// Affichage du résultat
include 'views/checkList-view.php';
?>
