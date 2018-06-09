<?php

// Rendre votre modèle accessible
include 'models/lstPricing-model.php';

// Création d'une instance
$lstMatiere = new PricingModel($db);
$matiere=$lstMatiere->getAllPricing();

// Affichage du résultat
include 'views/lstPricing-view.php';
