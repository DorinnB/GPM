<?php
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
  $adresse[$i]='entreprise';
  $i++;
}
if (isset($split['billing_rue1'])) {
  $adresse[$i]='billing_rue1';
  $i++;
}
if (isset($split['billing_rue2'])) {
  $adresse[$i]='billing_rue2';
  $i++;
}
if (isset($split['billing_ville'])) {
  $adresse[$i]='billing_ville';
  $i++;
}
if (isset($split['billing_pays'])) {
  $adresse[$i]='billing_pays';
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

/** Include PHPExcel */
require_once '../lib/PHPExcel/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setIncludeCharts(TRUE);


$styleBorder = array(
  'borders' => array(
    'outline' => array(
      'style' => PHPExcel_Style_Border::BORDER_THICK
    )
  )
);
$styleSplit = array(
  'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'A0A0A0')
  ),
  'font'  => array(
       'bold'  => true
   )
);

//nom du fichier excel d'UBR
$objPHPExcel = $objReader->load("../lib/PHPExcel/templates/Invoice.xlsx");


foreach (array('InvoiceFR', 'InvoiceUSA') as &$value) {
  $page=$objPHPExcel->getSheetByName($value);


  $row = 14; // 1-based index
  $nCode=1; //code "Others"


  $val2Xls = array(

    'D6' => '',
    'D7' => $split['VAT'],
    'D8' =>  $split['po_number'],
    'D9' =>  $split['MRSASRef'],
    'D10' => $split['customer'].'-'.$split['job'],
    'G9'=> date("Y-m-d"),

    'F1'=> (isset($adresse[0])?$split[$adresse[0]]:''),
    'F2'=> (isset($adresse[1])?$split[$adresse[1]]:''),
    'F3'=> (isset($adresse[2])?$split[$adresse[2]]:''),
    'F4'=> (isset($adresse[3])?$split[$adresse[3]]:''),
    'F5'=> (isset($adresse[4])?$split[$adresse[4]]:'')

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $page->setCellValue($key, $value);
  }


  //style monetaire
  $currency=($split['invoice_currency']==0)?'€':'$';
  $currencyDollar=($split['invoice_currency']==0)?'':' $';
  $currencyEuro=($split['invoice_currency']==0)?' €':'';

  $page->getStyle("G14")->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);


  //pour chaque split
  foreach ($splits as $key => $value) {

    //on ecrit l'intitulé du split
    $page->setCellValueByColumnAndRow(1, $row, $value['split'].' - '.$value['test_type_cust']);
    $page->getStyle('B'.$row.':D'.$row)->applyFromArray($styleSplit);

    $intituleSplit=$row;
    $row++;

    $nbLines=0; //comptage s'il y a des invoicelines dans ce split

    //pour chaque invoiceLine du split
    foreach ($oInvoices->getInvoiceListSplit($value['id_tbljob']) as $invoicelines) {
      //s'ily a une une quantité
      if ($invoicelines['qteUser']>0 OR $invoicelines['qteGPM']>0) {

        if ($invoicelines['prodCode']=="") {
          $code="O-".$nCode;
          $nCode++;
        }
        else {
          $code=$invoicelines['prodCode']."-".$invoicelines['OpnCode'];
        }

        $page->setCellValueByColumnAndRow(0, $row, $code);
        $page->setCellValueByColumnAndRow(1, $row, $invoicelines['pricingList']);
        $page->setCellValueByColumnAndRow(4, $row, ($invoicelines['qteUser']=="")?$invoicelines['qteGPM']:$invoicelines['qteUser']);
        $page->setCellValueByColumnAndRow(5, $row, $invoicelines['priceUnit']);
        $page->setCellValueByColumnAndRow(6, $row, (($invoicelines['qteUser']=="")?$invoicelines['qteGPM']:$invoicelines['qteUser'])*$invoicelines['priceUnit']);

        $page->getStyle("F".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);
        $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);

        $row++;
        $nbLines++;
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
    if ($invoicelines['qteUser']>0 OR isset($_GET['UBR'])) {

      if ($invoicelines['prodCode']=="") {
        $code="O-".$nCode;
        $nCode++;
      }
      else {
        $code=$invoicelines['prodCode']."-".$invoicelines['OpnCode'];
      }

      $page->setCellValueByColumnAndRow(0, $row, $code);
      $page->setCellValueByColumnAndRow(1, $row, $invoicelines['pricingList']);
      $page->setCellValueByColumnAndRow(4, $row, $invoicelines['qteUser']);
      $page->setCellValueByColumnAndRow(5, $row, $invoicelines['priceUnit']);
      $page->setCellValueByColumnAndRow(6, $row, $invoicelines['qteUser']*$invoicelines['priceUnit']);

      $page->getStyle("F".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);
      $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);

      $row++;
    }
  }



  $row++;
  $row++;

  //Bloc total
  if (substr($split['VAT'],0,2)=='FR') {  //si client francais=> TVA

    $page->setCellValueByColumnAndRow(4, $row, $page->getCellByColumnAndRow(8, 4)->getValue());
    $page->getStyleByColumnAndRow(4, $row)->getFont()->setBold( true );
    $page->getStyleByColumnAndRow(4, $row)->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $page->setCellValueByColumnAndRow(6, $row,'=sum(G13:G'.($row-1).')');
    $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);

    $row++;
    $row++;

    $page->setCellValueByColumnAndRow(4, $row, $page->getCellByColumnAndRow(8, 5)->getValue());
    $page->getStyleByColumnAndRow(4, $row)->getFont()->setBold( true );
    $page->getStyleByColumnAndRow(4, $row)->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $page->setCellValueByColumnAndRow(6, $row,'=20%*G'.($row-2));
    $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);
    $row++;
    $row++;

    $page->setCellValueByColumnAndRow(4, $row, $page->getCellByColumnAndRow(8, 6)->getValue());
    $page->getStyleByColumnAndRow(4, $row)->getFont()->setBold( true );
    $page->getStyleByColumnAndRow(4, $row)->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $page->getStyle('E'.$row.':G'.$row)->applyFromArray($styleBorder);
    $page->setCellValueByColumnAndRow(6, $row,'=G'.($row-2).'+G'.($row-4));
    $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);
    $row++;
    $row++;

    $page->setCellValueByColumnAndRow(4, $row, $page->getCellByColumnAndRow(8, 7)->getValue());
    $page->getStyleByColumnAndRow(4, $row)->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $dt = date("Y-m-d");
    $page->setCellValueByColumnAndRow(6, $row, date( "Y-m-d", strtotime( "$dt +7 day" ) ));
    $row++;
  }
  else {    //pas de TVA

    $page->setCellValueByColumnAndRow(4, $row, $page->getCellByColumnAndRow(9, 4)->getValue());
    $page->getStyleByColumnAndRow(4, $row)->getFont()->setBold( true );
    $page->getStyleByColumnAndRow(4, $row)->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $page->setCellValueByColumnAndRow(6, $row,'=sum(G13:G'.($row-1).')');
    $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);

    $row++;
    $row++;


    $page->setCellValueByColumnAndRow(4, $row, $page->getCellByColumnAndRow(9, 6)->getValue());
    $page->getStyleByColumnAndRow(4, $row)->getFont()->setBold( true );
    $page->getStyleByColumnAndRow(4, $row)->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $page->getStyle('E'.$row.':G'.$row)->applyFromArray($styleBorder);
    $page->setCellValueByColumnAndRow(6, $row,'=G'.($row-2).'+G'.($row-4));
    $page->getStyle("G".$row)->getNumberFormat()->setFormatCode($currencyDollar.'### ##0.00'.$currencyEuro);
    $row++;
    $row++;

    $page->setCellValueByColumnAndRow(4, $row, $page->getCellByColumnAndRow(9, 7)->getValue());
    $page->getStyleByColumnAndRow(4, $row)->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $dt = date("Y-m-d");
    $page->setCellValueByColumnAndRow(6, $row, date( "Y-m-d", strtotime( "$dt +7 day" ) ));
    $row++;
  }

  if ($split['invoice_currency']==0) {  //si euro
    $page->setCellValueByColumnAndRow(0, $row, $page->getCellByColumnAndRow(8, 9)->getValue());
    $page->getStyleByColumnAndRow(0, $row)->getFont()->setSize(8);
    $row++;
    $page->setCellValueByColumnAndRow(0, $row, $page->getCellByColumnAndRow(8, 10)->getValue());
    $page->getStyleByColumnAndRow(0, $row)->getFont()->setSize(8);
  }
  else {  //si dollar
    $page->setCellValueByColumnAndRow(0, $row, $page->getCellByColumnAndRow(9, 9)->getValue());
    $page->getStyleByColumnAndRow(0, $row)->getFont()->setSize(8);
    $row++;
    $page->setCellValueByColumnAndRow(0, $row, $page->getCellByColumnAndRow(9, 10)->getValue());
    $page->getStyleByColumnAndRow(0, $row)->getFont()->setSize(8);
  }



}






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
  }
}




//on cache la fenetre template
$objPHPExcel->getSheetByName('Template')
->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

$objPHPExcel->setActiveSheetIndex(0);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->setIncludeCharts(TRUE);
$objWriter->save('../lib/PHPExcel/files/Invoice-'.$split['job'].'-'.$date.'.xlsx');


//type de sortie en fonction d'un affichage browser ou copy ubr
if (isset($_GET['UBR'])) {
  //Copy du fichier vers //SRVDC/DONNEES/ADMINISTRATION/UBR/
  $srcfile='../lib/PHPExcel/files/Invoice-'.$split['job'].'-'.$date.'.xlsx';
  $dstfile = '//SRVDC/DONNEES/ADMINISTRATION/UBR/Invoice-'.$split['job'].'-'.$date.'.xlsx';
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
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Invoice-'.$split['job'].'-'.$date.'.xlsx"');
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
}
