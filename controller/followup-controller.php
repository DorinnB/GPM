<?php

// Rendre votre modèle accessible
include_once 'models/lstJobs-model.php';

// Création d'une instance
$oFollowup = new LstJobsModel($db);


$filtreFollowup=(isset($_GET['filtreFollowup']))?$_GET['filtreFollowup']:'';
