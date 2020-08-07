<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


if (!isset($_GET['dateStart'])) {
  exit;
}
if (!isset($_GET['dateEnd'])) {
  exit;
}

// Rendre votre modèle accessible
include '../models/invoice-model.php';
// Création d'une instance
$oInvoices = new InvoiceModel($db);
$payables=$oInvoices->getAllPayables($_GET['dateStart'],$_GET['dateEnd']);
$invoicables=$oInvoices->getAllUBR($_GET['dateStart'],$_GET['dateEnd']);
$invoices=$oInvoices->getAllInvoices($_GET['dateStart'],$_GET['dateEnd']);

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


$styleBorder = array(
  'borders' => array(
    'outline' => array(
      'borderStyle' => Border::BORDER_THICK,
    )
  )
);
$styleGras = array(
  'font'  => array(
    'bold'  => true
  )
);
$styleColor = array(
  'fill' => array(
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'A9A9A9')
  ),
);
//nom du fichier excel d'UBR
$objPHPExcel = $objReader->load("../templates/InvoicablePayables.xlsx");


$page=$objPHPExcel->getSheetByName('MRSAS PAYABLES');

$row = 4;
$startMonth=$row;

foreach ($payables as $key => $value) {
  $page->setCellValueByColumnAndRow(1+0, $row, $value['payable']);
  $page->setCellValueByColumnAndRow(1+1, $row, $value['date_due']);
  $page->setCellValueByColumnAndRow(1+2, $row, $value['date_invoice']);
  $page->setCellValueByColumnAndRow(1+3, $row, $value['invoice']);
  $page->setCellValueByColumnAndRow(1+4, $row, $value['TVA']);
  $page->setCellValueByColumnAndRow(1+5, $row, (($value['USD']>0)?$value['USD']*$value['taux']:$value['HT'])+$value['TVA']);
  $page->setCellValueByColumnAndRow(1+6, $row, $value['date_payable']);
  $page->setCellValueByColumnAndRow(1+7, $row, '');
  $page->setCellValueByColumnAndRow(1+8, $row, $value['payable_list']);
  $row++;

  if (!isset($payables[$key+1]) OR date("m",strtotime($payables[$key]['date_invoice']))!=date("m",strtotime($payables[$key+1]['date_invoice']))) {
    $page->setCellValueByColumnAndRow(1+0, $row, date("F",strtotime($value['date_invoice'])).' Payables');
    $page->setCellValueByColumnAndRow(1+3, $row, date("F",strtotime($value['date_invoice'])).' Payables');
    $page->setCellValueByColumnAndRow(1+4, $row, '=sum(E'.$startMonth.':E'.($row-1).')');
    $page->setCellValueByColumnAndRow(1+5, $row, '=sum(F'.$startMonth.':F'.($row-1).')');

    $page->getStyle('A'.$row.':I'.$row)->applyFromArray($styleGras);
    $row++;
    $page->getStyle('A'.$row.':I'.$row)->applyFromArray($styleColor);
    $row++;
    $startMonth=$row;
  }
}


$page->getPageSetup()->setPrintArea('A1:I'.$row);





$page2=$objPHPExcel->getSheetByName('MRSAS Invoicable');

$row = 7;
$startMonth=$row;

