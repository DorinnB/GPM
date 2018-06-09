<?php

// Rendre votre modèle accessible
include 'models/lstJobs-model.php';

// Création d'une instance
$lstJobs = new LstJobsModel($db);

// Affichage du résultat
include 'views/tblJobs-view.php';
