<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['type']) OR !isset($_GET['idposte']) OR !isset($_GET['idElement']))	{
  echo 'Missing Something';
  exit;
}


// Rendre votre modèle accessible
include '../models/poste-model.php';
// Création d'une instance
$oPoste = new PosteModel($db, $_GET['idposte']);
$poste=$oPoste->getPoste();

// Rendre votre modèle accessible
include '../models/calibration-model.php';
$oCalibration = new CalibrationModel($db);




/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Paris');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include \PhpOffice\PhpSpreadsheet\Spreadsheet */
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;


$objPHPExcel = new Spreadsheet();
// Create new \PhpOffice\PhpSpreadsheet\Spreadsheet object
$objReader = IOFactory::createReader('Xlsx');
$objReader->setIncludeCharts(TRUE);


//Fonction pour enlever les 0 après la virgule
function enleverZero($chiffre){
  if(strrchr($chiffre,".")!==false){//si le chiffre n'a pas de point (il faut savoir qu'un nombre envoyé à cette fonction, par exemple: 420.00, sera retourné 420, donc pour ne pas enlever le zéro de la fin, qui fausserait l'affichage, on demande si il existe un . dans $chiffre avec la fonction strrchr(), qui renvoiera "false" si il y a pas de .
    $strlen=strlen($chiffre);//mettre la longueur de la chaine dans la variable $strlen permet de ne pas perdre le total de strlen() à chaque fois qu'on enlève un 0 final...
    for($i=1;$i<=$strlen;$i++){ // strlen nous permet de compter combien il y a de numéro
      if(substr($chiffre,-1)=="0") {//substr-1 nous permet de prendre le dernier chiffre, si
        $chiffre = substr($chiffre,0,-1);//si c'est un 0, on l'enlève
      }
      if($i==$strlen){// en fin, si tous les numéros sont passez au peigne fin, on retourne le chiffre sans les zéros
        // on vérifie que le résultat n'est, exemple 14. ou 14,
        if(substr($chiffre,-1)=="." OR substr($chiffre,-1)==",") {
          $chiffre = substr($chiffre,0,-1);//si c'est une virgule ou un point, on l'enlève
        }
        return $chiffre;// en fin, on retourne le résultat
      }
    }
  } else {
    return $chiffre;
  }
}


function copyRange( Worksheet $sheet, $srcRange, $dstCell) {
  // Validate source range. Examples: A2:A3, A2:AB2, A27:B100
  if( !preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $srcRange, $srcRangeMatch) ) {
    // Wrong source range
    return;
  }
  // Validate destination cell. Examples: A2, AB3, A27
  if( !preg_match('/^([A-Z]+)(\d+)$/', $dstCell, $destCellMatch) ) {
    // Wrong destination cell
    return;
  }

  $srcColumnStart = $srcRangeMatch[1];
  $srcRowStart = $srcRangeMatch[2];
  $srcColumnEnd = $srcRangeMatch[3];
  $srcRowEnd = $srcRangeMatch[4];

  $destColumnStart = $destCellMatch[1];
  $destRowStart = $destCellMatch[2];

  // For looping purposes we need to convert the indexes instead
  // Note: We need to subtract 1 since column are 0-based and not 1-based like this method acts.

  $srcColumnStart = Coordinate::columnIndexFromString($srcColumnStart) ;
  $srcColumnEnd = Coordinate::columnIndexFromString($srcColumnEnd);
  $destColumnStart = Coordinate::columnIndexFromString($destColumnStart);

  $rowCount = 0;
  for ($row = $srcRowStart; $row <= $srcRowEnd; $row++) {
    $colCount = 0;
    for ($col = $srcColumnStart; $col <= $srcColumnEnd; $col++) {
      $cell = $sheet->getCellByColumnAndRow($col, $row);
      $style = $sheet->getStyleByColumnAndRow($col, $row);
      $dstCell = Coordinate::stringFromColumnIndex($destColumnStart + $colCount) . (string)($destRowStart + $rowCount);
      $sheet->setCellValue($dstCell, $cell->getValue());
      $sheet->duplicateStyle($style, $dstCell);

      // Set width of column, but only once per row
      if ($rowCount === 0) {
        $w = $sheet->getColumnDimensionByColumn($col)->getWidth();
        $sheet->getColumnDimensionByColumn ($destColumnStart + $colCount)->setAutoSize(false);
        $sheet->getColumnDimensionByColumn ($destColumnStart + $colCount)->setWidth($w);
      }

      $colCount++;
    }

    $h = $sheet->getRowDimension($row)->getRowHeight();
    $sheet->getRowDimension($destRowStart + $rowCount)->setRowHeight($h);

    $rowCount++;
  }

  foreach ($sheet->getMergeCells() as $mergeCell) {
    $mc = explode(":", $mergeCell);
    $mergeColSrcStart = Coordinate::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[0]));
    $mergeColSrcEnd = Coordinate::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[1]));
    $mergeRowSrcStart = ((int)preg_replace("/[A-Z]*/", "", $mc[0]));
    $mergeRowSrcEnd = ((int)preg_replace("/[A-Z]*/", "", $mc[1]));

    $relativeColStart = $mergeColSrcStart - $srcColumnStart;
    $relativeColEnd = $mergeColSrcEnd - $srcColumnStart;
    $relativeRowStart = $mergeRowSrcStart - $srcRowStart;
    $relativeRowEnd = $mergeRowSrcEnd - $srcRowStart;

    if (0 <= $mergeRowSrcStart && $mergeRowSrcStart >= $srcRowStart && $mergeRowSrcEnd <= $srcRowEnd) {
      $targetColStart = Coordinate::stringFromColumnIndex($destColumnStart + $relativeColStart);
      $targetColEnd = Coordinate::stringFromColumnIndex($destColumnStart + $relativeColEnd);
      $targetRowStart = $destRowStart + $relativeRowStart;
      $targetRowEnd = $destRowStart + $relativeRowEnd;

      $merge = (string)$targetColStart . (string)($targetRowStart) . ":" . (string)$targetColEnd . (string)($targetRowEnd);
      //Merge target cells
      $sheet->mergeCells($merge);
    }
  }
}

