<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

$ini = parse_ini_file('../var/config.ini');

if (!isset($_GET['file_type']) OR !isset($_GET['file_name'])) {
  exit;
}



$filePath=$ini['PATH_'.$_GET['file_type']];





if ($filePath) {

  $filename = $filePath.$_GET['file_name'];

  if (file_exists($filename)) {
  	$content = file_get_contents($filename);
  	header("Content-Disposition: inline; filename=$filename");
  	header("Content-type: application/pdf");
  	header('Cache-Control: private, max-age=0, must-revalidate');
  	header('Pragma: public');
  	echo $content;
  } else {
      echo '[ '.$filename.' ] does exist anymore.<br> Please contact Quality Manager';
  }

}
else {
echo 'Incorrect File category.<br> This should not happen. Please contact IT Manager';
}



?>
