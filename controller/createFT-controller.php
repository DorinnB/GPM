<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_ep']) OR $_GET['id_ep']=="")	{
  exit();

}



// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

$oEprouvette = new EprouvetteModel($db,$_GET['id_ep']);

$essai=$oEprouvette->getTest();

//recuperation des commentaires des splits precedents
$workflow=$oEprouvette->getWorkflow();
$essai['comm']=(isset($workflow['comm']))?$workflow['comm']:"";



$dimDenomination=$oEprouvette->dimensions($essai['id_dessin_type']);

//suppression des dimensions null
foreach ($dimDenomination as $index => $data) {

  if ($data=='') {
    unset($dimDenomination[$index]);
  }
}
$dimDenomination = array_values($dimDenomination);  //Conversion de l'array "keys" en "numeric"

$area = $oEprouvette->calculArea($essai['id_dessin_type'],$essai['dim1'],$essai['dim2'],$essai['dim3'])['area'];


$oEprouvette->niveaumaxmin($essai['c_1_type'], $essai['c_2_type'], $essai['c_type_1_val'], $essai['c_type_2_val']);

$tempCorrected=$oEprouvette->getTempCorrected();

$estimatedCycle=$oEprouvette->getEstimatedCycle();

//a suppreimer apres bug 3 juillet 2018
$maxLimitdeplacement="";




/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Paris');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include \PhpOffice\PhpSpreadsheet\Spreadsheet */
//require '../vendor/autoload.php';
require '../vendor/autoload.php';

// Create new \PhpOffice\PhpSpreadsheet\Spreadsheet object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$wizard = new \PhpOffice\PhpSpreadsheet\Helper\Html;



$style_gray = array(
  'fill' => array(
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'C0C0C0')
  )
);
$style_white = array(
  'fill' => array(
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'000000')
  )
);






if (isset($essai['split']))		//groupement du nom du job avec ou sans indice
$jobcomplet= $essai['customer'].'-'.$essai['job'].'-'.$essai['split'];
else
$jobcomplet= $essai['customer'].'-'.$essai['job'];




//restet en exposant
$essai['nom_eprouvette']=($essai['retest']!=1)?$essai['nom_eprouvette'].'<sup>'.$essai['retest'].'</sup>':$essai['nom_eprouvette'];
$identification = $wizard->toRichTextObject('<b>'.$essai['nom_eprouvette'].'</b>');





if (isset($essai['compresseur']) AND $essai['compresseur']==1)
$compresseur="n";
else
$compresseur="o";

$essai['ind_temp_top'] = (isset($essai['ind_temp_top']))? $essai['ind_temp_top'] : "";
$essai['ind_temp_strap'] = (isset($essai['ind_temp_strap']))? $essai['ind_temp_strap'] : "";
$essai['ind_temp_bot'] = (isset($essai['ind_temp_bot']))? $essai['ind_temp_bot'] : "";


if ($essai['ind_temp_top'] == $essai['ind_temp_bot'] )	{		//groupement des ind.temp.
  if (($essai['ind_temp_top'] == $essai['ind_temp_strap'])) {
    $ind_temp = $essai['ind_temp_top'];
  }
  elseif ( $essai['ind_temp_top']=="") {
    $ind_temp = $essai['ind_temp_strap'];
  }
  else{
    $ind_temp = $essai['ind_temp_top'].' / '.$essai['ind_temp_strap'];
  }
}
else {
  $ind_temp = $essai['ind_temp_top'].' / '.$essai['ind_temp_strap'].' / '.$essai['ind_temp_bot'];
}


if (isset($essai['type_chauffage']) AND $essai['type_chauffage']=="Coil")	//chauffage coil
$coil=$essai['chauffage'];
else
$coil="";

if (isset($essai['type_chauffage']) AND $essai['type_chauffage']=="Four")	//chauffage coil
$four=$essai['chauffage'];
else
$four="";

