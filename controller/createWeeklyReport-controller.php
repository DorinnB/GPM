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


$style_interligne = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb'=>'ddd9c4')
  ),
  'font'  => array(
        'color' => array('rgb' => '2D4D6A')
      )
);
$style_InProgress = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb'=>'E2EFDA')
  ),
  'font'  => array(
        'color' => array('rgb' => '2D4D6A')
      )
);
$style_Completed = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb'=>'e6e6e6')
  ),
  'font'  => array(
        'color' => array('rgb' => '2D4D6A')
      )
);
$style_AwaitingInstructions = array(
  'font'  => array(
        'color' => array('rgb' => '800000')
      )
);
$style_ReportEdition = array(
  'font'  => array(
        'color' => array('rgb' => '008000')
      )
);
$style_Normal = array(
  'font'  => array(
        'color' => array('rgb' => '2D4D6A')
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
  $page->setCellValueByColumnAndRow(0, $row, $value['po_number']."\n".$value['instruction']);
  $page->setCellValueByColumnAndRow(1, $row, $value['ref_matiere']);
  $page->setCellValueByColumnAndRow(2, $row, $value['job']);
  $page->setCellValueByColumnAndRow(3, $row, 0);
  $page->setCellValueByColumnAndRow(4, $row, 'Réception Matière');
  $page->setCellValueByColumnAndRow(5, $row, $value['nbreceived']);
  $page->setCellValueByColumnAndRow(6, $row, $value['nbep']);
  $page->setCellValueByColumnAndRow(7, $row, (isset($value['firstReceived'])?'Receipt '.$value['firstReceived']:' Not Received'));
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
    $page->setCellValueByColumnAndRow(4, $row, $v['test_type_cust']);
    $page->setCellValueByColumnAndRow(5, $row, $v['nbtest']);
    $page->setCellValueByColumnAndRow(6, $row, $v['nbtestplanned']);
    $page->setCellValueByColumnAndRow(7, $row, $v['statut_client']);
    $page->setCellValueByColumnAndRow(8, $row, $v['DyT_Cust']);


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
  $availability->setCellValueByColumnAndRow($colDate, 47, date('Y-m-d', strtotime($value)));
  $colDate++;
}

$row2=53;

foreach ($lstFrames as $frame)  {
  $availability->setCellValueByColumnAndRow(0, $row2, $frame['machine']);

  $colDate=3;
  foreach ($date2 as $key => $value) {
    $availability->setCellValueByColumnAndRow($colDate, $row2, ((isset($planningFrame[$frame['id_machine']][$value]))?1:0));
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
	new PHPExcel_Chart_DataSeriesValues('String', 'FrameAvailability!$C$39', NULL, 1),	//	100 Strain
	new PHPExcel_Chart_DataSeriesValues('String', 'FrameAvailability!$C$40', NULL, 1),	//	100 Load
	new PHPExcel_Chart_DataSeriesValues('String', 'FrameAvailability!$C$41', NULL, 1),	//	250 Strain
	new PHPExcel_Chart_DataSeriesValues('String', 'FrameAvailability!$C$42', NULL, 1),	//	250 Load
);
//	Set the X-Axis Labels
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'FrameAvailability!$D$38:$O$38', NULL, 4),	//	Q1 to Q4
);
//	Set the Data values for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'FrameAvailability!$D$39:$O$39', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'FrameAvailability!$D$40:$O$40', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'FrameAvailability!$D$41:$O$41', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'FrameAvailability!$D$42:$O$42', NULL, 4),
);

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	$dataSeriesLabels,								// plotLabel
	$xAxisTickValues,								// plotCategory
	$dataSeriesValues								// plotValues
);
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

//	Set the series in the plot area
$plotArea = new PHPExcel_Chart_PlotArea(NULL, array($series));
//	Set the chart legend
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

$title = new PHPExcel_Chart_Title('Disponibilité Machines Metcut France');
$xAxisLabel = new PHPExcel_Chart_Title('Semaine');


//	Create the chart
$chart = new PHPExcel_Chart(
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
$availability->addChart($chart);












//$page->setCellValue('K'.($row+2), $date);



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
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
$objWriter->setIncludeCharts(TRUE);
$objWriter->save('php://output');
exit;
