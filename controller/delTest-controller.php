<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

$oEprouvette = new EprouvetteModel($db,$_POST['id_ep']);

//cette methode lit les données de l'eprouvette actuelle puis recopie certaines valeurs dans une nouvelle eprouvette
$oEprouvette->delEp();

?>