if (isset($essai['c_cycle_STL']) AND $essai['c_cycle_STL']!="0")	//STL
$STL=$essai['c_cycle_STL'];
else
$STL="";

if (isset($essai['c_frequence_STL']) AND $essai['c_frequence_STL']!="0")	//STL
$F_STL=$essai['c_frequence_STL'];
else
$F_STL="";

if (isset($essai['runout']) AND $essai['runout']!="0")	//Runout
$runout=$essai['runout'];
else
$runout="RTF";



if ($essai['signal_true']=="1")	//chauffage coil
$true='T-';
else
$true='';
if ($essai['signal_tapered']=="1")	//chauffage coil
$tapered='-T';
else
$tapered='';






//cas particulier de la dim2
if (!isset($essai['dim2']) OR $essai['dim2']=='')	//Runout
$essai['dim2']=' ';
//cas particulier de la dim3
if (!isset($essai['dim3']) OR $essai['dim3']=='')	//Runout
$essai['dim3']=' ';



//	if (isset($essai['Cycle_min']) AND $essai['Cycle_min']!="0")	//STL
//		$essai['Cycle_min']=$essai['Cycle_min'];
//	else
//		$essai['Cycle_min']="-";









function copyRows(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet,$srcCol) {
  for ($row = 36; $row < 49; $row++) {
    for ($col = $srcCol; $col < $srcCol+16; $col++) {
      $cell = $sheet->getCellByColumnAndRow(1+$col, $row);
      $style = $sheet->getStyleByColumnAndRow(1+$col, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col-$srcCol) . (string)($row);
      $sheet->setCellValue($dstCell, $cell->getValue());
      $sheet->duplicateStyle($style, $dstCell);
    }
  }
}









$objPHPExcel = $objReader->load("../templates/FT.xlsx");
$FT=$objPHPExcel->getSheetByName('FT');


If ($essai['test_type_abbr']=="Loa"  OR $essai['test_type_abbr']=="Flx")	{

  //copy du template du control change
  copyRows($FT,43);
  //underpeak
  $FT->getStyle('O32:P32')->applyFromArray( $style_gray );

}
ElseIf ($essai['test_type_abbr']=="LoS" OR $essai['test_type_abbr']=="Dwl")	{

  //copy du template du control change
  copyRows($FT,43);
  //underpeak
  $FT->getStyle('O32:P32')->applyFromArray( $style_gray );

}
ElseIf ($essai['test_type_abbr']=="Crp" OR $essai['test_type_abbr']=="ICr")	{

  //copy du template du control change
  copyRows($FT,43);
  //underpeak
  $FT->getStyle('O32:P32')->applyFromArray( $style_gray );

}
ElseIf ($essai['test_type_abbr']=="Str"  OR $essai['test_type_abbr']=="IF" OR $essai['test_type_abbr']=="IRlx")	{

  //copy du template du control change
  copyRows($FT,26);
  //copy estimated STL
  $FT->setCellValue('B41', $estimatedCycle['c2_max_stressEstimate']/$area);
  $FT->setCellValue('B42', $estimatedCycle['c2_min_stressEstimate']/$area);

  $FT->setCellValue('E39', (($essai['Cycle_STL']>0)?$essai['Cycle_STL']:' '));
  $FT->setCellValue('M40', $essai['c_temperature']);
  $FT->setCellValue('O40', $tempCorrected);

  //cases temperature en gris
  if ($essai['c_temperature']<35) {
    $FT->getStyle('H40:H41')->applyFromArray( $style_gray );
    $FT->getStyle('J40:J41')->applyFromArray( $style_gray );
    $FT->getStyle('I42:I43')->applyFromArray( $style_gray );
  }

}
ElseIf ($essai['test_type_abbr']=="PS")	{

  //copy du template du control change
  copyRows($FT,60);

  if ($essai['other_1']==0) {
    $FT->getRowDimension(42)->setVisible(false);
    $FT->getRowDimension(43)->setVisible(false);
  }
  else {
    $FT->getRowDimension(44)->setVisible(true);
    $FT->getRowDimension(45)->setVisible(true);
    $FT->getRowDimension(46)->setVisible(true);

    $FT->getRowDimension(71)->setVisible(false);
    $FT->getRowDimension(72)->setVisible(false);
    $FT->getRowDimension(73)->setVisible(false);
    $FT->getRowDimension(74)->setVisible(false);
  }


}








