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
$objPHPExcel = $objReader->load("../templates/WeeklyReport.xlsm");

$page=$objPHPExcel->getSheetByName('WeeklyReport');


$row = 3; // 1-based index


//pour chaque split commencé non fini
foreach ($lstJobCust as $key => $value) {

  //on copie le style de pour chaque job
  for ($colEnTete = 0; $colEnTete <= 10; $colEnTete++) {
    $style = $page->getStyleByColumnAndrow(1+$colEnTete, 3);
    $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$colEnTete) . (string)($row);
    $page->duplicateStyle($style, $dstCell);
  }
  $firstLine=$row;
  //on ecrit les données par split
  $page->setCellValueByColumnAndRow(1+0, $row, $value['po_number']."\n".$value['instruction']);
  $page->setCellValueByColumnAndRow(1+1, $row, $value['ref_matiere']);
  $page->setCellValueByColumnAndRow(1+2, $row, $value['job']);
  $page->setCellValueByColumnAndRow(1+3, $row, 0);
  $page->setCellValueByColumnAndRow(1+4, $row, 'Material Receipt');
  $page->setCellValueByColumnAndRow(1+5, $row, $value['nbreceived']);
  $page->setCellValueByColumnAndRow(1+6, $row, $value['nbep']);
  $page->setCellValueByColumnAndRow(1+7, $row, (isset($value['firstReceived'])?'Receipt '.$value['firstReceived']:' Not Received'));
  $page->setCellValueByColumnAndRow(1+8, $row, $value['available_expected']);
  $page->setCellValueByColumnAndRow(1+9, $row, $value['weeklyComment']);
  $page->setCellValueByColumnAndRow(1+10, $row, $value['contactsXLS']);
  $page->getStyleByColumnAndRow(10,$row)->getAlignment()->setWrapText(true);

  $row++;

  foreach ($infoJobs[$value['id_info_job']] as $k => $v) {

    //on copie le style de pour chaque split
    for ($colEnTete = 1; $colEnTete <= 10; $colEnTete++) {
      $style = $page->getStyleByColumnAndrow(1+$colEnTete, 4);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$colEnTete) . (string)($row);
      $page->duplicateStyle($style, $dstCell);
    }

    $page->setCellValueByColumnAndRow(1+3, $row, $v['split']);
    $page->setCellValueByColumnAndRow(1+4, $row, $v['test_type_cust']);
    $page->setCellValueByColumnAndRow(1+5, $row, $v['nbtest']);
    $page->setCellValueByColumnAndRow(1+6, $row, $v['nbtestplanned']);
    $page->setCellValueByColumnAndRow(1+7, $row, $v['statut_client']);
    $page->setCellValueByColumnAndRow(1+8, $row, $v['DyT_Cust']);


    if ($v['statut_client']=="In Progress") {
      $page->getStyle('D'.$row.':I'.$row)->applyFromArray( $style_InProgress );
    }
    elseif ($v['statut_client']=="Completed") {
      $page->getStyle('D'.$row.':I'.$row)->applyFromArray( $style_Completed );
    }
    elseif ($v['statut_client']=="Awaiting Instructions") {
      $page->getStyle('D'.$row.':I'.$row)->applyFromArray( $style_AwaitingInstructions );
    }
    elseif ($v['statut_client']=="Report Edition") {
      $page->getStyle('D'.$row.':I'.$row)->applyFromArray( $style_ReportEdition );
    }
    else {
      $page->getStyle('D'.$row.':I'.$row)->applyFromArray( $style_Normal );
    }

    $row++;
  }

  $page->mergeCells('A'.$firstLine.':A'.($row-1));
  $page->mergeCells('B'.$firstLine.':B'.($row-1));
  $page->mergeCells('C'.$firstLine.':C'.($row-1));
  $page->mergeCells('j'.$firstLine.':j'.($row-1));
  $page->mergeCells('k'.$firstLine.':k'.($row-1));

  $page->getStyle('A'.$row.':K'.$row)->applyFromArray( $style_interligne );
  $row++;
}








$availability=$objPHPExcel->getSheetByName('FrameAvailability');


$nbJourPlanning=(isset($_GET['nbDayPlanned']))?$_GET['nbDayPlanned']:31*6;
$nbAvantNow=(isset($_GET['nbDayBefore']))?$_GET['nbDayBefore']:5;

