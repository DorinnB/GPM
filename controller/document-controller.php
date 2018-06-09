<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/document-model.php';

// Création d'une instance
$oDocument = new DocumentModel($db);
$documents=$oDocument->getAllDocuments($_POST['tbl'],$_POST['id_tbl'],$_POST['type']);





// Affichage du résultat
include '../views/document-view.php';
