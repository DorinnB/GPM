<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


if (!isset($_GET['customer']))	{
  exit;
}



// Rendre votre modèle accessible
include '../models/lstJobs-model.php';
$oJob = new LstJobsModel($db);
$lstJobCust=$oJob->getWeeklyReportSubC($_GET['customer']);

foreach ($lstJobCust as $key => $value) {
  $infoJobs[$value['id_info_job']]=$oJob->getWeeklyReportSubCJob($value['id_info_job'], $_GET['customer']);
}








$date=date("Y-m-d H-i-s");

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
$objReader->setIncludeCharts(TRUE);


$style_interligne = array(
  'fill' => array(
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'ddd9c4')
  ),
  'font'  => array(
    'color' => array('rgb' => '2D4D6A')
  )
);
$style_InProgress = array(
  'fill' => array(
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'E2EFDA')
  ),
  'font'  => array(
    'color' => array('rgb' => '2D4D6A')
  )
);
$style_Completed = array(
  'fill' => array(
    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'e6e6e6')
  ),
  'font'  => array(
    'color' => array('rgb' => '2D4D6A')
  )
);
$style_AwaitingInstructions = array(
  'fill' => array(
    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'E2EFDA')
  ),
  'font'  => array(
    'color' => array('rgb' => 'C00000')
  )
);
$style_ReportEdition = array(
  'fill' => array(
    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'E2EFDA')
  ),
  'font'  => array(
    'color' => array('rgb' => '008000')
  )
);
$style_Normal = array(
  'fill' => array(
    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'FFFFFF')
  ),
  'font'  => array(
    'color' => array('rgb' => '2D4D6A')
  )
);


//nom du fichier excel d'UBR
$objPHPExcel = $objReader->load("../templates/WeeklyReportSubC.xlsm");

$page=$objPHPExcel->getSheetByName('WeeklyReport');


$row = 3; // 1-based index


//pour chaque split commencé non fini
foreach ($lstJobCust as $key => $value) {

  //on copie le style de pour chaque job
  for ($colEnTete = 0; $colEnTete <= 14; $colEnTete++) {
    $style = $page->getStyleByColumnAndrow(1+$colEnTete, 3);
    $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$colEnTete) . (string)($row);
    $page->duplicateStyle($style, $dstCell);
  }
  $firstLine=$row;
  //on ecrit les données par split
  $page->setCellValueByColumnAndRow(1+0, $row, $value['job']);
  $page->setCellValueByColumnAndRow(1+1, $row, $value['customer']);
  $page->setCellValueByColumnAndRow(1+2, $row, $value['po_number']."\n".$value['instruction']);
  $page->setCellValueByColumnAndRow(1+3, $row, $value['ref_matiere']);
  $page->setCellValueByColumnAndRow(1+4, $row, '');
  $page->setCellValueByColumnAndRow(1+5, $row, 0);
  $page->setCellValueByColumnAndRow(1+6, $row, 'Réception Matière');
  $page->setCellValueByColumnAndRow(1+7, $row, $value['nbreceived']);
  $page->setCellValueByColumnAndRow(1+8, $row, $value['nbep']);
  $page->setCellValueByColumnAndRow(1+9, $row, (isset($value['firstSent'])?'Sent '.$value['firstSent']:' Not Sent'));
  $page->setCellValueByColumnAndRow(1+10, $row, '');
  $page->setCellValueByColumnAndRow(1+11, $row, '');
  $page->setCellValueByColumnAndRow(1+12, $row, $value['SubCComment']);
  $page->getStyleByColumnAndRow(10,$row)->getAlignment()->setWrapText(true);

  $row++;

  foreach ($infoJobs[$value['id_info_job']] as $k => $v) {

    //on copie le style de pour chaque split
    for ($colEnTete = 1; $colEnTete <= 14; $colEnTete++) {
      $style = $page->getStyleByColumnAndrow(1+$colEnTete, 4);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$colEnTete) . (string)($row);
      $page->duplicateStyle($style, $dstCell);
    }

    $page->setCellValueByColumnAndRow(1+4, $row, $v['refSubC']);
    $page->setCellValueByColumnAndRow(1+5, $row, $v['split']);
    $page->setCellValueByColumnAndRow(1+6, $row, $v['test_type_cust']);
    $page->setCellValueByColumnAndRow(1+7, $row, $v['nbtest']);
    $page->setCellValueByColumnAndRow(1+8, $row, $v['nbtestplanned']);
    $page->setCellValueByColumnAndRow(1+9, $row, $v['statut_client']);
    $page->setCellValueByColumnAndRow(1+10, $row, $v['DyT_SubC']);
    $page->setCellValueByColumnAndRow(1+11, $row, '');


    if ($v['statut_client']=="In Progress") {
      $page->getStyle('D'.$row.':M'.$row)->applyFromArray( $style_InProgress );
    }
    elseif ($v['statut_client']=="Completed") {
      $page->getStyle('D'.$row.':M'.$row)->applyFromArray( $style_Completed );
    }
    elseif ($v['statut_client']=="Awaiting Instructions") {
      $page->getStyle('D'.$row.':M'.$row)->applyFromArray( $style_AwaitingInstructions );
    }
    elseif ($v['statut_client']=="Report Edition") {
      $page->getStyle('D'.$row.':M'.$row)->applyFromArray( $style_ReportEdition );
    }
    else {
      $page->getStyle('D'.$row.':M'.$row)->applyFromArray( $style_Normal );
    }

    $row++;
  }

  $page->mergeCells('A'.$firstLine.':A'.($row-1));
  $page->mergeCells('B'.$firstLine.':B'.($row-1));
  $page->mergeCells('C'.$firstLine.':C'.($row-1));
  $page->mergeCells('D'.$firstLine.':D'.($row-1));
  $page->mergeCells('M'.$firstLine.':M'.($row-1));

  $page->getStyle('A'.$row.':M'.$row)->applyFromArray( $style_interligne );
  $row++;
}




//$page->setCellValue('K'.($row+2), $date);
//exit;


$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->setIncludeCharts(TRUE);

$file='../temp/WeeklyReportSubC-'.$date.'.xlsm';
$objWriter->save($file);


// Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
header('Content-Disposition: attachment;filename="WeeklyReportSubC-'.$date.'.xlsm"');
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