//calcul niveau + limits
if ($essai['c_unite']=="MPa")	{

  $maxMPa = number_format($oEprouvette->MAX()*1, 0, '.', ' ');
  $minMPa = number_format($oEprouvette->MIN()*1, 0, '.', ' ');

  $maxkN = number_format($oEprouvette->MAX()*$area/1000, 2, '.', ' ');
  $minkN = number_format($oEprouvette->MIN()*$area/1000, 2, '.', ' ');

  $maxLimitkN = $maxkN+max(max(abs($maxkN),abs($minkN))*5/100,0.5);
  $minLimitkN = $minkN-max(max(abs($maxkN),abs($minkN))*5/100,0.5);

  if (($essai['other_3']==0) OR ($essai['other_3']=="")) { //Standard
    $maxLimitdeplacement=6;
  }
  elseif ($essai['other_3']==1) { //OCV
    $maxLimitdeplacement=10;
  }
  elseif ($essai['other_3']==2) { //Tube
    $maxLimitdeplacement=10;
  }
  elseif ($essai['other_3']==3) { //Custom
    $maxLimitdeplacement='';
  }

  $FT->setCellValue('K21', '(MPa) MAX (kN)');
  $FT->setCellValue('M21', '(MPa) MIN (kN)');
  $FT->setCellValue('K22', $maxMPa.' ');
  $FT->setCellValue('M22', $minMPa.' ');
  $FT->setCellValue('L22', $maxkN.' ');
  $FT->setCellValue('N22', $minkN.' ');

  $FT->setCellValue('B32', $maxLimitdeplacement);
  $FT->setCellValue('C32', '-'.$maxLimitdeplacement);
  $FT->setCellValue('B33', $maxLimitkN);
  $FT->setCellValue('C33', $minLimitkN);
  $FT->setCellValue('B34', '');
  $FT->setCellValue('C34', '');
}
Elseif ($essai['c_unite']=="kN")	{
  $maxkN = number_format($oEprouvette->MAX(), 3, '.', ' ');
  $minkN = number_format($oEprouvette->MIN(), 3, '.', ' ');

  $maxLimitkN = $oEprouvette->MAX()+max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*5/100),0.5);
  $minLimitkN = $oEprouvette->MIN()-max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*5/100),0.5);

  if (($essai['other_3']==0) OR ($essai['other_3']=="")) { //Standard
    $maxLimitdeplacement=6;
  }
  elseif ($essai['other_3']==1) { //OCV
    $maxLimitdeplacement=10;
  }
  elseif ($essai['other_3']==2) { //Tube
    $maxLimitdeplacement=10;
  }
  elseif ($essai['other_3']==3) { //Custom
    $maxLimitdeplacement='';
  }

  $FT->setCellValue('K21', 'MAX (kN)');
  $FT->setCellValue('M21', 'MIN (kN)');
  $FT->setCellValue('K22', $maxkN.' ');
  $FT->setCellValue('M22', $minkN.' ');

  $FT->setCellValue('B32', $maxLimitdeplacement);
  $FT->setCellValue('C32', '-'.$maxLimitdeplacement);
  $FT->setCellValue('B33', $maxLimitkN);
  $FT->setCellValue('C33', $minLimitkN);
  $FT->setCellValue('B34', '');
  $FT->setCellValue('C34', '');
}
Elseif ($essai['c_unite']=="%")	{
  $maxStrain = number_format($oEprouvette->MAX(), 3, '.', ' ');
  $minStrain = number_format($oEprouvette->MIN(), 3, '.', ' ');

  $maxLimitStrain = $oEprouvette->MAX()+0.15;
  $minLimitStrain = $oEprouvette->MIN()-0.15;

  if (($essai['other_3']==2) OR ($essai['test_type_abbr']=="PS")) { //Tube ou PS
    $maxLimitdeplacement='';
  }
  elseif (($essai['other_3']==0) OR ($essai['other_3']=="")) { //Standard
    $maxLimitdeplacement=3;
  }
  elseif ($essai['other_3']==1) { //OCV
    $maxLimitdeplacement='';
  }
  elseif ($essai['other_3']==3) { //Custom
    $maxLimitdeplacement='';
  }

  $FT->setCellValue('K21', 'MAX (%)');
  $FT->setCellValue('M21', 'MIN (%)');
  $FT->setCellValue('K22', $maxStrain.' ');
  $FT->setCellValue('M22', $minStrain.' ');

  $FT->setCellValue('B32', $maxLimitdeplacement);
  $FT->setCellValue('C32', '-'.$maxLimitdeplacement);
  $FT->setCellValue('B33', '');
  $FT->setCellValue('C33', '');
  $FT->setCellValue('B34', $maxLimitStrain);
  $FT->setCellValue('C34', $minLimitStrain);
}
Else	{
  $maxkN = "ERREUR d'unité";
  $minkN = "ERREUR d'unité";

  $maxLimitkN = "ERREUR d'unité";
  $minLimitkN = "ERREUR d'unité";

  $FT->setCellValue('K22', $maxkN);
  $FT->setCellValue('M22', $minkN);
}