$styleCell = array(
  'void' => array(
    'borders' => array(
      'diagonalDirection' => Borders::DIAGONAL_UP,
      'diagonal' => array(
        'borderStyle' => Border::BORDER_THIN,
        'color' => array('rgb' => '888888')
      )
    ),
    'font'  => array(
      'italic'  => false,
      'color' => array('rgb' => '000000')
    )
  ),
  'running' => array(
    'borders' => array(
      'diagonalDirection' => Borders::DIAGONAL_NONE
    ),
    'font'  => array(
      'italic'  => true,
      'color' => array('rgb' => '0000CC'),
      'size'  => 8
    )
  ),
  'checked' => array(
    'borders' => array(
      'diagonalDirection' => Borders::DIAGONAL_NONE
    ),
    'font'  => array(
      'italic'  => false,
      'color' => array('rgb' => '000000')
    )
  ),
  'unchecked' => array(
    'borders' => array(
      'diagonalDirection' => Borders::DIAGONAL_NONE
    ),
    'font'  => array(
      'italic'  => true,
      'color' => array('rgb' => '888888'),
      'size'  => 8
    )
  ),
);




$lstCalib = $oCalibration->getCalibrationList($_GET['type']);




If ($lstCalib['id_calibration_type']==1)	{

  include '../models/lstIndTemp-model.php';
  $oIndTemp = new IndTempModel($db);
  $ind_temp_top=$oIndTemp->getIndTemp($poste['id_ind_temp_top']);
  $ind_temp_strap=$oIndTemp->getIndTemp($poste['id_ind_temp_strap']);
  $ind_temp_bot=$oIndTemp->getIndTemp($poste['id_ind_temp_bot']);

  $objPHPExcel = $objReader->load("../templates/cal_TempLine.xlsm");


  $enTete=$objPHPExcel->getSheet(0);


  $val2Xls = array(

    'A5'=> $poste['machine'],
    'K5'=> date("Y-m-d"),
    'M5'=> '',
    'C7'=> $ind_temp_top['ind_temp'].' - '.$ind_temp_strap['ind_temp'].' - '.$ind_temp_bot['ind_temp'].' - '.'???',

    'O2'=> 'Temp. Line - '.$poste['machine'],
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  $filename="TempLine_".$poste['machine'];



}
else {
  echo "???";
  exit;
}


//define first sheet as opener
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->setIncludeCharts(TRUE);



$file='../temp/'.$filename.'.xlsm';
$objWriter->save($file);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

readfile(str_replace("/","\\",$file));

exit;

?>