foreach ($invoicables as $key => $value) {

  $page2->setCellValueByColumnAndRow(1+0, $row, $value['entreprise']);
  $page2->setCellValueByColumnAndRow(1+1, $row, $value['customer']);
  $page2->setCellValueByColumnAndRow(1+2, $row, $value['job']);
  $page2->setCellValueByColumnAndRow(1+3, $row, ($value['invoice_currency']==1)?$value['ubrSubC']+$value['ubrMRSAS']:'');
  $page2->setCellValueByColumnAndRow(1+4, $row, '');
  $page2->setCellValueByColumnAndRow(1+5, $row, ($value['invoice_currency']==1)?'=D'.$row.'+E'.$row:'');
  $page2->setCellValueByColumnAndRow(1+6, $row, ($value['invoice_currency']==0)?$value['ubrSubC']+$value['ubrMRSAS']:'');
  $page2->setCellValueByColumnAndRow(1+7, $row, '');
  $page2->setCellValueByColumnAndRow(1+8, $row, ($value['invoice_currency']==0)?'=G'.$row.'+H'.$row:'');
  $page2->setCellValueByColumnAndRow(1+9, $row, ($value['invoice_currency']==0)?$value['ubroldSubC']+$value['ubroldMRSAS']:'');
  $page2->setCellValueByColumnAndRow(1+10, $row, ($value['invoice_currency']==1)?$value['ubroldSubC']+$value['ubroldMRSAS']:'');
  //$page2->setCellValueByColumnAndRow(1+11, $row, $value['date_ubr']);
  $row++;




  if (!isset($invoicables[$key+1]) OR date("m",strtotime($invoicables[$key]['date_ubr']))!=date("m",strtotime($invoicables[$key+1]['date_ubr']))) {
    $page2->setCellValueByColumnAndRow(1+0, $row, date("F",strtotime($value['date_ubr'])).' Invoicable');
    $page2->setCellValueByColumnAndRow(1+3, $row, date("F",strtotime($value['date_ubr'])).' Invoicable');
    $page2->setCellValueByColumnAndRow(1+3, $row, '=sum(D'.$startMonth.':D'.($row-1).')');
    $page2->setCellValueByColumnAndRow(1+4, $row, '=sum(E'.$startMonth.':E'.($row-1).')');
    $page2->setCellValueByColumnAndRow(1+5, $row, '=sum(F'.$startMonth.':F'.($row-1).')');
    $page2->setCellValueByColumnAndRow(1+6, $row, '=sum(G'.$startMonth.':G'.($row-1).')');
    $page2->setCellValueByColumnAndRow(1+7, $row, '=sum(H'.$startMonth.':H'.($row-1).')');
    $page2->setCellValueByColumnAndRow(1+8, $row, '=sum(I'.$startMonth.':I'.($row-1).')');
    $page2->setCellValueByColumnAndRow(1+9, $row, '=sum(J'.$startMonth.':J'.($row-1).')');
    $page2->setCellValueByColumnAndRow(1+10, $row, '=sum(K'.$startMonth.':K'.($row-1).')');

    $page2->getStyle('A'.$row.':K'.$row)->applyFromArray($styleGras);
    $row++;
    $page2->getStyle('A'.$row.':K'.$row)->applyFromArray($styleColor);
    $row++;
    $startMonth=$row;
  }
}


$page2->getPageSetup()->setPrintArea('A1:I'.$row);




$page3=$objPHPExcel->getSheetByName('Sales');

$row = 6;
$startMonth=$row;

foreach ($invoices as $key => $value) {
  $page3->setCellValueByColumnAndRow(1+0, $row, $value['entreprise']);
  $page3->setCellValueByColumnAndRow(1+1, $row, $value['customer'].'-'.$value['job']);
  $page3->setCellValueByColumnAndRow(1+2, $row, $value['inv_number']);
  $page3->setCellValueByColumnAndRow(1+3, $row, $value['inv_date']);
  $page3->setCellValueByColumnAndRow(1+4, $row, '=D'.$row.'+45');
  $page3->setCellValueByColumnAndRow(1+5, $row, ($value['invoice_currency']==1)?$value['inv_subc']+$value['inv_mrsas']:'');
  $page3->setCellValueByColumnAndRow(1+6, $row, ($value['invoice_currency']==1)?$value['inv_TVA']:'');
  $page3->setCellValueByColumnAndRow(1+7, $row, ($value['invoice_currency']==1)?'=F'.$row.'+G'.$row:'');
  $page3->setCellValueByColumnAndRow(1+8, $row, ($value['invoice_currency']==1)?($value['inv_subc']+$value['inv_mrsas'])*$value['USDRate']:'');
  $page3->setCellValueByColumnAndRow(1+9, $row, ($value['invoice_currency']==1)?$value['inv_TVA']*$value['USDRate']:'');
  $page3->setCellValueByColumnAndRow(1+10, $row, ($value['invoice_currency']==1)?'=I'.$row.'+J'.$row:'');
  $page3->setCellValueByColumnAndRow(1+11, $row, ($value['invoice_currency']==0)?$value['inv_subc']+$value['inv_mrsas']:'');
  $page3->setCellValueByColumnAndRow(1+12, $row, ($value['invoice_currency']==0)?$value['inv_TVA']:'');
  $page3->setCellValueByColumnAndRow(1+13, $row, ($value['invoice_currency']==0)?'=L'.$row.'+M'.$row:'');
  $page3->setCellValueByColumnAndRow(1+14, $row, $value['datepayement']);
  $page3->setCellValueByColumnAndRow(1+15, $row, (!isset($value['datepayement']))?'=I'.$row.'+J'.$row.'+L'.$row.'+M'.$row:'0');
  $row++;

  if (!isset($invoices[$key+1]) OR date("m",strtotime($invoices[$key]['inv_date']))!=date("m",strtotime($invoices[$key+1]['inv_date']))) {
    $page3->setCellValueByColumnAndRow(1+0, $row, date("F",strtotime($value['inv_date'])).' Sales');
    $page3->setCellValueByColumnAndRow(1+3, $row, date("F",strtotime($value['inv_date'])).' Sales');
    $page3->setCellValueByColumnAndRow(1+5, $row, '=sum(F'.$startMonth.':F'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+6, $row, '=sum(G'.$startMonth.':G'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+7, $row, '=sum(H'.$startMonth.':H'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+8, $row, '=sum(I'.$startMonth.':I'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+9, $row, '=sum(J'.$startMonth.':J'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+10, $row, '=sum(K'.$startMonth.':K'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+11, $row, '=sum(L'.$startMonth.':L'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+12, $row, '=sum(M'.$startMonth.':M'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+13, $row, '=sum(N'.$startMonth.':N'.($row-1).')');
    $page3->setCellValueByColumnAndRow(1+15, $row, '=sum(P'.$startMonth.':P'.($row-1).')');

    $page3->getStyle('A'.$row.':P'.$row)->applyFromArray($styleGras);
    $row++;
    $page3->getStyle('A'.$row.':P'.$row)->applyFromArray($styleColor);
    $row++;
    $startMonth=$row;
  }
}

