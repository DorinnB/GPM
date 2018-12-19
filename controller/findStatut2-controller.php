<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/statut-model.php';

$oSplit = new StatutModel($db);
$oSplit->id_tbljob=$_POST['id_tbljob'];
$state=$oSplit->findStatut2();

exit();









?>
