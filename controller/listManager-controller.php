<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/inOut-model.php';
$oInOut = new INOUT($db);

// Rendre votre modèle accessible
include_once 'models/qualite-model.php';
$oQualite = new QualiteModel($db);


$uncheckedJob=$oQualite->getUncheckedJob();
$uncheckedStartedJob=$oQualite->getUncheckedStartedJob();
$flag=$oQualite->getFlagJob();


// Rendre votre modèle accessible
include_once 'models/lstPlanningUsers-model.php';
$oPlanningUsers = new PlanningUsersModel($db);
$nbModifPlanning=$oPlanningUsers->getAllManagedAwaiting();

// Rendre votre modèle accessible
include_once 'models/badge-model.php';
$oBadges = new BadgeModel($db);
$nbBadgeAwaiting=$oBadges->getAllManagedAwaiting();

// Affichage du résultat
include 'views/listManager-view.php';

?>
