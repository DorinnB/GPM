<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/statut-model.php';

$oStatut = new StatutModel($db);
$oStatut->id_tbljob=$_POST['id_tbljob'];
$state=$oStatut->findStatut();

exit();









?>
