<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/lstServovalve-model.php';

// Création d'une instance
$lstServovalve = new ServovalveModel($db);
$ref_customer=$lstServovalve->getServovalve($_GET['id_servovalve']);
