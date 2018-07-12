<?php

// Rendre votre modèle accessible
include_once 'models/qualite-model.php';

// Création d'une instance
$oQualite = new QualiteModel($db);

$_GET['startDate']=isset($_GET['$startDate'])?$_GET['$startDate']:date("Y-m-d", strtotime("-3 months"));
$_GET['endDate']=isset($_GET['endDate'])?$_GET['endDate']:date("Y-m-d");




 include('views/qualitePareto-view.php');
