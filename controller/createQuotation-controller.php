<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



// Rendre votre modèle accessible
include '../models/quotation-model.php';
$oQuotations = new QUOTATION($db);
$quotation=$oQuotations->getQuotationList($_GET['id_quotation']);


// Rendre votre modèle accessible
include '../models/lstContact-model.php';
$lstCustomer = new ContactModel($db);
$ref_customer=$lstCustomer->getAllref_customer();



$quotationlistArray = array();
$quotationlist = array();

parse_str($quotation['quotationlist'], $quotationlistArray); // on récupère les quotationlist dans un array
if ($quotationlistArray) {
  foreach ($quotationlistArray as $key => $value) {            // on les range dans un sub array par ligne
    $name=explode("_", $key);
    $quotationlistNumber[$name[1]][$name[2]]=$value;
  }


  $index = "a";
  foreach($quotationlistNumber as $value)                     //on change l'index en lettre incrémental
  {
    $quotationlist[$index] = $value;
    $index++;
  }
}



//adresse
$i=0;
if (isset($quotation['entreprise'])) {
  $quotation[$i]=$quotation['entreprise'];
  $i++;
}
if (isset($quotation['billing_rue1'])) {
  $quotation[$i]=$quotation['billing_rue1'];
  $i++;
}
if (isset($quotation['billing_rue2'])) {
  $quotation[$i]=$quotation['billing_rue2'];
  $i++;
}
if (isset($quotation['billing_ville'])) {
  $quotation[$i]=$quotation['billing_ville'];
  $i++;
}
if (isset($quotation['billing_pays'])) {
  $quotation[$i]=$quotation['billing_pays'];
  $i++;
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


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

$objPHPExcel = new Spreadsheet();
$objReader = IOFactory::createReader('Xlsx');
$objReader->setIncludeCharts(TRUE);


function copyRange( Worksheet $sheet, $srcRange, $dstCell, Worksheet $destSheet = null) {
  if( !isset($destSheet)) {
    $destSheet = $sheet;
  }
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
      $destSheet->setCellValue($dstCell, $cell->getValue());
      $destSheet->duplicateStyle($style, $dstCell);

      // Set width of column, but only once per row
      if ($rowCount === 0) {
        $w = $sheet->getColumnDimensionByColumn($col)->getWidth();
        $destSheet->getColumnDimensionByColumn ($destColumnStart + $colCount)->setAutoSize(false);
        $destSheet->getColumnDimensionByColumn ($destColumnStart + $colCount)->setWidth($w);
      }

      $colCount++;
    }

    $h = $sheet->getRowDimension($row)->getRowHeight();
    $destSheet->getRowDimension($destRowStart + $rowCount)->setRowHeight($h);

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
      $destSheet->mergeCells($merge);
    }
  }
}


$styleBorder = array(
  'borders' => array(
    'outline' => array(
      'borderStyle' => Border::BORDER_THICK,
    )
  )
);
$styleTitre = array(
  'font'  => array(
    'bold'  => true
  ),
  'borders' => array(
    'bottom' => array(
      'borderStyle' => Border::BORDER_THIN,
      'color' => array('rgb' => '888888')
    )
  ),
  'fill' => array(
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'F2F2F2')
  ),
);

//nom du fichier excel d'UBR
$objPHPExcel = $objReader->load("../templates/Quotation.xlsm");

$page=$objPHPExcel->getSheetByName('Quotation');
//on cache la fenetre template
$page->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VISIBLE);

$pageComments=$objPHPExcel->getSheetByName('Comments');





$val2Xls = array(

  'F5' => date('y', strtotime($quotation['creation_date'])).'-'.sprintf('%05d',$quotation['id_quotation']),
  'F6' => $quotation['rev'],
  'F7'=> date("Y-m-d"),
  'F9' => $quotation['rfq'],

  'B6'=> (isset($adresse2[0])?$adresse2[0]:''),
  'B7'=> (isset($adresse2[1])?$adresse2[1]:''),
  'B8'=> (isset($adresse2[2])?$adresse2[2]:''),
  'B9'=> (isset($adresse2[3])?$adresse2[3]:''),
  'B10'=> (isset($adresse2[4])?$adresse2[4]:''),
  'B11'=> (isset($adresse2[5])?$adresse2[5]:'')

);

//Pour chaque element du tableau associatif, on update les cellules Excel
foreach ($val2Xls as $key => $value) {
  $page->setCellValue($key, $value);
}



$row = 17;

//pour chaque split
foreach ($quotationlist as $key => $value) {

  if ($value['type']=="title") {
    $row++;
    $page->setCellValueByColumnAndRow(1+1, $row, $value['description']);
    $page->getStyle('B'.$row.':G'.$row)->applyFromArray($styleTitre);
  }
  elseif ($value['type']=="comment") {
    $page->setCellValueByColumnAndRow(1+1, $row, $value['comments']);
  }
  elseif ($value['type']=="code") {
    $page->setCellValueByColumnAndRow(1+0, $row, $value['prodCode']);
    $page->setCellValueByColumnAndRow(1+1, $row, $value['description']);
    $page->setCellValueByColumnAndRow(1+4, $row, $value['unit']);
    $page->setCellValueByColumnAndRow(1+5, $row, $value['price']);
    $page->setCellValueByColumnAndRow(1+6, $row, $value['unit']*$value['price']);
    $row++;
    $page->setCellValueByColumnAndRow(1+1, $row, $value['comments']);
  }


  $row++;


}





$row++;


//Commentaire fin de page
if ($quotation['lang']==0) {  //FR
  $cell = $pageComments->getCellByColumnAndRow(11,5);
  $page->setCellValue('A1', $cell->getCalculatedValue());

  $cell = $pageComments->getCellByColumnAndRow(11,6);
  $page->setCellValue('A2', $cell->getCalculatedValue());
}
else {                        //US
  $cell = $pageComments->getCellByColumnAndRow(11,10);
  $page->setCellValue('A1', $cell->getCalculatedValue());
  $cell = $pageComments->getCellByColumnAndRow(11,11);
  $page->setCellValue('A2', $cell->getCalculatedValue());
}

copyRange(  $page, 'A1:G2', Coordinate::stringFromColumnIndex(1).$row);

//prix total -- a modifier
$page->setCellValueByColumnAndRow(1+6, $row,'=sum(F17:F'.($row-1).')');



$page->getPageSetup()->setPrintArea('A1:G'.$row);




/*

//on cache les fenetres template
$objPHPExcel->getSheetByName('Quotation')
->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
*/


$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
//$objWriter->setIncludeCharts(TRUE);
$file='../temp/Quotation-'.date('y', strtotime($quotation['creation_date'])).'-'.sprintf('%05d',$quotation['id_quotation']).'.xlsm';
$objWriter->save($file);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
header('Content-Disposition: attachment;filename="Quotation-'.date('y', strtotime($quotation['creation_date'])).'-'.sprintf('%05d',$quotation['id_quotation']).'.xlsm"');
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
