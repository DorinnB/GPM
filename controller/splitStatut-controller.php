<?php

// Rendre votre modèle accessible
include 'models/lstStatut-model.php';

// Création d'une instance
$oStatut = new LstStatutModel($db,$_GET['id_tbljob']);
$statut=$oStatut->getStatut();

// Affichage du résultat
include 'views/splitStatut-view.php';
