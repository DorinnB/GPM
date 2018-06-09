<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include_once '../models/lstJobs-model.php';
include_once '../models/eprouvettes-model.php';
include_once '../models/eprouvette-model.php';
include_once '../models/invoice-model.php';










// Création d'une instance
$oFollowup = new LstJobsModel($db);
//filtre specifique des splits
$filtreFollowup=(isset($_GET['filtreFollowup']))?$_GET['filtreFollowup']:'';


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

//nom du fichier excel d'UBR
$objPHPExcel = $objReader->load("../lib/PHPExcel/templates/UBR.xlsx");

$enTete=$objPHPExcel->getSheetByName('Summary');
$template=$objPHPExcel->getSheetByName('Template');




$rowEnTete = 9; // 1-based index


//pour chaque split commencé non fini
foreach ($oFollowup->getAllFollowup($filtreFollowup) as $row) {

  if ($row['nbtest']>0) {


    //on recupere la liste des eprouvettes de ce split
    $oEprouvettes = new LstEprouvettesModel($db,$row['id_tbljob']);
    $ep=$oEprouvettes->getAllEprouvettes();

    //on se crée un tableau $ep[$k] des informations
    for($k=0;$k < count($ep);$k++)	{
      $oEprouvette = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
      $ep[$k]=$oEprouvette->getTest();
    }

    //on copie le style de l'entete pour chaque split
    for ($colEnTete = 0; $colEnTete <= 7; $colEnTete++) {
      $style = $enTete->getStyleByColumnAndRow($colEnTete, 9);
      $dstCell = PHPExcel_Cell::stringFromColumnIndex($colEnTete) . (string)($rowEnTete);
      $enTete->duplicateStyle($style, $dstCell);
    }
    //on ecrit les données par split
    $enTete->setCellValueByColumnAndRow(0, $rowEnTete, $row['customer']);
    $enTete->setCellValueByColumnAndRow(1, $rowEnTete, $row['job']);
    $enTete->setCellValueByColumnAndRow(2, $rowEnTete, $row['split']);
    $enTete->setCellValueByColumnAndRow(3, $rowEnTete, $row['test_type_abbr']);
    $enTete->setCellValueByColumnAndRow(4, $rowEnTete, $row['temperature']);
    $enTete->setCellValueByColumnAndRow(5, $rowEnTete, $row['nbtest']);
    $enTete->setCellValueByColumnAndRow(6, $rowEnTete, $row['nbRetest']);

    $tpsSup=0;  //heure sup a 0 et on incrementera au fur et a mesure des eprouvettes


    //on crée un nouvel onglet du nom du split
    $newSheet = $objPHPExcel->getSheetByName('Template')->copy();
    $newSheet->setTitle($row['customer'].'-'.$row['job'].'-'.$row['split']);
    $objPHPExcel->addSheet($newSheet);


    $row = 0; // 1-based index
    $col = 3;
    //pour chaque eprouvette, on écrit les données de celle ci
    foreach ($ep as $key => $value) {
      //copy des styles des colonnes
      for ($row = 5; $row <= 17; $row++) {
        $style = $newSheet->getStyleByColumnAndRow(3, $row);
        $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($row);
        $newSheet->duplicateStyle($style, $dstCell);
      }

      $newSheet->setCellValueByColumnAndRow($col, 5, $value['prefixe']);
      $newSheet->setCellValueByColumnAndRow($col, 6, $value['nom_eprouvette']);
      $newSheet->setCellValueByColumnAndRow($col, 7, $value['n_essai']);
      $newSheet->setCellValueByColumnAndRow($col, 8, $value['n_fichier']);
      $newSheet->setCellValueByColumnAndRow($col, 9, $value['date']);
      $newSheet->setCellValueByColumnAndRow($col, 10, $value['c_temperature']);
      $newSheet->setCellValueByColumnAndRow($col, 11, $value['c_frequence']);
      $newSheet->setCellValueByColumnAndRow($col, 12, ($value['Cycle_STL']==0)?"":$value['Cycle_STL']);
      $newSheet->setCellValueByColumnAndRow($col, 13, $value['c_frequence_STL']);
      $newSheet->setCellValueByColumnAndRow($col, 14, $value['Cycle_final']);

      if ($value['c_frequence']>0 AND $value['Cycle_final']>0) {
        //calcul du temps d'essai
        if ($value['temps_essais']>0) {
          $tpsEssai = $value['temps_essais'];
        }
        else {
          if ($value['Cycle_STL']>0) {
            $tpsEssai = (($value['Cycle_final']-$value['Cycle_STL'])/$value['c_frequence_STL']+$value['Cycle_STL']/$value['c_frequence'])/3600;
          }
          else {
            $tpsEssai = $value['Cycle_final']/$value['c_frequence']/3600;
          }
        }

        $tpsSupSplit=($tpsEssai>24)?$tpsEssai-24:0;
        $newSheet->setCellValueByColumnAndRow($col, 15, $tpsEssai);
        $newSheet->setCellValueByColumnAndRow($col, 16, $tpsSupSplit);
        $tpsSup+=$tpsSupSplit;
      }
      $col++;
    }

    //on ecrit le temps total sur la page d'entete
    $enTete->setCellValueByColumnAndRow(7, $rowEnTete, $tpsSup);

    $rowEnTete+=1;


  }
}

$enTete->setCellValue("B4", $date);
$objPHPExcel->setActiveSheetIndex(0);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->setIncludeCharts(TRUE);
$objWriter->save('../lib/PHPExcel/files/UBR-'.$date.'.xlsx');

//Copy du fichier vers //SRVDC/DONNEES/ADMINISTRATION/UBR/
$srcfile='../lib/PHPExcel/files/UBR-'.$date.'.xlsx';
$dstfile = '//SRVDC/DONNEES/ADMINISTRATION/UBR/UBR-'.$date.'.xlsx';
copy($srcfile, $dstfile);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="UBR-'.$date.'.xlsx"');
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
