<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


if (!isset($_GET['customer']))	{
exit;
}



// Rendre votre modèle accessible
include '../models/lstJobs-model.php';
$oJob = new LstJobsModel($db);
$lstJobCust=$oJob->getWeeklyReportCust($_GET['customer']);

foreach ($lstJobCust as $key => $value) {
  $infoJobs[$value['id_info_job']]=$oJob->getWeeklyReportJob($value['id_info_job']);
}








$date=date("Y-m-d H-i-s");

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
$objReader->setIncludeCharts(TRUE);


$style_gray = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb'=>'E2EFDA')
  )
);


//nom du fichier excel d'UBR
$objPHPExcel = $objReader->load("../lib/PHPExcel/templates/WeeklyReport.xlsx");

$page=$objPHPExcel->getSheetByName('WeeklyReport');


$row = 3; // 1-based index


//pour chaque split commencé non fini
foreach ($lstJobCust as $key => $value) {

  //on copie le style de pour chaque job
 for ($colEnTete = 0; $colEnTete <= 10; $colEnTete++) {
   $style = $page->getStyleByColumnAndRow($colEnTete, 3);
   $dstCell = PHPExcel_Cell::stringFromColumnIndex($colEnTete) . (string)($row);
   $page->duplicateStyle($style, $dstCell);
 }
$firstLine=$row;
  //on ecrit les données par split
  $page->setCellValueByColumnAndRow(0, $row, $value['po_number']);
  $page->setCellValueByColumnAndRow(1, $row, $value['ref_matiere']);
  $page->setCellValueByColumnAndRow(2, $row, $value['job']);
  $page->setCellValueByColumnAndRow(3, $row, 0);
  $page->setCellValueByColumnAndRow(4, $row, 'Réception Matière');
  $page->setCellValueByColumnAndRow(5, $row, $value['nbreceived']);
  $page->setCellValueByColumnAndRow(6, $row, $value['nbep']);
  $page->setCellValueByColumnAndRow(7, $row, (isset($value['firstReceived'])?'Receipt '.$value['firstReceived']:''));
  $page->setCellValueByColumnAndRow(8, $row, $value['available_expected']);
  $page->setCellValueByColumnAndRow(9, $row, $value['weeklyComment']);
  $page->setCellValueByColumnAndRow(10, $row, $value['contactsXLS']);
  $page->getStyleByColumnAndRow(10,$row)->getAlignment()->setWrapText(true);

$row++;

 foreach ($infoJobs[$value['id_info_job']] as $k => $v) {

    //on copie le style de pour chaque split
   for ($colEnTete = 1; $colEnTete <= 10; $colEnTete++) {
     $style = $page->getStyleByColumnAndRow($colEnTete, 4);
     $dstCell = PHPExcel_Cell::stringFromColumnIndex($colEnTete) . (string)($row);
     $page->duplicateStyle($style, $dstCell);
   }

  $page->setCellValueByColumnAndRow(3, $row, $v['split']);
  $page->setCellValueByColumnAndRow(4, $row, $v['test_type_abbr']);
  $page->setCellValueByColumnAndRow(5, $row, $v['nbtest']);
  $page->setCellValueByColumnAndRow(6, $row, $v['nbtestplanned']);
  $page->setCellValueByColumnAndRow(7, $row, $v['statut_client']);
  $page->setCellValueByColumnAndRow(8, $row, $v['DyT_Cust']);

  $row++;
 }

        $page->mergeCells('A'.$firstLine.':A'.($row-1));
        $page->mergeCells('B'.$firstLine.':B'.($row-1));
        $page->mergeCells('C'.$firstLine.':C'.($row-1));
        $page->mergeCells('j'.$firstLine.':j'.($row-1));
        $page->mergeCells('k'.$firstLine.':k'.($row-1));

$page->getStyle('A'.$row.':K'.$row)->applyFromArray( $style_gray );
$row++;
}


//$page->setCellValue('K'.($row+2), $date);



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->setIncludeCharts(TRUE);
$objWriter->save('../lib/PHPExcel/files/WeeklyReport-'.$date.'.xlsx');


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="WeeklyReport-'.$date.'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->setIncludeCharts(TRUE);
$objWriter->save('php://output');
exit;
