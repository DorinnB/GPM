<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


if (!isset($_GET['file_type']) OR !isset($_GET['file_name'])) {
  exit;
}

// Rendre votre modèle accessible
include '../models/lstFilePath-model.php';
// Création d'une instance
$ofilePath = new FilePathsModel($db);

$ofilePath->file_type=$_GET['file_type'];
$filePath=$ofilePath->getFilePath();







if ($filePath) {

  $filename = $filePath['file_path'].$_GET['file_name'];

  if (file_exists($filename)) {
  	$content = file_get_contents($filename);
  	header("Content-Disposition: inline; filename=$filename");
  	header("Content-type: application/pdf");
  	header('Cache-Control: private, max-age=0, must-revalidate');
  	header('Pragma: public');
  	echo $content;
  } else {
      echo $filename.' does exist anymore.<br> Please contact Quality Manager';
  }

}
else {
echo 'Incorrect File category.<br> This should not happen. Please contact IT Manager';
}



?>
