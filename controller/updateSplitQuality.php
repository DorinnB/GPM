<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/split-model.php';

// Création d'une instance
$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);

// Retour de l'update si erreur
//return $oSplit->updateSplit($_POST['id_statut']);
$oSplit->tbljob_commentaire_qualite=$_POST['tbljob_commentaire_qualite'];

$oSplit->updateCommentaireQuality();

?>
