<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/lstIcone-model.php';

// Création d'une instance
$oIcone = new IconeModel($db);

if ($_POST['type']=='icone') {
  // Retour de l'update si erreur
  return $oIcone->updateIcone($_POST['id_machine'],$_POST['id_icone']);
}
elseif ($_POST['type']=='priorite') {
  return $oIcone->updatePriorite($_POST['id_machine'],$_POST['id_icone']);
}
elseif ($_POST['type']=='commentaire') {
  return $oIcone->updateCommentaire($_POST['id_machine'],$_POST['commentaire']);
}

?>
