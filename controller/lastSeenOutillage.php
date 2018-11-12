<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/lstOutillage-model.php';

// Création d'une instance
$lstOutillage = new OutillageModel($db);
$lastSeen=$lstOutillage->getLastSeen($_POST['id_outillage']);


echo json_encode($lastSeen);
