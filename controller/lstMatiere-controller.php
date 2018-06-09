<?php

// Rendre votre modèle accessible
include 'models/lstMatiere-model.php';

// Création d'une instance
$lstMatiere = new MatiereModel($db);
$matiere=$lstMatiere->getAllMatiere();

// Affichage du résultat
include 'views/lstMatiere-view.php';