//calcul temps d'essai
$dateDebut = new DateTime($essai['date']);
if (isset($estimatedCycle) AND $essai['c_frequence']>0) {              //il faut la fréquence
  if ((isset($estimatedCycle) AND $estimatedCycle['cycle_estime']>0)) { //et un cycle estimé
    if (isset($essai['c_cycle_STL']) AND $essai['c_cycle_STL']>0) {             //STL ou pas ?
      if ($estimatedCycle['cycle_estime']>$essai['c_cycle_STL']) {              //avant ou après le STL ?
        $tpsEstime=($estimatedCycle['cycle_estime']-$essai['c_cycle_STL'])/$essai['c_frequence_STL']/3600+$essai['c_cycle_STL']/$essai['c_frequence']/3600;
        $dateDebut->add(new DateInterval("PT".ceil($tpsEstime+12)."H"));
        $dateEstime= $dateDebut->format('Y-m-d') . "\n";
      }
      else {
        $tpsEstime=$estimatedCycle['cycle_estime']/$essai['c_frequence']/3600;
        $dateDebut->add(new DateInterval("PT".ceil($tpsEstime+12)."H"));
        $dateEstime= $dateDebut->format('Y-m-d') . "\n";
      }
    }
    else {
      $tpsEstime=$estimatedCycle['cycle_estime']/$essai['c_frequence']/3600;
      $dateDebut->add(new DateInterval("PT".ceil($tpsEstime+12)."H"));
      $dateEstime= $dateDebut->format('Y-m-d') . "\n";
    }
  }
  else {
    $tpsEstime=' ';
    $dateEstime=' ';
  }
}
else {
  $tpsEstime=' ';
  $dateEstime=' ';
}


