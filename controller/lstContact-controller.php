<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/lstContact-model.php';

// Création d'une instance
$lstContact = new ContactModel($db);
$ref_customer=$lstContact->getContact($_GET['ref_customer']);

// Affichage du résultat
include '../views/lstContact-view.php';
