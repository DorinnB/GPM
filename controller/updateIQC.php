<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()





if (isset($_FILES['fileToUpload']['tmp_name']) AND $_FILES['fileToUpload']['tmp_name']!="")	{








  $fichierIQC = $_FILES['fileToUpload']['tmp_name'];

  // Rendre votre modèle accessible
  include '../models/annexe_IQC-model.php';
  $oIQC = new AnnexeIQCModel($db);



  //  Include PHPExcel_IOFactory
  require_once '../lib/PHPExcel/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();
  $objReader = PHPExcel_IOFactory::createReader('Excel2007');


  $inputFileName = $fichierIQC;

  $objPHPExcel = $objReader->load($fichierIQC);




  //  Get worksheet dimensions
  //$sheet = $objPHPExcel->getActiveSheet();
  $sheet = $objPHPExcel->getSheetByName('New Entry');
  $highestRow = $sheet->getHighestRow();


//check si l'id du fichier (A5) correspond a celui de GPM
  if (isset($_POST['id_tbljob']) AND $_POST['id_tbljob']!="") {
    $idJob=$sheet->getCell('A5')->getValue();
    if ($idJob!=$_POST['id_tbljob']) {
      echo 'This dimensionnal file don\'t match this split !';
      exit();
    }
  }



$oIQC->comments=$sheet->getCell('N5')->getValue();
$oIQC->tbljob_commentaire=$sheet->getCell('F10')->getValue();
$oIQC->tbljob_commentaire_qualite=$sheet->getCell('N10')->getValue();
$oIQC->updateComments($_POST['id_tbljob']);


  //  Loop through each row of the worksheet in turn
  for ($row = 15; $row <= $highestRow; $row++){

    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('B'. $row.':P'.$row,
    NULL,
    TRUE,
    FALSE);


    if ($rowData[0][1]!="") {
      $oIQC = new AnnexeIQCModel($db);
      $IsIQC=$oIQC->getIQCid($_POST['id_tbljob'], $rowData[0][0], $rowData[0][1]);

      if (!$IsIQC) {
Echo 'oula. Une eprouvette semble avoir un mauvais nom malgré les aides fournis dans Excel';
        exit;
      }

      $oIQC2 = new AnnexeIQCModel($db);

      $oIQC2->dim1=$rowData[0][2];
      $oIQC2->dim2=$rowData[0][3];
      $oIQC2->dim3=$rowData[0][4];
      $oIQC2->marquage=$rowData[0][6];
      $oIQC2->surface=$rowData[0][7];
      $oIQC2->grenaillage=$rowData[0][8];
      $oIQC2->revetement=$rowData[0][9];
      $oIQC2->protection=$rowData[0][10];
      $oIQC2->autre=$rowData[0][11];

      $oIQC2->observation=$rowData[0][12];
      $oIQC2->date_IQC=date('Y-m-d H:i:s');
      $oIQC2->id_tech=$_COOKIE['id_user'];



      $oIQC2->inserupdateIQC($IsIQC['id_eprouvette']);


    }

  }
}



?>
