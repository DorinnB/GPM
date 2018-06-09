<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/lstCellLoad-model.php';

// Création d'une instance
$lstCellLoad = new CellLoadModel($db);
$ref_customer=$lstCellLoad->getCellLoad($_GET['id_cell_load']);
