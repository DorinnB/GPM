<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

$ini = parse_ini_file('var/config.ini');

$machine=(isset($_GET['machine']))?$_GET['machine']:20005;



$filePath=$ini['PATH_labMonitoring'];


foreach (glob($filePath."*.png") as $file) {
    if($file == '.' || $file == '..') continue;



    $filename = explode("_", basename($file));

$dstfile = 'labMonitoring/'.$filename[0].'.png';
copy($file, $dstfile);

$screen[$filename[0]]=$dstfile;
}


//var_dump($screen);


?>
