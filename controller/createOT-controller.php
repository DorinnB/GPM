<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_tbljob']) OR $_GET['id_tbljob']=="")	{
  exit();

}



// Rendre votre modèle accessible
include '../models/split-model.php';

$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);

$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include '../models/eprouvettes-model.php';
include '../models/eprouvette-model.php';


$oEprouvettes = new LstEprouvettesModel($db,$_GET['id_tbljob']);
$ep=$oEprouvettes->getAllEprouvettes();

for($k=0;$k < count($ep);$k++)	{
  $oEprouvette = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
  $ep[$k]=$oEprouvette->getTest();



  $dimDenomination=$oEprouvette->dimensions($ep[$k]['id_dessin_type']);

  //suppression des dimensions null
  foreach ($dimDenomination as $index => $data) {

    if ($data=='') {
      unset($dimDenomination[$index]);
    }
  }

  $ep[$k]['denomination'] =$dimDenomination;



  //groupement du nom du job avec ou sans indice
  if (isset($ep[$k]['split']))
  $jobcomplet= $ep[$k]['customer'].'-'.$ep[$k]['job'].'-'.$ep[$k]['split'];
  else
  $jobcomplet= $ep[$k]['customer'].'-'.$ep[$k]['job'];


  //recherche si le split a été fait avec un coil ou un four
  if (isset($ep[$k]['type_chauffage']) AND $ep[$k]['type_chauffage']=="Coil")
  $coil="x";
  if (isset($ep[$k]['type_chauffage']) AND $ep[$k]['type_chauffage']=="Four")
  $four="x";

}



//var_dump($split);
//var_dump($ep[1]);

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Paris');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include \PhpOffice\PhpSpreadsheet\Spreadsheet */
require '../vendor/autoload.php';


// Create new \PhpOffice\PhpSpreadsheet\Spreadsheet object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$objReader->setIncludeCharts(TRUE);



//var_dump($split);