$page3->getPageSetup()->setPrintArea('A1:I'.$row);



$page4=$objPHPExcel->getSheetByName('BALANCE MRI');

$row = 6;
$startMonth=$row;

foreach ($payables as $key => $value) {
  if (strtoupper($value['supplier'])=="MRI") {

    $page4->setCellValueByColumnAndRow(1+0, $row, $value['invoice']);
    $page4->setCellValueByColumnAndRow(1+1, $row, $value['date_invoice']);
    $page4->setCellValueByColumnAndRow(1+2, $row, $value['customer'].'-'.$value['job']);
    $page4->setCellValueByColumnAndRow(1+3, $row, $value['payable']);
    $page4->setCellValueByColumnAndRow(1+4, $row, $value['USD']);
    $page4->setCellValueByColumnAndRow(1+5, $row, $value['USD']*$value['taux']);
    $page4->setCellValueByColumnAndRow(1+6, $row, $value['date_payable']);
    $row++;
  }
  if (!isset($payables[$key+1]) OR date("m",strtotime($payables[$key]['date_invoice']))!=date("m",strtotime($payables[$key+1]['date_invoice']))) {
$startMonth=($startMonth==$row)?$row-1:$startMonth;

    $page4->setCellValueByColumnAndRow(1+3, $row, date("F",strtotime($value['date_invoice'])));
    $page4->setCellValueByColumnAndRow(1+4, $row, '=sum(E'.$startMonth.':E'.($row-1).')');
    $page4->setCellValueByColumnAndRow(1+5, $row, '=sum(F'.$startMonth.':F'.($row-1).')');

    $page4->getStyle('A'.$row.':G'.$row)->applyFromArray($styleGras);
    $row++;
    $page4->getStyle('A'.$row.':G'.$row)->applyFromArray($styleColor);
    $row++;
    $startMonth=$row;
  }

}


$page4->getPageSetup()->setPrintArea('A1:I'.$row);




$page5=$objPHPExcel->getSheetByName('BALANCE CTL');

$row = 6;
$startMonth=$row;

foreach ($payables as $key => $value) {
  if (strtoupper($value['supplier'])=="CTL") {

    $page5->setCellValueByColumnAndRow(1+0, $row, $value['invoice']);
    $page5->setCellValueByColumnAndRow(1+1, $row, $value['date_invoice']);
    $page5->setCellValueByColumnAndRow(1+2, $row, $value['customer'].'-'.$value['job']);
    $page5->setCellValueByColumnAndRow(1+3, $row, $value['payable']);
    $page5->setCellValueByColumnAndRow(1+4, $row, $value['USD']);
    $page5->setCellValueByColumnAndRow(1+5, $row, $value['USD']*$value['taux']);
    $page5->setCellValueByColumnAndRow(1+6, $row, $value['date_payable']);
    $row++;
  }
  if (!isset($payables[$key+1]) OR date("m",strtotime($payables[$key]['date_invoice']))!=date("m",strtotime($payables[$key+1]['date_invoice']))) {
$startMonth=($startMonth==$row)?$row-1:$startMonth;
    $page5->setCellValueByColumnAndRow(1+3, $row, date("F",strtotime($value['date_invoice'])));
    $page5->setCellValueByColumnAndRow(1+4, $row, '=sum(E'.$startMonth.':E'.($row-1).')');
    $page5->setCellValueByColumnAndRow(1+5, $row, '=sum(F'.$startMonth.':F'.($row-1).')');

    $page5->getStyle('A'.$row.':G'.$row)->applyFromArray($styleGras);
    $row++;
    $page5->getStyle('A'.$row.':G'.$row)->applyFromArray($styleColor);
    $row++;
    $startMonth=$row;
  }

}


$page5->getPageSetup()->setPrintArea('A1:I'.$row);

$objPHPExcel->setActiveSheetIndex(0);


$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
//$objWriter->setIncludeCharts(TRUE);
$file='../temp/InvoicePayables_'.$date.'.xlsx';
$objWriter->save($file);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="InvoicePayables_'.$date.'.xlsx');
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
