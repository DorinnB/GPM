<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['TDR']) OR $_GET['TDR']=="")	{
  exit();

}
else {
  $TDRID=explode("_",$_GET['TDR'])[1];
}


// Rendre votre modèle accessible
include '../models/qualite-model.php';
$oQualite = new QualiteModel($db);
$TDR=$oQualite->getTDR($TDRID);


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

$oEprouvette = new EprouvetteModel($db,$TDR['id_eprouvette']);

$essai=$oEprouvette->getTest();
$tempCorrected=$oEprouvette->getTempCorrected();
$area = $oEprouvette->calculArea($essai['id_dessin_type'],$essai['dim1'],$essai['dim2'],$essai['dim3'])['area'];




/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Paris');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../lib/PHPExcel/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$wizard = new PHPExcel_Helper_HTML;



$style_gray = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb'=>'C0C0C0'))
  );
  $style_white = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb'=>'000000'))
    );




    $essai['nom_eprouvette']=($essai['retest']!=1)?$essai['nom_eprouvette'].'<sup>'.$essai['retest'].'</sup>':$essai['nom_eprouvette'];


    if (isset($essai['split']))		//groupement du nom du job avec ou sans indice
    $jobcomplet= $essai['customer'].'-'.$essai['job'].'-'.$essai['split'];
    else
    $jobcomplet= $essai['customer'].'-'.$essai['job'];



    $objPHPExcel = $objReader->load("../templates/TDR.xlsx");
    $FT=$objPHPExcel->getSheetByName('TDR');



    $val2Xls = array(
      'A5' => $jobcomplet,
      'C5' => ' '.$essai['prefixe'],
      'F5' => $essai['nom_eprouvette'],
      'J5' => $essai['n_fichier'],
      'M5' => $essai['n_essai'],

      'A7' => $essai['machine'],
      'D7' => $area,
      'J7' => $TDR['technicien'],
      'M7' => ' ',

      'A12' => $TDR['TDR_type'],
      'A14' => $TDR['cyclenumber'],

      'M21' => $essai['c_temperature'],
      'O21' => $tempCorrected,

      'A58' => $TDR['TDR_text']

    );

    foreach ($val2Xls as $key => $value) {
      $FT->setCellValue($key, $value);
    }



    //exit;


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('../temp/TDR-'.$essai['n_fichier'].'-'.$TDRID.'.xlsx');

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="TDR-'.$essai['n_fichier'].'-'.$TDRID.'.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;

    ?>
