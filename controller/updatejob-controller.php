<?php

// Rendre votre modèle accessible
include 'models/infojob-model.php';

// Création d'une instance
$oJob = new InfoJob($db,$_GET['id_tbljob']);
$job=$oJob->getInfoJob();



// Rendre votre modèle accessible
include 'models/lstMatiere-model.php';
$lstMatiere = new MatiereModel($db);
$matiere=$lstMatiere->getAllMatiere();


// Rendre votre modèle accessible
include 'models/lstContact-model.php';
$lstCustomer = new ContactModel($db);
$ref_customer=$lstCustomer->getAllref_customer();

// Rendre votre modèle accessible
include 'models/lstPricing-model.php';
$lstPricing = new PricingModel($db);
$pricing=$lstPricing->getAllPricing();
