<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/lstCellDisplacement-model.php';

// Création d'une instance
$lstCellDisplacement = new CellDisplacementModel($db);
$ref_displacement=$lstCellDisplacement->getCellDisplacement($_GET['id_cell_displacement']);
