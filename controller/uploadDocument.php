<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/document-model.php';

// Création d'une instance
$oDocument = new DocumentModel($db);





$uploads_dir = '//SRVDC/DONNEES/labo/Computer/BDD/Document/'.$_POST['type'];

$tmp_name = $_FILES["file-input"]["tmp_name"];
$name = $_FILES["file-input"]["name"];


if (file_exists("$uploads_dir/$name")) {
  echo "File not uploaded.\n   ".$name."\nalready exists on\n   ".$uploads_dir;
  exit;
}
move_uploaded_file($tmp_name, "$uploads_dir/$name");

$document=$oDocument->newDocument($_POST['id'], $_POST['tbl'], $_POST['type'], $uploads_dir, $name);





 ?>
