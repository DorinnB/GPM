<?php

// Rendre votre modèle accessible
include 'models/lstTech-model.php';

// Création d'une instance
$lstTech = new TechModel($db);

// Affichage du résultat
include 'views/lstTech-view.php';
