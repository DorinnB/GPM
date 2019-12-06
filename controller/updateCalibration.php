<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


if (!isset($_COOKIE['id_user']) OR $_COOKIE['id_user']=="") {
  echo "no";
  exit;
}


// Rendre votre modèle accessible
include '../models/calibration-model.php';
$oCalibration = new CalibrationModel($db);


function delete_file($pFilename)    {
  if ( file_exists($pFilename) ) {
    //    Added by muhammad.begawala
    //    '@' will stop displaying "Resource Unavailable" error because of file is open some where.
    //    'unlink($pFilename) !== true' will check if file is deleted successfully.
    //  Throwing exception so that we can handle error easily instead of displaying to users.
    if( @unlink($pFilename) !== true )
    throw new Exception('Could not delete file: ' . $pFilename . ' Please close all applications that are using it.');
  }
  return true;
}



if (isset($_POST['type']) AND $_POST['type']=="check" and isset($_POST['idCalibration'])) { //check

  //deplacement du fichier de calib vers repertoire Qualite
  $myFile= $ini['PATH_calibration2'].$_POST['idCalibration'].'_*.pdf';

  foreach (glob($myFile) as $fileName) {
    copy($fileName, $ini['PATH_calibration1'].basename($fileName,'.pdf').'_'.gmdate('Y-m-d H-i-s').'.pdf');

    try {
      if( delete_file($fileName) === true ) {
        $oCalibration->checkCalibration($_POST['idCalibration']);
      }
    }
    catch (Exception $e) {
      //var_dump($e);
      $maReponse = array('result' => 'cantDelete', 'message'=> $e->getMessage());
      echo json_encode($maReponse);
      //        echo json_encode($e->getMessage()); // will print Exception message defined above.
    }


  }

}
elseif (isset($_FILES['calToUpload']['tmp_name']) AND $_FILES['calToUpload']['tmp_name']!="")	{ //upload calib


  $fichierCal = $_FILES['calToUpload']['tmp_name'];


  //  Include \PhpOffice\PhpSpreadsheet\IOFactory
  require '../vendor/autoload.php';

  // Create new \PhpOffice\PhpSpreadsheet\Spreadsheet object
  $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');


  $inputFileName = $fichierCal;

  $objPHPExcel = $objReader->load($fichierCal);



  $calType = $objPHPExcel->getSheetNames()[0];

  $sheet=$objPHPExcel->getSheet(0);


  if ( $calType = "TempLine") {

    $sheet->setCellValue("M5", $_COOKIE['technicien']);

    $oCalibration->checker=$_COOKIE['id_user'];
    $oCalibration->frame=$sheet->getCell('A5')->getValue();
    $oCalibration->id_element="";
    $oCalibration->date_start=$sheet->getCell('K5')->getValue();
    $oCalibration->date_end=$sheet->getCell('K7')->getFormattedValue();
    $oCalibration->thermocouple=$sheet->getCell('A7')->getValue();
    $oCalibration->adjustment=($sheet->getCell('A17')->getValue()=="Yes")?1:0;
    $oCalibration->scale=$sheet->getCell('E17')->getCalculatedValue();
    $oCalibration->cancelprevious=($sheet->getCell('I17')->getValue()=="Yes")?1:0;
    $oCalibration->compliant=($sheet->getCell('M17')->getCalculatedValue()=="Yes")?2:0;



    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');

    $name='TempLine_'.($sheet->getCell('A5')->getValue()).'_'.($sheet->getCell('A7')->getValue());
    $file=$name.'.xlsx';
    $objWriter->save('../temp/'.$file);




    $id=$oCalibration->insertNewTempLine();
    $cmd=$ini['PATH_GPMlocal'].'lib/calibration.bat '.$name.' '.$id.'_'.$name;
    //echo $cmd;

    pclose(popen("start /B ". $cmd, "r"));


    $filename = $ini['PATH_calibration2'].$id.'_'.$name.'.pdf';

    $tempMax=0;
    while( !file_exists($filename) OR $tempMax>60)  {
      sleep(1);
      $tempMax+=1;
    }



  }



}
?>