// Rendre votre modèle accessible
include '../models/planningLab-model.php';
$oPlanningLab = new PLANNINGLAB($db);

include '../models/poste-model.php';
$oPoste = new PosteModel($db,0);
$lstFrames=$oPoste->getAllMachine();


//décompose la liste complete des plannings en tableau, par machine, des dates=id_tbljob
foreach ($lstFrames as $frame)  {
  $planningFrames=$oPlanningLab->getAllPlanningFrame($frame['id_machine'],$nbAvantNow);
  foreach ($planningFrames as $key => $value) {
    $planningFrame[$frame['id_machine']][$value['date']]   =   1;
  }
}

$now=date("Y-m-d");

for ($i=-$nbAvantNow; $i < $nbJourPlanning; $i++) {
  $date2[$i]=date('Y-m-d', strtotime($now . ' +'.$i.' day'));
}

$colDate=3;
foreach ($date2 as $key => $value) {
  $availability->setCellValueByColumnAndRow(1+$colDate, 45, date('Y-m-d', strtotime($value)));
  $colDate++;
}

$row2=53;

foreach ($lstFrames as $frame)  {
  $availability->setCellValueByColumnAndRow(1+0, $row2, $frame['machine']);

  $colDate=3;
  foreach ($date2 as $key => $value) {
    $availability->setCellValueByColumnAndRow(1+$colDate, $row2, ((isset($planningFrame[$frame['id_machine']][$value]))?1:0));
    $colDate++;
  }
  $row2++;
}






//	Set the Labels for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesLabels = array(
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'FrameAvailability!$C$37', NULL, 1),	//	Strain RT
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'FrameAvailability!$C$38', NULL, 1),	//	Strain Coil
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'FrameAvailability!$C$39', NULL, 1),	//	Strain Four
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'FrameAvailability!$C$40', NULL, 1),	//	Load RT
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'FrameAvailability!$C$41', NULL, 1),	//	Load coil
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'FrameAvailability!$C$42', NULL, 1),	//	Load Four
);
//	Set the X-Axis Labels
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$xAxisTickValues = array(
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'FrameAvailability!$D$36:$aa$36', NULL, 4),	//	Q1 to Q4
);
//	Set the Data values for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesValues = array(
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'FrameAvailability!$D$37:$aa$37', NULL, 4),
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'FrameAvailability!$D$38:$aa$38', NULL, 4),
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'FrameAvailability!$D$39:$aa$39', NULL, 4),
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'FrameAvailability!$D$40:$aa$40', NULL, 4),
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'FrameAvailability!$D$41:$aa$41', NULL, 4),
  new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'FrameAvailability!$D$42:$aa$42', NULL, 4),
);

//	Build the dataseries
$series = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
  \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART,		// plotType
  \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STACKED,	// plotGrouping
  range(0, count($dataSeriesValues)-1),			// plotOrder
  $dataSeriesLabels,								// plotLabel
  $xAxisTickValues,								// plotCategory
  $dataSeriesValues								// plotValues
);
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
$series->setPlotDirection(\PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

//	Set the series in the plot area
$plotArea = new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(NULL, array($series));
//	Set the chart legend
$legend = new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_BOTTOM, NULL, false);

$title = new \PhpOffice\PhpSpreadsheet\Chart\Title('Disponibilité Machines Metcut France');
$xAxisLabel = new \PhpOffice\PhpSpreadsheet\Chart\Title('Semaine');


//	Create the chart
$chart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
  'chart1',		// name
  $title,			// title
  $legend,		// legend
  $plotArea,		// plotArea
  true,			// plotVisibleOnly
  0,				// displayBlanksAs
  $xAxisLabel,			// xAxisLabel
  NULL		// yAxisLabel
);

//	Set the position where the chart should appear in the FrameAvailability
$chart->setTopLeftPosition('A1');
$chart->setBottomRightPosition('P26');

//	Add the chart to the FrameAvailability
//$availability->addChart($chart);












//$page->setCellValue('K'.($row+2), $date);
//exit;


$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->setIncludeCharts(TRUE);

$file='../temp/WeeklyReport-'.$date.'.xlsm';
$objWriter->save($file);


// Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
header('Content-Disposition: attachment;filename="WeeklyReport-'.$date.'.xlsm"');
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
