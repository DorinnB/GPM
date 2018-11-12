<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/lstServovalve-model.php';

// Création d'une instance
$lstServovalve = new ServovalveModel($db);
$lastSeen=$lstServovalve->getLastSeen($_POST['id_servovalve']);


echo json_encode($lastSeen);
