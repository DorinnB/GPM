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
$style_alert = array(
  'fill' => array(
    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'DA9694')
  ),
  'font'  => array(
    'color' => array('rgb' => 'FFFFFF')
  )
);
$style_delay = array(
  'fill' => array(
    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'000000')
  ),
  'font'  => array(
    'color' => array('rgb' => 'FFFFFF')
  )
);
$style_warning = array(
  'fill' => array(
    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'fabf8f')
  ),
  'font'  => array(
    'color' => array('rgb' => '2D4D6A')
  )
);
$style_Update = array(
  'fill' => array(
    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'F2DCDB')
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


$validation = $page->getCell('AA1')->getDataValidation(); //data validation for completed




//pour chaque split commencé non fini
foreach ($lstJobCust as $key => $value) {
  if ($value['nbuncompleted']!=0 OR $value['nbEpNotReceived']>0) {

    //on copie le style de pour chaque job
    for ($colEnTete = 0; $colEnTete <= 15; $colEnTete++) {
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
    $page->setCellValueByColumnAndRow(1+6, $row, 0);
    $page->setCellValueByColumnAndRow(1+7, $row, 'Shipment');
    $page->setCellValueByColumnAndRow(1+8, $row, $value['nbsent']);
    $page->setCellValueByColumnAndRow(1+9, $row, $value['nbep']);
    $page->setCellValueByColumnAndRow(1+10, $row, (isset($value['firstSent'])?'Shipped on '.$value['firstSent']:' Not Shipped'));
    $page->setCellValueByColumnAndRow(1+12, $row, '');
    $page->setCellValueByColumnAndRow(1+13, $row, '');
    $page->setCellValueByColumnAndRow(1+15, $row, $value['SubCComment']);
    $page->getStyleByColumnAndRow(15,$row)->getAlignment()->setWrapText(true);

    $row++;

    foreach ($infoJobs[$value['id_info_job']] as $k => $v) {

      //on copie le style de pour chaque split
      for ($colEnTete = 1; $colEnTete <= 15; $colEnTete++) {
        $style = $page->getStyleByColumnAndrow(1+$colEnTete, 4);
        $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$colEnTete) . (string)($row);
        $page->duplicateStyle($style, $dstCell);
      }





      if ($v['statut_SubC']=="In Progress") {
        $page->getStyle('E'.$row.':N'.$row)->applyFromArray( $style_InProgress );
      }
      elseif ($v['statut_SubC']=="Completed") {
        $page->getStyle('E'.$row.':N'.$row)->applyFromArray( $style_Completed );
      }
      elseif ($v['statut_SubC']=="Awaiting Instructions") {
        $page->getStyle('E'.$row.':N'.$row)->applyFromArray( $style_AwaitingInstructions );
      }
      elseif ($v['statut_SubC']=="Completed SubC") {
        $page->getStyle('E'.$row.':N'.$row)->applyFromArray( $style_Completed );
      }
      else {
        $page->getStyle('E'.$row.':N'.$row)->applyFromArray( $style_Normal );
      }


      $page->setCellValueByColumnAndRow(1+4, $row, $v['refSubC']);      $page->getStyle('F'.$row.':F'.$row)->applyFromArray(((isset($value['firstSent']) AND $value['firstSent']>0 AND $v['refSubC']=='')?$style_alert:$style_Update));
      $page->setCellValueByColumnAndRow(1+6, $row, $v['split']);
      $page->setCellValueByColumnAndRow(1+7, $row, $v['test_type_cust']);
      $page->setCellValueByColumnAndRow(1+8, $row, $v['nbtest']);
      $page->setCellValueByColumnAndRow(1+9, $row, $v['nbtestplanned']);
      $page->setCellValueByColumnAndRow(1+10, $row, $v['statut_SubC']);
      $page->getStyle('L'.$row)->applyFromArray( $style_Update );    $page->getCellByColumnAndRow(1+11,$row)->setDataValidation(clone $validation);
      $page->setCellValueByColumnAndRow(1+12, $row, $v['DyT_SubC']);
      $page->setCellValueByColumnAndRow(1+13, $row, (isset($v['DyT_expected'])?date('Y-m-d', strtotime($v['DyT_expected']. ' - 3 days')):''));

      $delay=(isset($v['DyT_expected']))?(strtotime(date("Y-m-d"))-strtotime(date('Y-m-d', strtotime($v['DyT_expected']. ' - 3 days'))))/86400:-9999;
      if ($delay>=0 AND $v['statut_SubC']!="Completed SubC") {$page->getStyle('N'.$row)->applyFromArray( $style_delay );} elseif ($delay>=-7 AND $v['statut_SubC']!="Completed SubC") {$page->getStyle('N'.$row)->applyFromArray( $style_warning );}

      $page->getStyle('O'.$row)->applyFromArray(((isset($value['firstSent']) AND $value['firstSent']>0 AND $v['DyT_expected']=='')?$style_alert:$style_Update));





      $row++;
    }


    $page->mergeCells('A'.$firstLine.':A'.($row-1));
    $page->mergeCells('B'.$firstLine.':B'.($row-1));
    $page->mergeCells('C'.$firstLine.':C'.($row-1));
    $page->mergeCells('D'.$firstLine.':D'.($row-1));
    $page->mergeCells('P'.$firstLine.':P'.($row-1));

    $page->getStyle('A'.$row.':P'.$row)->applyFromArray( $style_interligne );
    $row++;
  }
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
