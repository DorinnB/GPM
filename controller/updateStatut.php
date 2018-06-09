<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/statut-model.php';

// Création d'une instance
$oStatut = new StatutModel($db);

$oStatut->id_tbljob=$_POST['id_tbljob'];
// Retour de l'update si erreur
return $oStatut->updateStatut2($_POST['id_statut']);

?>
