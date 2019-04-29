<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/split-model.php';
// Création d'une instance
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);
$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include '../models/workflow.class.php';
// Création d'une instance
$oWorkflow = new WORKFLOW($db,$_GET['id_tbljob']);
$splits=$oWorkflow->getAllSplit();


// Rendre votre modèle accessible
include '../models/invoice-model.php';
// Création d'une instance
$oInvoices = new InvoiceModel($db);

include_once '../models/eprouvettes-model.php';
include_once '../models/eprouvette-model.php';

//adresse
$i=0;
if (isset($split['entreprise'])) {
  $adresse[$i]=$split['entreprise'];
  $i++;
}
if (isset($split['billing_rue1'])) {
  $adresse[$i]=$split['billing_rue1'];
  $i++;
}
if (isset($split['billing_rue2'])) {
  $adresse[$i]=$split['billing_rue2'];
  $i++;
}
if (isset($split['billing_ville'])) {
  $adresse[$i]=$split['billing_ville'];
  $i++;
}
if (isset($split['billing_pays'])) {
  $adresse[$i]=$split['billing_pays'];
  $i++;
}

$i=0;
if (isset($split['entreprise'])) {
  $adresse2[$i]=$split['entreprise'];
  $i++;
}
if (isset($split['nom'])) {
  $adresse2[$i]=$split['prenom'].' '.$split['nom'];
  $i++;
}
if (isset($split['rue1'])) {
  $adresse2[$i]=$split['rue1'];
  $i++;
}
if (isset($split['rue2'])) {
  $adresse2[$i]=$split['rue2'];
  $i++;
}
if (isset($split['ville'])) {
  $adresse2[$i]=$split['ville'];
  $i++;
}
if (isset($split['pays'])) {
  $adresse2[$i]=$split['pays'];
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
$styleSplit = array(
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
$objPHPExcel = $objReader->load("../templates/Invoice.xlsm");

//$page=$objPHPExcel->getSheetByName('Invoice'.$split['invoice_lang'].$split['invoice_currency']);
$page=$objPHPExcel->getSheetByName('Invoice0'.$split['invoice_currency']);
//on cache la fenetre template
$objPHPExcel->getSheetByName('Invoice0'.$split['invoice_currency'])
->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VISIBLE);


$val2Xls = array(

  'F3'=> date("Y-m-d"),

  'D2' => '[N° Facture]',

  'B6'=> (isset($adresse2[0])?$adresse2[0]:''),
  'B7'=> (isset($adresse2[1])?$adresse2[1]:''),
  'B8'=> (isset($adresse2[2])?$adresse2[2]:''),
  'B9'=> (isset($adresse2[3])?$adresse2[3]:''),
  'B10'=> (isset($adresse2[4])?$adresse2[4]:''),
  'B11'=> (isset($adresse2[5])?$adresse2[5]:''),

  'E6'=> (isset($adresse[0])?$adresse[0]:''),
  'E7'=> (isset($adresse[1])?$adresse[1]:''),
  'E8'=> (isset($adresse[2])?$adresse[2]:''),
  'E9'=> (isset($adresse[3])?$adresse[3]:''),
  'E10'=> (isset($adresse[4])?$adresse[4]:''),

  'C16' => $split['VAT'],
  'C17' =>  $split['po_number'],
  'G17' =>  $split['MRSASRef'],
  'G16' => $split['customer'].'-'.$split['job'],
  'C19' =>  $split['info_jobs_instruction']

);

//Pour chaque element du tableau associatif, on update les cellules Excel
foreach ($val2Xls as $key => $value) {
  $page->setCellValue($key, $value);
}



$row = 25;
$rowTemplate = 24;
$nCode=1; //code "Others"


//pour chaque split
foreach ($splits as $key => $value) {

  //on ecrit l'intitulé du split
  $page->setCellValueByColumnAndRow(1+1, $row, $value['split'].' - '.$value['test_type_cust']);
  $page->getStyle('B'.$row.':D'.$row)->applyFromArray($styleSplit);

  $intituleSplit=$row;
  $row++;

  $nbLines=0; //comptage s'il y a des invoicelines dans ce split

  //pour chaque invoiceLine du split
  foreach ($oInvoices->getInvoiceListSplit($value['id_tbljob']) as $invoicelines) {
    //s'ily a une une quantité
    if ($invoicelines['qteUser']>0 OR $invoicelines['qteGPM']>0) {


      copyRange($page, 'A'.$rowTemplate.':G'.$rowTemplate, Coordinate::stringFromColumnIndex(1).$row);


      $page->setCellValueByColumnAndRow(1+0, $row, $nCode);
      $page->setCellValueByColumnAndRow(1+1, $row, $invoicelines['pricingList']);
      $page->setCellValueByColumnAndRow(1+4, $row, ($invoicelines['qteUser']=="")?$invoicelines['qteGPM']:$invoicelines['qteUser']);
      $page->setCellValueByColumnAndRow(1+5, $row, $invoicelines['priceUnit']);
      $page->setCellValueByColumnAndRow(1+6, $row, '=E'.$row.'*F'.$row);

      $page->setCellValueByColumnAndRow(9, $row, $invoicelines['qteGPM']);

      /*$page->getStyle("F".$row)->getNumberFormat()->setFormatCode($currencyFormat);
      $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyFormat);
      */
      $row++;
      $nbLines++;
      $nCode++;
    }

  }


  if ($nbLines>0) { //1 ligne de separation avec le split suivant
    $row++;
  }
  else { //sinon on masque la ligne d'intitulé de split
    $page->getRowDimension($intituleSplit)->setVisible(false);
  }

}

//pour le job
//pour chaque invoiceLine du split
foreach ($oInvoices->getInvoiceListJob($_GET['id_tbljob']) as $invoicelines) {

  //s'ily a une une quantité ou si UBR
  if ($invoicelines['qteUser']>0) {

    copyRange($page, 'A'.$rowTemplate.':G'.$rowTemplate, Coordinate::stringFromColumnIndex(1).$row);

    $page->setCellValueByColumnAndRow(1+0, $row, $nCode);
    $page->setCellValueByColumnAndRow(1+1, $row, $invoicelines['pricingList']);
    $page->setCellValueByColumnAndRow(1+4, $row, $invoicelines['qteUser']);
    $page->setCellValueByColumnAndRow(1+5, $row, $invoicelines['priceUnit']);
    $page->setCellValueByColumnAndRow(1+6, $row, '=E'.$row.'*F'.$row);

    /*   $page->getStyle("F".$row)->getNumberFormat()->setFormatCode($currencyFormat);
    $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyFormat);
    */
    $row++;
    $nCode++;
  }
}



$row++;
$row++;


//si possible fin de page
$row++;
$row++;


if (substr($split['VAT'],0,2)=='FR') {  //si client francais=> TVA

  copyRange($page, 'O3:U7', Coordinate::stringFromColumnIndex(1).$row);
  $page->setCellValueByColumnAndRow(1+6, $row,'=sum(G24:G'.($row-1).')');
  $page->setCellValueByColumnAndRow(1+6, $row+1,'=G'.$row.'*20%');
  $page->setCellValueByColumnAndRow(1+6, $row+2,'=G'.$row.'+G'.($row+1));

  $dt = date("Y-m-d");
  $page->setCellValueByColumnAndRow(1+6, $row+4, date( "Y-m-d", strtotime( "$dt +45 day" ) ));
  $row=$row+5;
}
else {    //pas de TVA

  copyRange($page, 'O8:U10', Coordinate::stringFromColumnIndex(1).$row);
  $page->setCellValueByColumnAndRow(1+6, $row,'=sum(G24:G'.($row-1).')');

  $dt = date("Y-m-d");
  $page->setCellValueByColumnAndRow(1+6, $row+2, date( "Y-m-d", strtotime( "$dt +45 day" ) ));
  $row=$row+3;
}



$page->getPageSetup()->setPrintArea('A1:G'.$row);







//si ubr, on affiche un 'résumé' des données du job
if (isset($_GET['UBR'])) {
  foreach ($splits as $row) {
    //on recupere la liste des eprouvettes de ce split
    $oEprouvettes = new LstEprouvettesModel($db,$row['id_tbljob']);
    $ep=$oEprouvettes->getAllEprouvettes();

    //on se crée un tableau $ep[$k] des informations
    for($k=0;$k < count($ep);$k++)	{
      $oEprouvette = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
      $ep[$k]=$oEprouvette->getTest();
    }

    //on crée un nouvel onglet du nom du split
    $newSheet = $objPHPExcel->getSheetByName('Template')->copy();
    $newSheet->setTitle($row['split'].'-'.$row['test_type_abbr']);
    $objPHPExcel->addSheet($newSheet);

    $tpsSup=0;
    $row = 0; // 1-based index
    $col = 3;
    //pour chaque eprouvette, on écrit les données de celle ci
    foreach ($ep as $key => $value) {
      //copy des styles des colonnes
      for ($row = 5; $row <= 17; $row++) {
        $style = $newSheet->getStyleByColumnAndRow(1+3, $row);
        $dstCell = Coordinate::stringFromColumnIndex($col) . (string)($row);
        $newSheet->duplicateStyle($style, $dstCell);
      }

      $newSheet->setCellValueByColumnAndRow(1+$col, 5, $value['prefixe']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 6, $value['nom_eprouvette']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 7, $value['n_essai']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 8, $value['n_fichier']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 9, $value['date']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 10, $value['c_temperature']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 11, $value['c_frequence']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 12, ($value['Cycle_STL']==0)?"":$value['Cycle_STL']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 13, $value['c_frequence_STL']);
      $newSheet->setCellValueByColumnAndRow(1+$col, 14, $value['Cycle_final']);

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
        $newSheet->setCellValueByColumnAndRow(1+$col, 15, $tpsEssai);
        $newSheet->setCellValueByColumnAndRow(1+$col, 16, $tpsSupSplit);
        $tpsSup+=$tpsSupSplit;
      }
      $col++;
    }
  }
}



//on cache les fenetres template
$objPHPExcel->getSheetByName('Invoice')
->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

$objPHPExcel->getSheetByName('Template')
->setSheetState(Worksheet::SHEETSTATE_HIDDEN);



$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
//$objWriter->setIncludeCharts(TRUE);
$file='../temp/Invoice-'.$split['job'].'-'.$date.'.xlsm';
$objWriter->save($file);


//type de sortie en fonction d'un affichage browser ou copy ubr
if (isset($_GET['UBR'])) {
  //Copy du fichier vers server
  $srcfile='../temp/Invoice-'.$split['job'].'-'.$date.'.xlsm';
  $dstfile = $ini['PATH_UBR'].'Invoice-'.$split['job'].'-'.$date.'.xlsm';
  copy($srcfile, $dstfile);

  echo '
  <script language="javascript" type="text/javascript">
  window.open("","_parent","");
  window.close();
  </script>';
  exit;
}
else {
  // Redirect output to a client’s web browser (Excel2007)
  header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
  header('Content-Disposition: attachment;filename="Invoice-'.$split['job'].'-'.$date.'.xlsm"');
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
}
