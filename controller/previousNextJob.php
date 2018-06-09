<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/infojob-model.php';

// Création d'une instance
$oInfoJob = new InfoJob($db,$_POST['id_tbljob']);




$oInfoJob->previousNextJob($_POST['sens']);


?>
