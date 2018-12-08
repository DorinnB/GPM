<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_ep']) OR $_GET['id_ep']=="")	{
  exit();

}



// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

$oEprouvette = new EprouvetteModel($db,$_GET['id_ep']);

$essai=$oEprouvette->getTest();





/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Paris');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include \PhpOffice\PhpSpreadsheet\Spreadsheet */
require '../vendor/autoload.php';


// Create new \PhpOffice\PhpSpreadsheet\Spreadsheet object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$wizard = new \PhpOffice\PhpSpreadsheet\Helper\Html;



$style_gray = array(
  'fill' => array(
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'C0C0C0')
  )
);
$style_white = array(
  'fill' => array(
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'000000')
  )
);






if (isset($essai['split']))		//groupement du nom du job avec ou sans indice
$jobcomplet= $essai['customer'].'-'.$essai['job'].'-'.$essai['split'];
else
$jobcomplet= $essai['customer'].'-'.$essai['job'];



if (isset($essai['type_chauffage']) AND $essai['type_chauffage']=="Coil")	//chauffage coil
$chauffage="I";
elseif (isset($essai['type_chauffage']) AND $essai['type_chauffage']=="Four")	//chauffage coil
$chauffage="F";
else
$chauffage="";



$objPHPExcel = $objReader->load("../templates/IRR.xlsx");
$FT=$objPHPExcel->getSheetByName('Data Collection');


$NADCAPTestCode="?";
switch ($essai['test_type_abbr']) {
  case "Loa":
  $NADCAPTestCode="O";
  break;
  case "Str":
  $NADCAPTestCode="Y";
  break;
}


$val2Xls = array(
  'C6' => $essai['machine'],
  'C7' => $essai['cell_load_gamme'],
  'C8' => '',
  'C9' => $chauffage,
  'C10' => (($chauffage!="")?"K":""),
  'C13' => $NADCAPTestCode,
  'C14' => $essai['customer'],
  'C15' => $essai['job'],
  'C16' => $essai['n_fichier'],
  'C17' => $essai['ref_matiere'],

  'H6' => $essai['operateur'],
  'H7' => $essai['controleur']
);

foreach ($val2Xls as $key => $value) {
  $FT->setCellValue($key, $value);
}







//exit;

$FT->getProtection()->setSheet(true);
$FT->getProtection()->setPassword("metcut44");


$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');

$file='../temp/IRR-'.$essai['n_fichier'].'.xlsx';
$objWriter->save($file);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="IRR-'.$essai['n_fichier'].'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

readfile($file);

exit;

?>
