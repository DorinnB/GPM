<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/lstNotification-model.php';
$oNotification = new NotificationModel($db);

include '../models/poste-model.php';
$oPoste = new PosteModel($db,0);
$lstFrames=$oPoste->getAllMachine();

include '../models/lstTech-model.php';
$oUser = new TechModel($db);
$lstUsers=$oUser->getAllTech();


include '../models/badge-model.php';
$oBadge = new BadgeModel($db);
$isBadge=$oBadge->isBadge();


include('../views/notification-view.php');

?>