$val2Xls = array(

  'C2' => $essai['test_type'].' Fatigue Test',
  'O2' => 'FT - '.$essai['n_fichier'],

  'A5' => $jobcomplet,
  'D5' => $essai['prefixe'],
  'G5' => $identification,
  'J5' => $essai['n_fichier'],
  'M5' => $essai['n_essai'],

  'A7' => $essai['machine'],
  'D7' => $essai['name'],
  'G7' => $essai['operateur'],
  'J7' => $essai['controleur'],
  'M7' => $essai['date'],


  'A12' => $essai['outillage_top'],
  'C12' => $essai['outillage_bot'],
  'E12' => (isset($essai['chauffage'])?$essai['chauffage']:' '),
  'G12' => $compresseur,

  'A14' => $essai['enregistreur'],
  'C14' => '40001',
  'D14' => $essai['Lo'],
  'E14' => $ind_temp,


  'J12' => $essai['cell_displacement_serial'],
  'K12' => $essai['cell_displacement_gamme'],
  'L12' => $essai['Disp_P'],
  'M12' => $essai['Disp_i'],
  'N12' => $essai['Disp_D'],
  'O12' => $essai['Disp_Conv'],
  'P12' => $essai['Disp_Sens'],

  'J13' => $essai['cell_load_serial'],
  'K13' => $essai['cell_load_gamme'],
  'L13' => $essai['Load_P'],
  'M13' => $essai['Load_i'],
  'N13' => $essai['Load_D'],
  'O13' => $essai['Load_Conv'],
  'P13' => $essai['Load_Sens'],

  'J14' => $essai['extensometre'],
  'K14' => '_5%_',
  'L14' => $essai['Strain_P'],
  'M14' => $essai['Strain_i'],
  'N14' => $essai['Strain_D'],
  'O14' => $essai['Strain_Conv'],
  'P14' => $essai['Strain_Sens'],



  'A19' => $essai['dessin'],
  'C19' => $essai['ref_matiere'],
  'E19' => $essai['c_frequence'],
  'G19' => $true.$essai['c_waveform'].$tapered,
  'K18' => ((isset($dimDenomination[0])?$dimDenomination[0]:' ')).' (mm)',
  'K19' => $essai['dim1'],
  'M18' => ((isset($dimDenomination[1])?$dimDenomination[1]:' ')),
  'M19' => $essai['dim2'],
  'O18' => ((isset($dimDenomination[2])?$dimDenomination[2]:' ')),
  'O19' => $essai['dim3'],

  'B22' =>((isset($estimatedCycle) AND $estimatedCycle['E_RTEstime']>0 AND $estimatedCycle['E_RTEstime']!=1)?$estimatedCycle['E_RTEstime']:' '),
  'B23' =>((isset($estimatedCycle) AND $estimatedCycle['E_htEstime']>0 AND $estimatedCycle['E_htEstime']!=1)?$estimatedCycle['E_htEstime']:' '),
  'E22' => $essai['c_temperature'],
  'G22' => $tempCorrected,
  'I19' => $runout,
  'O22' => number_format($area, 3, '.', ' '),
  'J27' => ((isset($estimatedCycle) AND $estimatedCycle['dilatationEstime']!=1)?$estimatedCycle['dilatationEstime']:' '),

  'A39' => $STL,
  'C39' => $F_STL,

  'A53' => $essai['Cycle_min'],
  'C53' => (($essai['truecyclefinal']>0)?$essai['truecyclefinal']:' '),
  'E53' => $essai['temps_essais'].' ',
  'G53' => $essai['Rupture'].' ',
  'I53' => $essai['Fracture'].' ',
  'C54' =>((isset($estimatedCycle) AND $estimatedCycle['cycle_estime']>0)?$estimatedCycle['cycle_estime']:' '),
  'E54' => $tpsEstime,
  'K54' => $dateEstime,


  'A59' => $essai['comm'].' / '.$essai['c_commentaire'],
  'A60' => $essai['tbljob_instruction'],
  'P58' => (($essai['special_instruction']=='')?' ':'Special Instructions'),
  'P59' => $essai['special_instruction']

);