If ($split['test_type_abbr']=="PS")	{

  $objPHPExcel = $objReader->load("../templates/OT_PS.xlsx");

  $page=$objPHPExcel->getActiveSheet();


  $val2Xls = array(

    'L2' => $jobcomplet,
    'C6'=> $split['tbljob_frequence'],
    'G4'=> $split['dessin'],
    'G5'=> $split['ref_matiere'],
    'G6'=> $split['waveform'],
    'K4'=> date("Y-m-d"),
    'K5'=> $split['nomCreateur'],
    'K6'=> $split['comCheckeur'],
    'C24'=> $split['c_unite'],
    'C25'=> $split['c_unite'],

    'G9'=> (($split['other_1']==0)?'No':$split['other_1']),
    'J9'=> $split['specification'],

    'D47'=> $split['tbljob_instruction'],
    'D52'=> $split['info_jobs_instruction']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $page->setCellValue($key, $value);
  }

  //titre des lignes PV
  $page->setCellValueByColumnAndRow(1+1, 26, $split['c_type_1']);
  $page->setCellValueByColumnAndRow(1+2, 26, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $page->setCellValueByColumnAndRow(1+1, 27, $split['c_type_2']);
  $page->setCellValueByColumnAndRow(1+2, 27, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");



  $row = 0; // 1-based index
  $col = 3;
  foreach ($ep as $key => $value) {

    $page->setCellValueByColumnAndRow(1+$col, 12, $value['prefixe'].' ');
    $page->setCellValueByColumnAndRow(1+$col, 13, $value['nom_eprouvette'].' ');

    $page->setCellValueByColumnAndRow(1+$col, 14, $value['n_essai']);
    $page->setCellValueByColumnAndRow(1+$col, 15, $value['n_fichier']);
    $page->setCellValueByColumnAndRow(1+$col, 16, $value['operateur']);
    $page->setCellValueByColumnAndRow(1+$col, 17, $value['machine']);
    $page->setCellValueByColumnAndRow(1+$col, 18, $value['date']);
    $page->setCellValueByColumnAndRow(1+$col, 19, $value['c_temperature']);
    $page->setCellValueByColumnAndRow(1+$col, 20, $value['c_frequence']);
    $page->setCellValueByColumnAndRow(1+$col, 21, $value['c_cycle_STL']);
    $page->setCellValueByColumnAndRow(1+$col, 22, $value['c_frequence_STL']);

    if (isset($value['denomination']['denomination_1'])) {
      $page->setCellValueByColumnAndRow(1+$col, 23, $value['dim1']);
      $page->setCellValueByColumnAndRow(1+0, 23, $value['denomination']['denomination_1']);
    }
    else {
      $page->getRowDimension(23)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $page->setCellValueByColumnAndRow(1+$col, 24, $value['dim2']);
      $page->setCellValueByColumnAndRow(1+0, 24, $value['denomination']['denomination_2']);
    }
    else {
      $page->getRowDimension(24)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $page->setCellValueByColumnAndRow(1+$col, 25, $value['dim3']);
      $page->setCellValueByColumnAndRow(1+0, 25, $value['denomination']['denomination_3']);
    }
    else {
      $page->getRowDimension(25)->setVisible(FALSE);
    }

    $page->setCellValueByColumnAndRow(1+$col, 26, $value['c_type_1_val']);
    $page->setCellValueByColumnAndRow(1+$col, 27, $value['c_type_2_val']);

    $oEprouvette->niveaumaxmin($split['c_type_1'], $split['c_type_2'], $value['c_type_1_val'], $value['c_type_2_val']);
    $page->setCellValueByColumnAndRow(1+$col, 28, $oEprouvette->MAX());
    $page->setCellValueByColumnAndRow(1+$col, 29, $oEprouvette->MIN());


    $page->setCellValueByColumnAndRow(1+$col, 30, $value['other_2']);
    $page->setCellValueByColumnAndRow(1+$col, 31, $value['runout']);

    $col++;
  }

  //on masque l'orientation 2 s'il n'y en a pas
  if ($split['other_1']==0) {
    for ($j=35; $j <=38 ; $j++) {
      $page->getRowDimension($j)->setVisible(FALSE);
    }
  }

  $colImprimable=ceil(count($ep)/10)*10+3;
  //zone d'impression
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colImprimable-1);
  $page->getPageSetup()->setPrintArea('A1:'.$colString.'56');




}
ElseIf ($split['final']=="1")	{

  $objPHPExcel = $objReader->load("../templates/OT_Default.xlsx");

  $page=$objPHPExcel->getSheetByName('OT');


  $val2Xls = array(

    'C2'=> $split['test_type_abbr']." Fatigue Test",
    'O2' => 'OT - '.$split['job'].'-'.$split['split'],

    'A5' => $jobcomplet.' ',
    'D5'=> $split['po_number'].' ',
    'G5'=> $split['ref_matiere'].' ',
    'I5'=> $split['dessin'].' ',
    'K5'=> $split['nomCreateur'].' ',
    'M5'=> $split['comCheckeur'].' ',
    'A7'=> $split['info_jobs_instruction'].' ',
    'M7'=> date("Y-m-d").' ',

    'A12'=> $split['waveform'].' ',
    'C12'=> $split['tbljob_frequence'].' ',
    'E12'=> $split['c_type_1'].' ',
    'G12'=> $split['c_type_2'].' ',
    'I12'=> $split['c_unite'].' ',
    'K12'=> $split['temperature'].' ',
    'M12'=> (($split['other_4']==0)?'-':$split['other_4']).' ',


    'A14'=> $split['name'].' ',
    'C14'=> (($split['GE']==0)?'-':$split['GE']),
    'E14'=> (($split['staircase']==0)?'-':$split['staircase']),
    'G14'=> (($split['specific_protocol']==0)?'-':$split['specific_protocol']),

    'J14'=> $split['special_instruction'].' ',

    'A18'=> $split['tbljob_instruction'].' '

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $page->setCellValue($key, $value);
  }



  $row = 28; // 1-based index
  $col = 0;
  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($col = 0; $col <= 15; $col++) {
      $style = $page->getStyleByColumnAndrow(1+$col, 28);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . (string)($row);
      $page->duplicateStyle($style, $dstCell);
    }

    $page->setCellValueByColumnAndRow(1+0, $row, $value['prefixe'].' ');
    $page->setCellValueByColumnAndRow(1+2, $row, $value['nom_eprouvette'].' ');

    $page->setCellValueByColumnAndRow(1+5, $row, ' '.$value['n_fichier']);
    $page->setCellValueByColumnAndRow(1+8, $row, ' ');
    $page->setCellValueByColumnAndRow(1+12, $row, ' ');

    $row++;
  }



  //zone d'impression
  $page->getPageSetup()->setPrintArea('A1:P'.($row-1));



}
ElseIf ($split['test_type_abbr']=="IQC")	{

  $objPHPExcel = $objReader->load("../templates/OT_IQC.xlsx");

  // Rendre votre modèle accessible
  include '../models/annexe_IQC-model.php';

  $oEp = new AnnexeIQCModel($db);
  $epIQC=$oEp->getAllIQC($_GET['id_tbljob']);

  $IQC=$oEp->getGlobalIQC($split['id_dessin']);

  $val2Xls = array(

    'A5' => $split['id_tbljob'],

    'C5' => $split ['customer'].'-'.$split ['job']. '-'.$split ['split'],
    'C6' => $split['dessin'],
    'C7' => $split['ref_matiere'].' ('.$split['matiere'].')',

    'N5' =>  $split['comments'],
    'N6' => date("Y-m-d"),
    'N7' => $split['specification'],

    'B10' => $split['tbljob_instruction'],
    'F10' => $split['tbljob_commentaire'],
    'N10' => $split['tbljob_commentaire_qualite'],

    'R1' => $IQC['nominal_1'],
    'R2' => $IQC['tolerance_plus_1'],
    'R3' => $IQC['tolerance_moins_1'],
    'R4' => $IQC['nominal_2'],
    'R5' => $IQC['tolerance_plus_2'],
    'R6' => $IQC['tolerance_moins_2'],
    'R7' => $IQC['nominal_3'],
    'R8' => $IQC['tolerance_plus_3'],
    'R9' => $IQC['tolerance_moins_3']
  );

  $row = 15; // 1-based index
  $col = 0;

  $oldData=$objPHPExcel->getSheetByName('OldData');
  $data=$objPHPExcel->getSheetByName('INSPECTION QUALITE DIM INSTRUM');
  $newEntry=$objPHPExcel->getSheetByName('New Entry');

  foreach ($epIQC as $key => $value) {
    $oldData->setCellValueByColumnAndRow(1+0, $row, $value['id_eprouvette']);

    $oldData->setCellValueByColumnAndRow(1+1, $row, '*'.$value['prefixe']);
    $oldData->setCellValueByColumnAndRow(1+2, $row, '*'.$value['nom_eprouvette']);


    $oldData->setCellValueByColumnAndRow(1+3, $row, $value['dim1']);
    $oldData->setCellValueByColumnAndRow(1+4, $row, $value['dim2']);
    $oldData->setCellValueByColumnAndRow(1+5, $row, $value['dim3']);

    $oldData->setCellValueByColumnAndRow(1+7, $row, $value['marquage']);
    $oldData->setCellValueByColumnAndRow(1+8, $row, $value['surface']);
    $oldData->setCellValueByColumnAndRow(1+9, $row, $value['grenaillage']);
    $oldData->setCellValueByColumnAndRow(1+10, $row, $value['revetement']);
    $oldData->setCellValueByColumnAndRow(1+11, $row, $value['protection']);
    $oldData->setCellValueByColumnAndRow(1+12, $row, $value['autre']);

    $oldData->setCellValueByColumnAndRow(1+13, $row, $value['d_commentaire']);
    $oldData->setCellValueByColumnAndRow(1+14, $row, $value['date_IQC']);

    $oldData->setCellValueByColumnAndRow(1+15, $row, $value['technicien']);

    $row++;
  }

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $data->setCellValue($key, $value);
    $newEntry->setCellValue($key, $value);
  }

}
ElseIf ($split['ST']=="1")	{

  $objPHPExcel = $objReader->load("../templates/OT_.Default.xlsx");

  $pageEN=$objPHPExcel->getSheetByName('SSTT EN');
  $pageFR=$objPHPExcel->getSheetByName('SSTT FR');

  //on unserialize les deliveredgoods
  parse_str($split['other_4'], $deliveredGoods);


  $val2Xls = array(
    'B4'=> $split['entreprise_abbrST'],
    'C4'=> $split['job'],
    'C5'=> date('Y-m-d'),
    'C6'=> $split['other_3'],
    'C7'=> $split['entrepriseST'],
    'C8'=> $split['prenomST'].' '.$split['nomST'],
    'C2'=> strtoupper($split['test_type']),

    'C13'=> $split['customer'].'-'.$split['job'].'-'.$split['split'],
    'C14'=> $split['po_number'] .' - '.$split['info_jobs_instruction'],
    'C15'=> $split['ref_matiere'],
    'C16'=> $split['dessin'],

    'F14'=> (isset($deliveredGoods['1_DG'])?$deliveredGoods['1_DG']:""),
    'G14'=> (isset($deliveredGoods['1_DGQty'])?$deliveredGoods['1_DGQty']:""),
    'F15'=> (isset($deliveredGoods['2_DG'])?$deliveredGoods['2_DG']:""),
    'G15'=> (isset($deliveredGoods['2_DGQty'])?$deliveredGoods['2_DGQty']:""),
    'F16'=> (isset($deliveredGoods['3_DG'])?$deliveredGoods['3_DG']:""),
    'G16'=> (isset($deliveredGoods['3_DGQty'])?$deliveredGoods['3_DGQty']:""),

    'D21'=> $split['DyT_SubC'],
    'G21'=> $split['nbep'],
    'C22'=> $split['specification'].' - '.$split['tbljob_instruction'],
    'D23'=> (($split['other_2']==0)?'NO':'Yes'),
    'F23'=> (($split['other_1']==0)?'NO':'Fatigue Specimen'),

    'C24'=> $split['tbljob_commentaire']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $pageEN->setCellValue($key, $value);
    $pageFR->setCellValue($key, $value);
  }

  if ($split['test_type_abbr']==".Ma") {
    $pageEN->getRowDimension(23)->setVisible(true);
    $pageFR->getRowDimension(23)->setVisible(true);
  }



  $row = 27; // 1-based index
  $col = 0;

  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($col = 0; $col < 7; $col++) {
      $style = $pageEN->getStyleByColumnAndrow(1+$col, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row+1);
      $pageEN->duplicateStyle($style, $dstCell);

      $style = $pageFR->getStyleByColumnAndrow(1+$col, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row+1);
      $pageFR->duplicateStyle($style, $dstCell);
    }

    $pageEN->setCellValueByColumnAndRow(1+0, $row, (isset($value['prefixe']))?$value['prefixe'].'-'.$value['nom_eprouvette'].' ':$value['nom_eprouvette'].' ');
    $pageEN->setCellValueByColumnAndRow(1+3, $row, $value['dessin']);
    $pageEN->setCellValueByColumnAndRow(1+4, $row, $value['c_commentaire']);

    $pageFR->setCellValueByColumnAndRow(1+0, $row, (isset($value['prefixe']))?$value['prefixe'].' '.'-'.$value['nom_eprouvette'].' ':$value['nom_eprouvette'].' ');
    $pageFR->setCellValueByColumnAndRow(1+3, $row, $value['dessin']);
    $pageFR->setCellValueByColumnAndRow(1+4, $row, (($value['c_type_1_val']>0)?'Inertial Welding - ':'').$value['c_commentaire']);

    $row++;
  }

  $pageEN->getPageSetup()->setPrintArea('A1:G'.$row);
  $pageFR->getPageSetup()->setPrintArea('A1:G'.$row);
}
else {
  $objPHPExcel = $objReader->load("../templates/OT INCONNU.xlsx");
}




//exit;

//define first sheet as opener
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
//$objWriter->setIncludeCharts(TRUE);

$file='../temp/OT-'.$split['job'].'-'.$split['split'].'-'.$split['test_type_abbr'].'.xlsx';
$objWriter->setPreCalculateFormulas(false);
$objWriter->save($file);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="OT-'.$split['job'].'-'.$split['split'].'-'.$split['test_type_abbr'].'.xlsx"');
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
