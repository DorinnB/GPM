<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_tbljob']) OR $_GET['id_tbljob']=="")	{
  exit();

}


// Rendre votre modèle accessible
include '../models/split-model.php';
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);

$split=$oSplit->getSplit();




$filename = '../OneNote/15.0/Sauvegarder/Notebook-JOBS En Cours/'.$split['customer'].'-'.$split['job'].'*.one';



if (glob($filename)) {
  $oneNote=glob($filename)[0];

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($oneNote).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($oneNote));
    readfile($oneNote);
    exit;

}
else {
  echo 'OneNote was not found.';
}
