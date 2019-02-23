<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once '../models/split-model.php';
// Création d'une instance
$oSplitInfo = new LstSplitModel($db,$_GET['id_tbljob']);

$SplitInfo = $oSplitInfo->getShortSplit();


// Affichage du résultat
include '../views/report-icone-view.php';

?>