//case temperature en gris
if ($essai['c_temperature']<35) {

  $FT->getStyle('E12:F12')->applyFromArray( $style_gray );
  $FT->getStyle('E14:H14')->applyFromArray( $style_gray );
  $FT->getStyle('K34:L34')->applyFromArray( $style_gray );

  $FT->getStyle('A23:D23')->applyFromArray( $style_gray );
  $FT->getStyle('J23:P23')->applyFromArray( $style_gray );
  $FT->getStyle('A24:P27')->applyFromArray( $style_gray );

}


//Pour chaque element du tableau associatif, on update les cellules Excel
foreach ($val2Xls as $key => $value) {
  $FT->setCellValue($key, $value);
}



//tableau pour le stepcase
if ($essai['stepcase_val']!='') {
  $FT->setCellValue('K38', 'Stepcase n°');
  $FT->setCellValue('L38', 'Max (MPa)');
  $FT->setCellValue('M38', 'Min (MPa)');
  $FT->setCellValue('N38', 'Max (kN)');
  $FT->setCellValue('O38', 'Min (kN)');
  $FT->setCellValue('P38', 'Runout');
  for ($i=0; $i <5 ; $i++) {
    $oEprouvette->niveaumaxmin(
      $essai['c_1_type'],
      $essai['c_2_type'],
      $essai['c_type_1_val']+(($essai['c_1_type']==$essai['steptype'])?$i*$essai['stepcase_val']:0),
      $essai['c_type_2_val']+(($essai['c_2_type']==$essai['steptype'])?$i*$essai['stepcase_val']:0)
    );


    //calcul des limites avec le niveau le plus extreme des 5 stepcases
    //et des differents steps
    if ($essai['c_unite']=="MPa")	{

      $maxMPa = number_format($oEprouvette->MAX(), 0, '.', ' ');
      $minMPa = number_format($oEprouvette->MIN(), 0, '.', ' ');
      $maxkN = number_format($oEprouvette->MAX()*$area/1000, 2, '.', ' ');
      $minkN = number_format($oEprouvette->MIN()*$area/1000, 2, '.', ' ');

      $maxLimitkN = number_format(max($maxLimitkN,$maxkN+max(max(abs($maxkN),abs($minkN))*5/100,0.5)), 2, '.', ' ');
      $minLimitkN = number_format(min($minLimitkN,$minkN-max(max(abs($maxkN),abs($minkN))*5/100,0.5)), 2, '.', ' ');

    }
    Elseif ($essai['c_unite']=="kN")	{
      $maxMPa='';
      $minMPa='';
      $maxkN = number_format($oEprouvette->MAX(), 3, '.', ' ');
      $minkN = number_format($oEprouvette->MIN(), 3, '.', ' ');

      $maxLimitkN = number_format(max($maxLimitkN,$oEprouvette->MAX()+max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*5/100),0.5)), 2, '.', ' ');
      $minLimitkN = number_format(min($minLimitkN,$oEprouvette->MIN()-max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*5/100),0.5)), 2, '.', ' ');;
    }
    Else	{
      $maxMPa='';
      $minMPa='';
      $maxkN = "ERREUR d'unité";
      $minkN = "ERREUR d'unité";

      $maxLimitkN = "ERREUR d'unité";
      $minLimitkN = "ERREUR d'unité";
    }


    $FT->setCellValue('K'.(39+$i), ($i+1));
    $FT->setCellValue('L'.(39+$i), $maxMPa);
    $FT->setCellValue('M'.(39+$i), $minMPa);
    $FT->setCellValue('N'.(39+$i), $maxkN);
    $FT->setCellValue('O'.(39+$i), $minkN);
    $FT->setCellValue('P'.(39+$i), $runout*($i+1));

  }
  //on ajoute * apres les limites pour signifier l'incertitude des limites
  $FT->setCellValue('B33', $maxLimitkN.'*');
  $FT->setCellValue('C33', $minLimitkN.'*');

}




//exit;


$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');

$file='../temp/FT-'.$essai['n_fichier'].'.xlsx';
$objWriter->save($file);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="FT-'.$essai['n_fichier'].'.xlsx"');
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
