<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



$now = time();
$june = strtotime("1st June");

if ($now > $june) {
  $getBegin=date("y-m-d", strtotime('+0 year', $june));
  $getEnd=date("y-m-d", strtotime('+1 year', $june));
}
else {
  $getBegin=date("y-m-d", strtotime('-1 year ', $june));
  $getEnd=date("y-m-d", strtotime('0 year', $june));
}






$getBegin=(isset($_GET['begin']))?$_GET['begin']:$getBegin;
$getEnd=(isset($_GET['end']))?$_GET['end']:$getEnd;

$begin = new DateTime($getBegin);
$end = new DateTime($getEnd);

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);





// Rendre votre modèle accessible
include_once '../models/lstPlanningUsers-model.php';
$oPlanningUser = new PlanningUsersModel($db);

$lstUsers=$oPlanningUser->getAllUsers();
$planningUser=$oPlanningUser->getAllPlanningUsers($getBegin,$getEnd);
$planningValidated=$oPlanningUser->getAllPlanningModifValidated($getBegin,$getEnd);
$planningAwaiting=$oPlanningUser->getAllPlanningModifAwaiting($getBegin,$getEnd);

$planningUpdated=$oPlanningUser->getAllPlanningUpdated($getBegin,$getEnd);


foreach ($planningUpdated as $key => $value) {
  $planning[$value['dateplanned']][$value['id_user']]=array("quantity" => $value['quantity'], "id_planning_type" => $value['id_type'], "workable" => $value['workable'], "val" => $value['val'], "calculGPM" => $value['calculGPM']);
}

/*
foreach ($planningUser as $key => $value) {
  $planning[$value['dateplanned']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['planning_type'], "id_planning_type" => $value['id_planning_type'], "workable" => $value['workable']);
}
foreach ($planningValidated as $key => $value) {
  $planning[$value['datemodif']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['planning_type'], "id_planning_type" => $value['id_planning_type'], "workable" => $value['workable']);
}
*/





/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Paris');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include \PhpOffice\PhpSpreadsheet\Spreadsheet */
//require '../vendor/autoload.php';
require '../vendor/autoload.php';

// Create new \PhpOffice\PhpSpreadsheet\Spreadsheet object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');

$objPHPExcel = $objReader->load("../templates/planning.xlsx");

$planningxls['joursem'][]="";
$planningxls['year'][]="";
$planningxls['date'][]="id_user";
$planningxls['joursem'][]="";
$planningxls['year'][]="";
$planningxls['date'][]="Users";
foreach ($lstUsers as $oUser)  {
  $planningxls['q'.$oUser['technicien']][]=$oUser['id_technicien'];
  $planningxls['q'.$oUser['technicien']][]=$oUser['technicien'];
}
$planningxls['space1'][]="";
$planningxls['space2'][]="";
$planningxls['space3'][]="";
$planningxls['space4'][]="";
$planningxls['space5'][]="";
foreach ($lstUsers as $oUser)  {
  $planningxls['d'.$oUser['technicien']][] = $oUser['id_technicien'];
  $planningxls['d'.$oUser['technicien']][] = $oUser['technicien'];
}
$planningxls['space6'][]="";
$planningxls['space7'][]="";
$planningxls['space8'][]="";
$planningxls['space9'][]="";
$planningxls['space10'][]="";
foreach ($lstUsers as $oUser)  {
  $planningxls['t'.$oUser['technicien']][] = $oUser['id_technicien'];
  $planningxls['t'.$oUser['technicien']][] = $oUser['technicien'];
}
foreach ($period as $key => $value) {
  $excelDateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(
                  $value->format("Y-m-d") );

  $planningxls['joursem'][]= $value->format("w");
  $planningxls['year'][]= $excelDateValue;
  $planningxls['date'][]= $excelDateValue;
  foreach ($lstUsers as $oUser)  {
    $planningxls['q'.$oUser['technicien']][] = isset($planning[$value->format("Y-m-d")][$oUser['id_technicien']])?$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity']:'';
    $planningxls['d'.$oUser['technicien']][] = isset($planning[$value->format("Y-m-d")][$oUser['id_technicien']])?
    (($planning[$value->format("Y-m-d")][$oUser['id_technicien']]['val']>0)?
    $planning[$value->format("Y-m-d")][$oUser['id_technicien']]['val']:
      $planning[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity'])
      :'';
    $planningxls['t'.$oUser['technicien']][] = isset($planning[$value->format("Y-m-d")][$oUser['id_technicien']])?$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['id_planning_type']:'';
  }
}




$objPHPExcel->getActiveSheet()
->fromArray(
  $planningxls,  // The data to set
  NULL,        // Array values with this value will not be set
  'A4'         // Top left coordinate of the worksheet range where
  //    we want to set these values (default is A1)
);

$objPHPExcel->getActiveSheet()
->fromArray(
  $oPlanningUser->getAllPlanningTypes(),  // The data to set
  NULL,        // Array values with this value will not be set
  'A41'         // Top left coordinate of the worksheet range where
  //    we want to set these values (default is A1)
);

$objPHPExcel->getActiveSheet()
->fromArray(
  $lstUsers,  // The data to set
  NULL,        // Array values with this value will not be set
  'I42'         // Top left coordinate of the worksheet range where
  //    we want to set these values (default is A1)
);


/*
$rowCount=7;
$rowType=18;
$rowCalc=35;

foreach ($lstUsers as $oUser)  {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $rowCalc, $oUser['technicien']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $rowCalc, '=COUNTIF('.$rowType.':'.$rowType.',1)+COUNTIF('.$rowType.':'.$rowType.',6)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $rowCalc, '=SUMIF('.$rowType.':'.$rowType.',1,'.$rowCount.':'.$rowCount.')+SUMIF('.$rowType.':'.$rowType.',6,'.$rowCount.':'.$rowCount.')');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $rowCalc, '=COUNTIF('.$rowType.':'.$rowType.',2)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $rowCalc, '=SUMIF('.$rowType.':'.$rowType.',2,'.$rowCount.':'.$rowCount.')');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $rowCalc, '=SUMIFS('.$rowType.':'.$rowType.',1'.$rowType.':'.$rowType.',1,$4:$4,6)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $rowCalc, '=SUMIFS('.$rowCount.':'.$rowCount.',1'.$rowType.':'.$rowType.',1,$4:$4,6)');

        $rowCalc++;
        $rowCount++;
        $rowType++;
}
*/



$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');

$file='../temp/Planning.xlsx';
$objWriter->save($file);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Planning.xlsx"');
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
