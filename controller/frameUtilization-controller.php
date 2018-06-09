<?php

// Rendre votre modèle accessible
include_once 'models/LstEtatMachines-model.php';

// Création d'une instance
$oEtatMachines = new LstEtatMachines($db);

$_GET['group']=isset($_GET['group'])?$_GET['group']:'Day';
$_GET['filtre']=isset($_GET['filtre'])?$_GET['filtre']:'Lab';




 include('views/frameUtilization-view.php');
