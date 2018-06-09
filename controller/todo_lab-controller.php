<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/lab-model.php';
// Création d'une instance
$oTest = new LabModel($db);

$todoLab=$oTest->getTodoLab();

// Affichage du résultat
include 'views/todo_lab-view.php';
?>
