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
if (isset($quotation['nom'])) {
  $adresse[$i]=$quotation['prenom'].' '.$quotation['nom'];
  $i++;
}
if (isset($quotation['entreprise'])) {
  $adresse[$i]=$quotation['entreprise'];
  $i++;
}
if (isset($quotation['billing_rue1'])) {
  $adresse[$i]=$quotation['billing_rue1'];
  $i++;
}
if (isset($quotation['billing_rue2'])) {
  $adresse[$i]=$quotation['billing_rue2'];
  $i++;
}
if (isset($quotation['billing_ville'])) {
  $adresse[$i]=$quotation['billing_ville'];
  $i++;
}
if (isset($quotation['billing_pays'])) {
  $adresse[$i]=$quotation['billing_pays'];
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

function autoHeight($page, $col, $row, $value, $width=80) {
  //calcul de la hauteur max de la cellule de commentaire
  $maxheight = 0;
  //$width=80;  //valeur empirique lié à la largeur des colonnes
  $line = explode("\n", $value);


  foreach($line as $source) {
    $maxheight += intval((strlen($source) / $width) +1);
  }

  $page->setCellValueByColumnAndRow($col, $row, $value);
  $page->getRowDimension($row)->setRowHeight(count($line) * 13.75);

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
$styleComment = array(
  'font'  => array(
    'color' => array('rgb'=>'8193AB') //FF000000    //F2F2F2
  )
);
$styleCurrencyEURO = array(
  'numberFormat' => array(
    'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE
  )
);
$styleCurrencyUSD = array(
  'numberFormat' => array(
    'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE
  )
);


//nom du fichier excel d'UBR
$objPHPExcel = $objReader->load("../templates/Quotation.xlsm");

$pageComments=$objPHPExcel->getSheetByName('Comments');

//Commentaire fin de page
if ($quotation['lang']==0) {  //FR
  $page=$objPHPExcel->getSheetByName('QuotationFR');
  $objPHPExcel->getSheetByName('QuotationUS')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
  $comments1 = $pageComments->getCellByColumnAndRow(1,5);
  $comments2 = $pageComments->getCellByColumnAndRow(11,6);
  $CommentSubTotal = $pageComments->getCellByColumnAndRow(1,7);
  $CommentTotal = $pageComments->getCellByColumnAndRow(1,8);
}
else {                        //US
  $page=$objPHPExcel->getSheetByName('QuotationUS');
  $objPHPExcel->getSheetByName('QuotationFR')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
  $comments1 = $pageComments->getCellByColumnAndRow(1,14);
  $comments2 = $pageComments->getCellByColumnAndRow(11,15);
  $CommentSubTotal = $pageComments->getCellByColumnAndRow(1,16);
  $CommentTotal = $pageComments->getCellByColumnAndRow(1,17);
}

$page
->getStyle('F:G')
->getNumberFormat()
->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);



$val2Xls = array(

  'F10' => date('y', strtotime($quotation['creation_date'])).'-'.sprintf('%05d',$quotation['id_quotation']),
  'F11' => $quotation['ver'],
  'F13'=> date("Y-m-d"),
  'B16' => $quotation['rfq'],

  'B9'=> (isset($adresse[0])?$adresse[0]:''),
  'B10'=> (isset($adresse[1])?$adresse[1]:''),
  'B11'=> (isset($adresse[2])?$adresse[2]:''),
  'B12'=> (isset($adresse[3])?$adresse[3]:''),
  'B13'=> (isset($adresse[4])?$adresse[4]:''),
  'B14'=> (isset($adresse[5])?$adresse[5]:'')

);

//Pour chaque element du tableau associatif, on update les cellules Excel
foreach ($val2Xls as $key => $value) {
  $page->setCellValue($key, $value);
}



$row = 21;
$rowInitial=$row;
$rowSubTotal = $row;

//pour chaque split
foreach ($quotationlist as $key => $value) {

  if ($value['type']=="title") {
    $row++;
    $page->setCellValueByColumnAndRow(1+1, $row, $value['description']);
    $page->getStyle('B'.$row.':G'.$row)->applyFromArray($styleTitre);
  }
  elseif ($value['type']=="comment") {
    $page->setCellValueByColumnAndRow(1+1, $row, $value['comments']);
    $page->getStyle('B'.$row)->applyFromArray($styleComment);
  }
  elseif ($value['type']=="code") {
    $page->setCellValueByColumnAndRow(1+0, $row, $value['prodCode']);
    $page->setCellValueByColumnAndRow(1+1, $row, $value['description']);
    $page->setCellValueByColumnAndRow(1+4, $row, $value['unit']);
    $page->setCellValueByColumnAndRow(1+5, $row, $value['price']);
    $page->setCellValueByColumnAndRow(1+6, $row, $value['unit']*$value['price']);
    $page->setCellValueByColumnAndRow(1+7, $row, $value['unit']*$value['price']);
    $row++;
    //$page->setCellValueByColumnAndRow(1+1, $row, $value['comments']);
    $page->mergeCells('B'.$row.':D'.$row);
    $page->getStyle('B'.$row)->getAlignment()->setWrapText(true);

    autoHeight($page, 1+1, $row, $value['comments'], $width=60);

    $page->getStyle('B'.$row)->applyFromArray($styleComment);

  }
  elseif ($value['type']=="subTotal") {
    $row++;

    copyRange(  $page, 'A3:G3', Coordinate::stringFromColumnIndex(1).$row);
    $page->setCellValueByColumnAndRow(1+0, $row, '=$A$3');
    $page->setCellValueByColumnAndRow(1+6, $row,'=sum(G'.$rowSubTotal.':G'.($row-1).')');
    $rowSubTotal=$row+1;

  }

  $row++;


}





$row++;

//prix total
copyRange(  $page, 'A4:G4', Coordinate::stringFromColumnIndex(1).$row);
$page->setCellValueByColumnAndRow(1+0, $row, '=$A$4');
$page->setCellValueByColumnAndRow(1+6, $row,'=sum(H'.$rowInitial.':H'.($row-1).')');
$row++;

//commentaire additionnel
$page->setCellValueByColumnAndRow(1+0, $row, 'Notes');
$page->mergeCells('B'.$row.':G'.$row);
autoHeight($page, 1+1, $row, $quotation['endComments'], $width=60);
$page->getStyle('B'.$row)->getAlignment()->setWrapText(true);


$row++;

$row++;


copyRange(  $page, 'A1:G2', Coordinate::stringFromColumnIndex(1).$row);
$page->setCellValueByColumnAndRow(1+0, $row, '=$A$1');
$row++;
$page->setCellValueByColumnAndRow(1+0, $row, '=$A$2');
$row++;


//format nombre
if ($quotation['currency']==0) {  //EURO
  $page->getStyle('F'.$rowInitial.':G'.$row)->applyFromArray($styleCurrencyEURO);
}
else {                        //USD
  $page->getStyle('F'.$rowInitial.':G'.$row)->applyFromArray($styleCurrencyUSD);
}




$page->setSelectedCell('A18');
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
