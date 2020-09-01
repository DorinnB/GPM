<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_tbljob']) OR $_GET['id_tbljob']=="")	{
  exit;
}


if (isset($_GET['type']) && $_GET['type']!='')	{
  $type=$_GET['type'];
}
else {
  echo 'Missing Type of report';
  exit;
}
if (isset($_GET['language']) && $_GET['language']!='')	{
  $language='_'.$_GET['language'];
}
else {
  $language='_FR';
}
if (isset($_GET['specific']) && $_GET['specific']!='')	{
  $specific='_'.$_GET['specific'];
}
else {
  $specific='_Std';
}


// Rendre votre modèle accessible
include '../models/split-model.php';

$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);

$split=$oSplit->getSplit();


if (isset($split['split']))		//groupement du nom du job avec ou sans indice
$jobcomplet= $split['customer'].'-'.$split['job'].'-'.$split['split'];
else
$jobcomplet= $split['customer'].'-'.$split['job'];



//adresse
$i=0;
if (isset($split['departement']) AND $split['departement']!="") {
  $adresse[$i]='departement';
  $i++;
}
if (isset($split['rue1']) AND $split['rue1']!="") {
  $adresse[$i]='rue1';
  $i++;
}
if (isset($split['rue2']) AND $split['rue2']!="") {
  $adresse[$i]='rue2';
  $i++;
}
if (isset($split['ville']) AND $split['ville']!="") {
  $adresse[$i]='ville';
  $i++;
}
if (isset($split['pays']) AND $split['pays']!="") {
  $adresse[$i]='pays';
  $i++;
}



// Rendre votre modèle accessible
include '../models/eprouvettes-model.php';
include '../models/eprouvette-model.php';


$oEprouvettes = new LstEprouvettesModel($db,$_GET['id_tbljob']);
$ep=$oEprouvettes->getAllEprouvettes();

$MA['MArefSubC']='';
$MA['MAspecifs']='';

for($k=0;$k < count($ep);$k++)	{
  $oEprouvette = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
  $ep[$k]=$oEprouvette->getTest();

  //récupération des splits .MA effectué
  $ep2[$k]=$oEprouvette->getWorkflow();

  //suppression des split non .MA en otant les ; de separation (si existant)
  if (isset($ep2[$k]['MArefSubC'])) {
    $ep2[$k]['MArefSubC']=str_replace(';', '', $ep2[$k]['MArefSubC']);
  }
  if (isset($ep2[$k]['MAspecifs'])) {
    $ep2[$k]['MAspecifs']=str_replace(';', '', $ep2[$k]['MAspecifs']);
  }
  //si le split .MA exist, on supprime les doublons grace aux clé de l'array
  $MA['MArefSubC'][$ep2[$k]['MArefSubC']]=1;
  $MA['MAspecifs'][$ep2[$k]['MAspecifs']]=1;




  $dimDenomination=$oEprouvette->dimensions($ep[$k]['id_dessin_type']);
  $area = $oEprouvette->calculArea($ep[$k]['id_dessin_type'],$ep[$k]['dim1'],$ep[$k]['dim2'],$ep[$k]['dim3'])['area'];

  //suppression des dimensions null
  foreach ($dimDenomination as $index => $data) {

    if ($data=='') {
      unset($dimDenomination[$index]);
    }
  }

  $ep[$k]['denomination'] =$dimDenomination;
  $ep[$k]['area'] = $oEprouvette->calculArea($ep[$k]['id_dessin_type'],$ep[$k]['dim1'],$ep[$k]['dim2'],$ep[$k]['dim3'])['area'];



  $oEprouvette->niveaumaxmin($ep[$k]['c_1_type'], $ep[$k]['c_2_type'], $ep[$k]['c_type_1_val'], $ep[$k]['c_type_2_val']);
  $ep[$k]['max']=$oEprouvette->MAX();
  $ep[$k]['min']=$oEprouvette->MIN();



  //recherche si le split a été fait avec un coil ou un four
  if (isset($ep[$k]['type_chauffage']) AND $ep[$k]['type_chauffage']=="Coil")
  $coil="x";
  if (isset($ep[$k]['type_chauffage']) AND $ep[$k]['type_chauffage']=="Four")
  $four="x";

}





$MArefSubC=implode(" - ",array_keys($MA['MArefSubC']));
$MAspecifs=implode(" - ",array_keys($MA['MAspecifs']));




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



/*  ANNULE POUR GAGNER EN RAPIDITE
//copy des styles des colonnes
for ($row = 10; $row <= 58; $row++) {
$style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
$dstCell = Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
$pvEssais->duplicateStyle($style, $dstCell);
}
*/


If ($split['test_type_abbr']=="Str" AND $type=="Report")	{

  $objPHPExcel = $objReader->load("../templates/Report ".$split['test_type_abbr'].$language.$specific.".xlsm");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');


  $val2Xls = array(

    'B5'=> $split['entreprise'],
    'B6'=> $split['prenom'].' '.$split['nom'],
    'B7'=> (isset($adresse[0])?$split[$adresse[0]]:''),
    'B8'=> (isset($adresse[1])?$split[$adresse[1]]:''),
    'B9'=> (isset($adresse[2])?$split[$adresse[2]]:''),
    'B10'=> (isset($adresse[3])?$split[$adresse[3]]:''),
    'B11'=> (isset($adresse[4])?$split[$adresse[4]]:''),

    'F5' => $jobcomplet,
    'F6'=> (($split['report_rev']=='')?($split['report_rev']+1-1).' - DRAFT':$split['report_rev']),
    'F7'=> date("Y-m-d"),
    'F9'=> $split['po_number'],

    'C20'=> $split['info_jobs_instruction'],
    'C22'=> $split['customer'].'-'.$split['job'],

    'C24'=> $split['ref_matiere'],

    //'C28' si .MA
    'K25'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C26'=> $MArefSubC,
    'C27'=> $MAspecifs,
    'C28'=> $split['dessin'],

    'C36'=> $split['specification'],
    'C37'=> $split['nbep'],
    'C38'=> $split['nbtestdone'],

    'C41'=> $split['waveform'],
    'K42'=> $split['cell_load_capacity'],
    'K43'=> $split['four'],
    'L43'=> $split['coil'],
    'C44'=> $split['ratio1']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //masquage des lignes d'adresse non utilisé
  if (!isset($adresse[3])) {
    $enTete->getRowDimension(10)->setVisible(false);
    $enTete->getRowDimension(11)->setVisible(false);
    $enTete->getRowDimension(53)->setVisible(true);
    $enTete->getRowDimension(54)->setVisible(true);
  }
  if (!isset($adresse[4])) {
    $enTete->getRowDimension(11)->setVisible(false);
    $enTete->getRowDimension(54)->setVisible(true);
  }

  //job number
  $pvEssais->setCellValue("M1", $jobcomplet);

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 19, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 19, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 20, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 20, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

  $pvEssais->setCellValueByColumnAndRow(1+0, 46, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 46, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 47, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 47, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;

  $hide_row=array();
  $show_row=array();

  foreach ($ep as $key => $value) {

    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 21, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 26, $value['denomination']['denomination_1']);
      }
      else {
        array_push($hide_row, 26);
      }
    }
    else {
      array_push($hide_row, 22);
      array_push($hide_row, 26);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 27, $value['denomination']['denomination_2']);
      }
      else {
        array_push($hide_row, 27);
      }

    }
    else {
      array_push($hide_row, 23);
      array_push($hide_row, 27);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 24, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 28, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 28, $value['denomination']['denomination_3']);
      }
      else {
        array_push($hide_row, 28);
      }
    }
    else {
      array_push($hide_row, 24);
      array_push($hide_row, 28);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 25, $value['E_RT']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, (isset($value['dilatation'])?$value['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, (isset($value['dilatation'])?$value['Lo']*$value['dilatation']:''));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, $value['c1_E_montant']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, $value['c1_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['c1_min_stress']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, $value['c2_cycle']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, $value['c2_E_montant']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 38, $value['c2_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 39, $value['c2_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 40, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']-$value['c2_calc_inelastic_strain']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 41, $value['c2_calc_inelastic_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 42, $value['c2_meas_inelastic_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 43, $value['c2_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 44, $value['c2_min_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 45,(isset($value['c2_max_strain'])?(($value['name']=="GE")?$value['c1_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10:$value['c2_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10):''));


    if (strlen($value['c2_max_strain']==0)) {
      $mivie_val_1="";
      $mivie_val_2="";
    }
    else {
      if ($split['c_type_1']=="Max") {
        $mivie_val_1=$value['c2_max_strain'];
      }
      elseif ($split['c_type_1']=="Min") {
        $mivie_val_1=$value['c2_min_strain'];
      }
      elseif ($split['c_type_1']=="Mean") {
        $mivie_val_1=($value['c2_max_strain']+$value['c2_min_strain'])/2;
      }
      elseif ($split['c_type_1']=="Alt") {
        $mivie_val_1=($value['c2_max_strain']-$value['c2_min_strain'])/2;
      }
      elseif ($split['c_type_1']=="Range") {
        $mivie_val_1=$value['c2_max_strain']-$value['c2_min_strain'];
      }
      elseif ($split['c_type_1']=="R") {
        if ($value['c2_max_strain']==0) {
          $mivie_val_1="infini";
        }
        else {
          $mivie_val_1=$value['c2_min_strain']/$value['c2_max_strain'];
        }
      }
      elseif ($split['c_type_1']=="A") {
        if ((($value['c2_max_strain']+$value['c2_min_strain'])/2)==0) {
          $mivie_val_1="infini";
        }
        else {
          $mivie_val_1=(($value['c2_max_strain']-$value['c2_min_strain'])/2)/(($value['c2_max_strain']+$value['c2_min_strain'])/2);
        }
      }


      if ($split['c_type_2']=="Max") {
        $mivie_val_2=$value['c2_max_strain'];
      }
      elseif ($split['c_type_2']=="Min") {
        $mivie_val_2=$value['c2_min_strain'];
      }
      elseif ($split['c_type_2']=="Mean") {
        $mivie_val_2=($value['c2_max_strain']+$value['c2_min_strain'])/2;
      }
      elseif ($split['c_type_2']=="Alt") {
        $mivie_val_2=($value['c2_max_strain']-$value['c2_min_strain'])/2;
      }
      elseif ($split['c_type_2']=="Range") {
        $mivie_val_2=$value['c2_max_strain']-$value['c2_min_strain'];
      }
      elseif ($split['c_type_2']=="R") {
        if ($value['c2_max_strain']==0) {
          $mivie_val_2="infini";
        }
        else {
          $mivie_val_2=$value['c2_min_strain']/$value['c2_max_strain'];
        }
      }
      elseif ($split['c_type_2']=="A") {
        if ((($value['c2_max_strain']+$value['c2_min_strain'])/2)==0) {
          $mivie_val_2="infini";
        }
        else {
          $mivie_val_2=(($value['c2_max_strain']-$value['c2_min_strain'])/2)/(($value['c2_max_strain']+$value['c2_min_strain'])/2);
        }
      }
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $mivie_val_1);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $mivie_val_2);



    $pvEssais->setCellValueByColumnAndRow(1+$col, 49, ($value['Cycle_STL']==0)?"NA":$value['Cycle_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['Cycle_min']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 52, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 53, (($value['Ni']=="")?"NA":$value['Ni']));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 54, (($value['Nf75']=="")?"NA":$value['Nf75']));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 55, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 56, $value['Fracture']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 57, $value['fractureCust']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 58, $value['validation']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 59, $value['unvalidity']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 60, $value['validupto']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 61, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));


    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'68')->applyFromArray( $styleCell['checked'] ); //default style
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'9')->applyFromArray( $styleCell['running'] );
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'49:'.Coordinate::stringFromColumnIndex(1+$col).'68')->applyFromArray( $styleCell['running'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "RUNNING");
    }
    elseif (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'68')->applyFromArray( $styleCell['unchecked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "Unchecked");
    }
    elseif ($value['valid']=='0') {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'10:'.Coordinate::stringFromColumnIndex(1+$col).'68')->applyFromArray( $styleCell['void'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "VOID");
    }
    else {
      //$pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'68')->applyFromArray( $styleCell['checked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "");
    }


    //s'il y a un mini, on affiche la lignes
    if ($value['Cycle_min']>0) {
      array_push($show_row, 51);
    }

    //affichage si DMC
    if ($split['id_rawData']==6) {
      array_push($show_row, 57);
      array_push($show_row, 58);
      array_push($show_row, 59);
      array_push($show_row, 60);
    }


    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 70, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 70)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 69, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 70, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'70:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'70');
      $pvEssais->getRowDimension(70)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(70)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 71, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'71:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'71');
      $pvEssais->getRowDimension(71)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 71)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(71)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //suppression des doublons et affichage lignes
  $hide_row = array_unique($hide_row);
  $show_row = array_unique($show_row);
  foreach (array_unique($hide_row) as $key => $value) {
    $pvEssais->getRowDimension($value)->setVisible(FALSE);
  }
  foreach (array_unique($show_row) as $key => $value) {
    $pvEssais->getRowDimension($value)->setVisible(TRUE);
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(71));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < (1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1) ; $c+=$nbPage) {
    $pvEssais->setBreak( Coordinate::stringFromColumnIndex(1+$c).(1) , Worksheet::BREAK_COLUMN );
    copyRange($pvEssais, 'D1:M8', Coordinate::stringFromColumnIndex(1+$c).(1));
  }





}
ElseIf ($split['test_type_abbr']=="TMF" AND $type=="Report")	{

  $objPHPExcel = $objReader->load("../templates/Report ".$split['test_type_abbr'].$language.$specific.".xlsm");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');


  $val2Xls = array(

    'B5'=> $split['entreprise'],
    'B6'=> $split['prenom'].' '.$split['nom'],
    'B7'=> (isset($adresse[0])?$split[$adresse[0]]:''),
    'B8'=> (isset($adresse[1])?$split[$adresse[1]]:''),
    'B9'=> (isset($adresse[2])?$split[$adresse[2]]:''),
    'B10'=> (isset($adresse[3])?$split[$adresse[3]]:''),
    'B11'=> (isset($adresse[4])?$split[$adresse[4]]:''),

    'F5' => $jobcomplet,
    'F6'=> (($split['report_rev']=='')?($split['report_rev']+1-1).' - DRAFT':$split['report_rev']),
    'F7'=> date("Y-m-d"),
    'F9'=> $split['po_number'],

    'C20'=> $split['info_jobs_instruction'],
    'C22'=> $split['customer'].'-'.$split['job'],

    'C24'=> $split['ref_matiere'],

    //'C28' si .MA
    'K25'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C26'=> $MArefSubC,
    'C27'=> $MAspecifs,
    'C28'=> $split['dessin'],

    'C36'=> $split['specification'],
    'C37'=> $split['nbep'],
    'C38'=> $split['nbtestdone'],

    'C41'=> $split['waveform'],
    'K42'=> $split['cell_load_capacity'],
    'K43'=> $split['four'],
    'L43'=> $split['coil'],
    'C44'=> $split['ratio1']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //masquage des lignes d'adresse non utilisé
  if (!isset($adresse[3])) {
    $enTete->getRowDimension(10)->setVisible(false);
    $enTete->getRowDimension(11)->setVisible(false);
    $enTete->getRowDimension(53)->setVisible(true);
    $enTete->getRowDimension(54)->setVisible(true);
  }
  if (!isset($adresse[4])) {
    $enTete->getRowDimension(11)->setVisible(false);
    $enTete->getRowDimension(54)->setVisible(true);
  }

  //job number
  $pvEssais->setCellValue("M1", $jobcomplet);

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 19, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 19, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 20, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 20, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

  $pvEssais->setCellValueByColumnAndRow(1+0, 46, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 46, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 47, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 47, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;

  $hide_row=array();
  $show_row=array();

  foreach ($ep as $key => $value) {

    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 21, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 26, $value['denomination']['denomination_1']);
      }
      else {
        array_push($hide_row, 26);
      }
    }
    else {
      array_push($hide_row, 22);
      array_push($hide_row, 26);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 27, $value['denomination']['denomination_2']);
      }
      else {
        array_push($hide_row, 27);
      }

    }
    else {
      array_push($hide_row, 23);
      array_push($hide_row, 27);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 24, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 28, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 28, $value['denomination']['denomination_3']);
      }
      else {
        array_push($hide_row, 28);
      }
    }
    else {
      array_push($hide_row, 24);
      array_push($hide_row, 28);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 25, $value['E_RT']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, (isset($value['dilatation'])?$value['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, (isset($value['dilatation'])?$value['Lo']*$value['dilatation']:''));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, $value['c1_E_montant']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, $value['c1_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['c1_min_stress']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, $value['c2_cycle']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, $value['c2_E_montant']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 38, $value['c2_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 39, $value['c2_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 40, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']-$value['c2_calc_inelastic_strain']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 41, $value['c2_calc_inelastic_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 42, $value['c2_meas_inelastic_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 43, $value['c2_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 44, $value['c2_min_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 45,(isset($value['c2_max_strain'])?(($value['name']=="GE")?$value['c1_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10:$value['c2_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10):''));


    if (strlen($value['c2_max_strain']==0)) {
      $mivie_val_1="";
      $mivie_val_2="";
    }
    else {
      if ($split['c_type_1']=="Max") {
        $mivie_val_1=$value['c2_max_strain'];
      }
      elseif ($split['c_type_1']=="Min") {
        $mivie_val_1=$value['c2_min_strain'];
      }
      elseif ($split['c_type_1']=="Mean") {
        $mivie_val_1=($value['c2_max_strain']+$value['c2_min_strain'])/2;
      }
      elseif ($split['c_type_1']=="Alt") {
        $mivie_val_1=($value['c2_max_strain']-$value['c2_min_strain'])/2;
      }
      elseif ($split['c_type_1']=="Range") {
        $mivie_val_1=$value['c2_max_strain']-$value['c2_min_strain'];
      }
      elseif ($split['c_type_1']=="R") {
        if ($value['c2_max_strain']==0) {
          $mivie_val_1="infini";
        }
        else {
          $mivie_val_1=$value['c2_min_strain']/$value['c2_max_strain'];
        }
      }
      elseif ($split['c_type_1']=="A") {
        if ((($value['c2_max_strain']+$value['c2_min_strain'])/2)==0) {
          $mivie_val_1="infini";
        }
        else {
          $mivie_val_1=(($value['c2_max_strain']-$value['c2_min_strain'])/2)/(($value['c2_max_strain']+$value['c2_min_strain'])/2);
        }
      }


      if ($split['c_type_2']=="Max") {
        $mivie_val_2=$value['c2_max_strain'];
      }
      elseif ($split['c_type_2']=="Min") {
        $mivie_val_2=$value['c2_min_strain'];
      }
      elseif ($split['c_type_2']=="Mean") {
        $mivie_val_2=($value['c2_max_strain']+$value['c2_min_strain'])/2;
      }
      elseif ($split['c_type_2']=="Alt") {
        $mivie_val_2=($value['c2_max_strain']-$value['c2_min_strain'])/2;
      }
      elseif ($split['c_type_2']=="Range") {
        $mivie_val_2=$value['c2_max_strain']-$value['c2_min_strain'];
      }
      elseif ($split['c_type_2']=="R") {
        if ($value['c2_max_strain']==0) {
          $mivie_val_2="infini";
        }
        else {
          $mivie_val_2=$value['c2_min_strain']/$value['c2_max_strain'];
        }
      }
      elseif ($split['c_type_2']=="A") {
        if ((($value['c2_max_strain']+$value['c2_min_strain'])/2)==0) {
          $mivie_val_2="infini";
        }
        else {
          $mivie_val_2=(($value['c2_max_strain']-$value['c2_min_strain'])/2)/(($value['c2_max_strain']+$value['c2_min_strain'])/2);
        }
      }
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $mivie_val_1);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $mivie_val_2);



    $pvEssais->setCellValueByColumnAndRow(1+$col, 49, ($value['Cycle_STL']==0)?"NA":$value['Cycle_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['Cycle_min']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 52, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 53, (($value['Ni']=="")?"NA":$value['Ni']));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 54, (($value['Nf75']=="")?"NA":$value['Nf75']));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 55, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 56, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 57, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));


    $pvEssais->setCellValueByColumnAndRow(1+$col, 65, $value['fractureCust']);



    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] ); //default style
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'9')->applyFromArray( $styleCell['running'] );
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'49:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['running'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "RUNNING");
    }
    elseif (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['unchecked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "Unchecked");
    }
    elseif ($value['valid']=='0') {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'10:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['void'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "VOID");
    }
    else {
      //$pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "");
    }


    //s'il y a un mini, on affiche la lignes
    if ($value['Cycle_min']>0) {
      array_push($show_row, 51);
    }




    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 60)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 59, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'60:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');
      $pvEssais->getRowDimension(60)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(60)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 61, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'61:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');
      $pvEssais->getRowDimension(61)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 61)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(61)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //suppression des doublons et affichage lignes
  $hide_row = array_unique($hide_row);
  $show_row = array_unique($show_row);
  foreach (array_unique($hide_row) as $key => $value) {
    $pvEssais->getRowDimension($value)->setVisible(FALSE);
  }
  foreach (array_unique($show_row) as $key => $value) {
    $pvEssais->getRowDimension($value)->setVisible(TRUE);
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < (1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1) ; $c+=$nbPage) {
    $pvEssais->setBreak( Coordinate::stringFromColumnIndex(1+$c).(1) , Worksheet::BREAK_COLUMN );
    copyRange($pvEssais, 'D1:M8', Coordinate::stringFromColumnIndex(1+$c).(1));
  }





}
ElseIf ($split['test_type_abbr']=="PS" AND $type=="Annexe")	{

  $objPHPExcel = $objReader->load("../templates/Annexe ".$split['test_type_abbr'].$language.$specific.".xlsm");

  $pvEssais=$objPHPExcel->getSheet(0);

  //job number
  $pvEssais->setCellValue("M1", $split['customer'].'-'.$split['job']);
  $pvEssais->setCellValue("A1", $split['customer'].'-'.$split['job'].'-'.$split['split']);

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 19, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 19, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 20, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 20, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;



  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 10; $row <= 59; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['other_1']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 21, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 24, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 25, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 25, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(25)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(24)->setVisible(FALSE);
      $pvEssais->getRowDimension(25)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 26, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 27, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(27)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(26)->setVisible(FALSE);
      $pvEssais->getRowDimension(27)->setVisible(FALSE);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, (isset($value['dilatation'])?$value['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, $value['other_2']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, $value['c1_max_stress']*$area/1000);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['c1_min_stress']*$area/1000);


    $degreeOrientation=180/($value['other_1']+1);
    //    $nbParRotation=$value['runout']/($value['other_1']+1);
    $nbParRotation=$value['runout'];

    $cycleRotation1=($value['Cycle_final']>=($nbParRotation*1))?$nbParRotation:(($value['Cycle_final']==0)?"":($nbParRotation)%$value['Cycle_final']);
    $cycleRotation2=($value['Cycle_final']>=($nbParRotation*2))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*1))?0:($nbParRotation*2)%$value['Cycle_final']);
    $cycleRotation3=($value['Cycle_final']>=($nbParRotation*3))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*2))?0:($nbParRotation*3)%$value['Cycle_final']);
    $cycleRotation4=($value['Cycle_final']>=($nbParRotation*4))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*3))?0:($nbParRotation*4)%$value['Cycle_final']);


    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, $cycleRotation1);
    $pvEssais->setCellValueByColumnAndRow(1+2, 36, '0°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, $value['val_1']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 38, $value['val_3']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 39, $cycleRotation2);
    $pvEssais->setCellValueByColumnAndRow(1+2, 39, ($degreeOrientation).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 40, $value['val_2']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 41, $value['val_4']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 42, $cycleRotation3);
    $pvEssais->setCellValueByColumnAndRow(1+2, 42, ($degreeOrientation*2).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 43, $value['val_6']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 44, $value['val_8']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 45, $cycleRotation4);
    $pvEssais->setCellValueByColumnAndRow(1+2, 45, ($degreeOrientation*3).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $value['val_7']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $value['val_9']);

    //affichage des orientations demandées
    if ($value['other_1']>=1) {
      $pvEssais->getRowDimension(39)->setVisible(TRUE);
      $pvEssais->getRowDimension(40)->setVisible(TRUE);
      $pvEssais->getRowDimension(41)->setVisible(TRUE);
    }
    if ($value['other_1']>=2) {
      $pvEssais->getRowDimension(42)->setVisible(TRUE);
      $pvEssais->getRowDimension(43)->setVisible(TRUE);
      $pvEssais->getRowDimension(44)->setVisible(TRUE);
    }
    if ($value['other_1']>=3) {
      $pvEssais->getRowDimension(45)->setVisible(TRUE);
      $pvEssais->getRowDimension(46)->setVisible(TRUE);
      $pvEssais->getRowDimension(47)->setVisible(TRUE);
    }


    $pvEssais->setCellValueByColumnAndRow(1+$col, 48, $value['val_5']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 49, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $value['Cycle_min']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 52, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 53, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 54, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] ); //default style
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'52:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['running'] );
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $styleCell['running'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "RUNNING");
    }
    elseif (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['unchecked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "Unchecked");
    }
    elseif ($value['valid']==0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'10:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['void'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "VOID");
    }
    else {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "");
    }

    //s'il y a un mini, on affiche la lignes
    if ($value['Cycle_min']>0) {
      $pvEssais->getRowDimension(50)->setVisible(TRUE);
    }




    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 60)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 59, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'60:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');
      $pvEssais->getRowDimension(60)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(60)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 61, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'61:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');
      $pvEssais->getRowDimension(61)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 61)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(61)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < (1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1) ; $c+=$nbPage) {
    $pvEssais->setBreak( Coordinate::stringFromColumnIndex(1+$c).(1) , Worksheet::BREAK_COLUMN );
    copyRange($pvEssais, 'D1:M8', Coordinate::stringFromColumnIndex(1+$c).(1));
  }
}
ElseIf ($split['test_type_abbr']=="PS" AND $type=="Report")	{

  $objPHPExcel = $objReader->load("../templates/Report ".$split['test_type_abbr'].$language.$specific.".xlsm");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');



  $val2Xls = array(

    'B5'=> $split['entreprise'],
    'B6'=> $split['prenom'].' '.$split['nom'],
    'B7'=> (isset($adresse[0])?$split[$adresse[0]]:''),
    'B8'=> (isset($adresse[1])?$split[$adresse[1]]:''),
    'B9'=> (isset($adresse[2])?$split[$adresse[2]]:''),
    'B10'=> (isset($adresse[3])?$split[$adresse[3]]:''),
    'B11'=> (isset($adresse[4])?$split[$adresse[4]]:''),

    'F5' => $jobcomplet,
    'F6'=> (($split['report_rev']=='')?($split['report_rev']+1-1).' - DRAFT':$split['report_rev']),
    'F7'=> date("Y-m-d"),
    'F9'=> $split['po_number'],

    'C20'=> $split['info_jobs_instruction'],
    'C22'=> $split['customer'].'-'.$split['job'],

    'C24'=> $split['ref_matiere'],

    //'C28' si .MA
    'K25'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C26'=> $MArefSubC,
    'C27'=> $MAspecifs,
    'C28'=> $split['dessin'],

    'C36'=> $split['specification'],
    'C37'=> $split['nbep'],
    'C38'=> $split['nbtestdone'],

    'C41'=> $split['waveform'],
    'K42'=> $split['cell_load_capacity'],
    'K43'=> $split['four'],
    'L43'=> $split['coil'],
    'C44'=> $split['ratio1']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //masquage des lignes d'adresse non utilisé
  if (!isset($adresse[3])) {
    $enTete->getRowDimension(10)->setVisible(false);
    $enTete->getRowDimension(11)->setVisible(false);
  }
  if (!isset($adresse[4])) {
    $enTete->getRowDimension(11)->setVisible(false);
  }

  //job number
  $pvEssais->setCellValue("M1", $jobcomplet);

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 14, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 15, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;



  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 10; $row <= 59; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['other_1']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 21, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 24, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 25, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 25, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(25)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(24)->setVisible(FALSE);
      $pvEssais->getRowDimension(25)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 26, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 27, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(27)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(26)->setVisible(FALSE);
      $pvEssais->getRowDimension(27)->setVisible(FALSE);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, (isset($value['dilatation'])?$value['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, $value['other_2']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, $value['c1_max_stress']/$area*1000);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['c1_min_stress']/$area*1000);


    $degreeOrientation=180/($value['other_1']+1);
    $nbParRotation=$value['runout']/($value['other_1']+1);

    $cycleRotation1=($value['Cycle_final']>=($nbParRotation*1))?$nbParRotation:(($value['Cycle_final']==0)?"":($nbParRotation)%$value['Cycle_final']);
    $cycleRotation2=($value['Cycle_final']>=($nbParRotation*2))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*1))?0:($nbParRotation*2)%$value['Cycle_final']);
    $cycleRotation3=($value['Cycle_final']>=($nbParRotation*3))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*2))?0:($nbParRotation*3)%$value['Cycle_final']);
    $cycleRotation4=($value['Cycle_final']>=($nbParRotation*4))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*3))?0:($nbParRotation*4)%$value['Cycle_final']);


    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, $cycleRotation1);
    $pvEssais->setCellValueByColumnAndRow(1+2, 36, '0°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, $value['val_1']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 38, $value['val_3']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 39, $cycleRotation2);
    $pvEssais->setCellValueByColumnAndRow(1+2, 39, ($degreeOrientation).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 40, $value['val_2']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 41, $value['val_4']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 42, $cycleRotation3);
    $pvEssais->setCellValueByColumnAndRow(1+2, 42, ($degreeOrientation*2).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 43, $value['val_6']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 44, $value['val_8']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 45, $cycleRotation4);
    $pvEssais->setCellValueByColumnAndRow(1+2, 45, ($degreeOrientation*3).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $value['val_7']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $value['val_9']);

    //affichage des orientations demandées
    if ($value['other_1']>=1) {
      $pvEssais->getRowDimension(39)->setVisible(TRUE);
      $pvEssais->getRowDimension(40)->setVisible(TRUE);
      $pvEssais->getRowDimension(41)->setVisible(TRUE);
    }
    if ($value['other_1']>=2) {
      $pvEssais->getRowDimension(42)->setVisible(TRUE);
      $pvEssais->getRowDimension(43)->setVisible(TRUE);
      $pvEssais->getRowDimension(44)->setVisible(TRUE);
    }
    if ($value['other_1']>=3) {
      $pvEssais->getRowDimension(45)->setVisible(TRUE);
      $pvEssais->getRowDimension(46)->setVisible(TRUE);
      $pvEssais->getRowDimension(47)->setVisible(TRUE);
    }


    $pvEssais->setCellValueByColumnAndRow(1+$col, 48, $value['val_5']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 49, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $value['Cycle_min']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 52, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 53, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 54, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] ); //default style
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'52:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['running'] );
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $styleCell['running'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "RUNNING");
    }
    elseif (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['unchecked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "Unchecked");
    }
    elseif ($value['valid']==0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'10:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['void'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "VOID");
    }
    else {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "");
    }

    //s'il y a un mini, on affiche la lignes
    if ($value['Cycle_min']>0) {
      $pvEssais->getRowDimension(50)->setVisible(TRUE);
    }




    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 60)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 59, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'60:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');
      $pvEssais->getRowDimension(60)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(60)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 61, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'61:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');
      $pvEssais->getRowDimension(61)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 61)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(61)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < (1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1) ; $c+=$nbPage) {
    $pvEssais->setBreak( Coordinate::stringFromColumnIndex(1+$c).(1) , Worksheet::BREAK_COLUMN );
    copyRange($pvEssais, 'D1:M8', Coordinate::stringFromColumnIndex(1+$c).(1));
  }
}
ElseIf (($split['test_type_abbr']=="Loa" OR $split['test_type_abbr']=="LoS" OR $split['test_type_abbr']=="Dwl" OR $split['test_type_abbr']=="Flx") AND $type=="Annexe")	{

  $objPHPExcel = $objReader->load("../templates/Annexe ".$split['test_type_abbr'].$language.$specific.".xlsm");

  $pvEssais=$objPHPExcel->getSheet(0);

  //job number
  $pvEssais->setCellValue("M1", $split['customer'].'-'.$split['job']);
  $pvEssais->setCellValue("A1", $split['customer'].'-'.$split['job'].'-'.$split['split']);

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 19, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 19, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 20, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 20, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

  $pvEssais->setCellValueByColumnAndRow(1+2, 30, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 31, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 32, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 33, $split['c_unite']);


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;



  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 10; $row <= 58; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 23, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 24, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 27, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(27)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(24)->setVisible(FALSE);
      $pvEssais->getRowDimension(27)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 25, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 25, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 28, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 28, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(28)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(25)->setVisible(FALSE);
      $pvEssais->getRowDimension(28)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 26, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 29, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 29, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(29)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(26)->setVisible(FALSE);
      $pvEssais->getRowDimension(29)->setVisible(FALSE);
    }


    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['max']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, ($value['max']+$value['min'])/2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, ($value['max']-$value['min'])/2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['min']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 48, $value['Cycle_min']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 49, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 52, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 53, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] ); //default style
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'9')->applyFromArray( $styleCell['running'] );
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'49:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['running'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "RUNNING");
    }
    elseif (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['unchecked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "Unchecked");
    }
    elseif ($value['valid']=='0') {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'10:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['void'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "VOID");
    }
    else {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "");
    }

    //s'il y a un mini, on affiche la lignes
    if ($value['Cycle_min']>0) {
      $pvEssais->getRowDimension(48)->setVisible(TRUE);
    }

    //tableau pour le stepcase
    if ($value['stepcase_val']!='' AND $value['Cycle_final']>0) {
      $stepcaseDone=floor($value['Cycle_final']/($value['runout']+1));  //+1 pour eviter qu'un essay NR soit considérer du step d'apres.
      $nbCycleStepcase=$value['Cycle_final']-$value['runout']*$stepcaseDone;
      $stepcaseInitial=($split['c_type_1']==$value['steptype'])?$value['c_type_1_val']:$value['c_type_2_val'];




      $oEprouvette->niveaumaxmin(
        $value['c_1_type'],
        $value['c_2_type'],
        $value['c_type_1_val']+(($value['c_1_type']==$value['steptype'])?($stepcaseDone)*$value['stepcase_val']:0),
        $value['c_type_2_val']+(($value['c_2_type']==$value['steptype'])?($stepcaseDone)*$value['stepcase_val']:0)
      );
      $value['max']=$oEprouvette->MAX();
      $value['min']=$oEprouvette->MIN();

      //on réecrit les niveaux utilisé et les informations du stepcase
      $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['stepcase_val']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['steptype']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $stepcaseDone);

      $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['max']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 31, ($value['max']+$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 32, ($value['max']-$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['min']);

      $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $nbCycleStepcase);

      $pvEssais->getRowDimension(21)->setVisible(TRUE);
      $pvEssais->getRowDimension(22)->setVisible(TRUE);
      $pvEssais->getRowDimension(46)->setVisible(TRUE);
      $pvEssais->getRowDimension(50)->setVisible(TRUE);


    }
    else {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $value['Cycle_final']);
    }


    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 60)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 59, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'60:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');

      $pvEssais->getRowDimension(60)->setRowHeight(-1);
      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(60)->setRowHeight($maxheight * 12.75 + 13.25);

    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 61, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'61:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');

      $pvEssais->getRowDimension(61)->setRowHeight(-1);
      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 61)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(61)->setRowHeight($maxheight * 12.75 + 13.25);

    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < (1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1) ; $c+=$nbPage) {
    $pvEssais->setBreak( Coordinate::stringFromColumnIndex(1+$c).(1) , Worksheet::BREAK_COLUMN );
    copyRange($pvEssais, 'D1:M8', Coordinate::stringFromColumnIndex(1+$c).(1));
  }




}
ElseIf (($split['test_type_abbr']=="Loa" OR $split['test_type_abbr']=="LoS" OR $split['test_type_abbr']=="Dwl" OR $split['test_type_abbr']=="Flx" OR $split['test_type_abbr']=="Rlx"  OR $split['test_type_abbr']=="IRlx" OR $split['test_type_abbr']=="Te" OR $split['test_type_abbr']=="IF" OR $split['test_type_abbr']=="Dep" OR $split['test_type_abbr']=="Oth") AND $type=="Report")	{

  $objPHPExcel = $objReader->load("../templates/Report ".$split['test_type_abbr'].$language.$specific.".xlsm");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');


  $val2Xls = array(

    'B5'=> $split['entreprise'],
    'B6'=> $split['prenom'].' '.$split['nom'],
    'B7'=> (isset($adresse[0])?$split[$adresse[0]]:''),
    'B8'=> (isset($adresse[1])?$split[$adresse[1]]:''),
    'B9'=> (isset($adresse[2])?$split[$adresse[2]]:''),
    'B10'=> (isset($adresse[3])?$split[$adresse[3]]:''),
    'B11'=> (isset($adresse[4])?$split[$adresse[4]]:''),

    'F5' => $jobcomplet,
    'F6'=> (($split['report_rev']=='')?($split['report_rev']+1-1).' - DRAFT':$split['report_rev']),
    'F7'=> date("Y-m-d"),
    'F9'=> $split['po_number'],

    'C20'=> $split['info_jobs_instruction'],
    'C22'=> $split['customer'].'-'.$split['job'],

    'C24'=> $split['ref_matiere'],

    //'C28' si .MA
    'K25'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C26'=> $MArefSubC,
    'C27'=> $MAspecifs,
    'C28'=> $split['dessin'],

    'C36'=> $split['specification'],
    'C37'=> $split['nbep'],
    'C38'=> $split['nbtestdone'],

    'C41'=> $split['waveform'],
    'K42'=> $split['cell_load_capacity'],
    'K43'=> $split['four'],
    'L43'=> $split['coil'],
    'C44'=> $split['ratio1']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //masquage des lignes d'adresse non utilisé
  if (!isset($adresse[3])) {
    $enTete->getRowDimension(10)->setVisible(false);
    $enTete->getRowDimension(11)->setVisible(false);
  }
  if (!isset($adresse[4])) {
    $enTete->getRowDimension(11)->setVisible(false);
  }

  //job number
  $pvEssais->setCellValue("M1", $jobcomplet);

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 19, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 19, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 20, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 20, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

  $pvEssais->setCellValueByColumnAndRow(1+2, 30, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 31, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 32, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 33, $split['c_unite']);


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;

  $hide_row=array();
  $show_row=array();


  foreach ($ep as $key => $value) {


    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 23, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 24, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 27, $value['denomination']['denomination_1']);
      }
      else {
        array_push($hide_row, 27);
      }
    }
    else {
      array_push($hide_row, 24);
      array_push($hide_row, 27);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 25, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 25, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 28, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 28, $value['denomination']['denomination_2']);
      }
      else {
        array_push($hide_row, 28);
      }

    }
    else {
      array_push($hide_row, 25);
      array_push($hide_row, 28);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 26, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 29, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 29, $value['denomination']['denomination_3']);
      }
      else {
        array_push($hide_row, 29);
      }
    }
    else {
      array_push($hide_row, 26);
      array_push($hide_row, 29);
    }


    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['max']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, ($value['max']+$value['min'])/2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, ($value['max']-$value['min'])/2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['min']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 48, $value['Cycle_min']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 49, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 52, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 53, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] ); //default style
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'9')->applyFromArray( $styleCell['running'] );
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'49:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['running'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "RUNNING");
    }
    elseif (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['unchecked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "Unchecked");
    }
    elseif ($value['valid']=='0') {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'10:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['void'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "VOID");
    }
    else {
      //      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "");
    }

    //s'il y a un mini, on affiche la lignes
    if ($value['Cycle_min']>0) {
      array_push($show_row, 48);
    }

    //tableau pour le stepcase
    if ($value['stepcase_val']!='' AND $value['Cycle_final']>0) {
      $stepcaseDone=floor($value['Cycle_final']/($value['runout']+1));  //+1 pour eviter qu'un essay NR soit considérer du step d'apres.
      $nbCycleStepcase=$value['Cycle_final']-$value['runout']*$stepcaseDone;
      $stepcaseInitial=($split['c_type_1']==$value['steptype'])?$value['c_type_1_val']:$value['c_type_2_val'];




      $oEprouvette->niveaumaxmin(
        $value['c_1_type'],
        $value['c_2_type'],
        $value['c_type_1_val']+(($value['c_1_type']==$value['steptype'])?($stepcaseDone)*$value['stepcase_val']:0),
        $value['c_type_2_val']+(($value['c_2_type']==$value['steptype'])?($stepcaseDone)*$value['stepcase_val']:0)
      );
      $value['max']=$oEprouvette->MAX();
      $value['min']=$oEprouvette->MIN();

      //on réecrit les niveaux utilisé et les informations du stepcase
      $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['stepcase_val']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['steptype']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $stepcaseDone);

      $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['max']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 31, ($value['max']+$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 32, ($value['max']-$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['min']);

      $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $nbCycleStepcase);


      array_push($show_row, 21);
      array_push($show_row, 22);
      array_push($show_row, 46);
      array_push($show_row, 50);

    }
    else {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $value['Cycle_final']);
    }


    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 60)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 59, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'60:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');

      $pvEssais->getRowDimension(60)->setRowHeight(-1);
      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(60)->setRowHeight($maxheight * 12.75 + 13.25);

    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 61, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'61:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');

      $pvEssais->getRowDimension(61)->setRowHeight(-1);
      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 61)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(61)->setRowHeight($maxheight * 12.75 + 13.25);

    }

    $col++;
  }

  //suppression des doublons et affichage lignes
  $hide_row = array_unique($hide_row);
  $show_row = array_unique($show_row);
  foreach (array_unique($hide_row) as $key => $value) {
    $pvEssais->getRowDimension($value)->setVisible(FALSE);
  }
  foreach (array_unique($show_row) as $key => $value) {
    $pvEssais->getRowDimension($value)->setVisible(TRUE);
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < (1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1) ; $c+=$nbPage) {
    $pvEssais->setBreak( Coordinate::stringFromColumnIndex(1+$c).(1) , Worksheet::BREAK_COLUMN );
    copyRange($pvEssais, 'D1:M8', Coordinate::stringFromColumnIndex(1+$c).(1));
  }


}
ElseIf ($split['test_type_abbr']=="Ovl" AND $type=="Annexe")	{

  $objPHPExcel = $objReader->load("../templates/Annexe ".$split['test_type_abbr'].$language.$specific.".xlsm");

  $pvEssais=$objPHPExcel->getSheet(0);

  //job number
  $pvEssais->setCellValue("M1", $split['customer'].'-'.$split['job']);
  $pvEssais->setCellValue("A1", $split['customer'].'-'.$split['job'].'-'.$split['split']);


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;



  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 10; $row <= 59; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['nom_eprouvette'].' ');

    if ($value['d_checked']>0) {  //uniquement si checké car difficile savoir si mesure terminée ou non et évite division par 0 sans dimensionnel
      $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['val_1']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['val_2']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 15, (abs($value['val_1']-$value['val_2'])/$value['dim1']));
      $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['val_3']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['val_4']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 18, (abs($value['val_3']-$value['val_4'])/$value['dim1']));
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['val_7']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['val_8']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 21, (abs($value['val_7']-$value['val_8'])/$value['dim1']));
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['val_5']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['val_6']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, (abs($value['val_5']-$value['val_6'])/$value['dim1']));

      //style "checké"
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "");
    }

    if ($value['valid']==0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'10:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['void'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "VOID");
    }




    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 60)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 59, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'60:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');
      $pvEssais->getRowDimension(60)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(60)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 61, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'61:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');
      $pvEssais->getRowDimension(61)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 61)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(61)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < (1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1) ; $c+=$nbPage) {
    $pvEssais->setBreak( Coordinate::stringFromColumnIndex(1+$c).(1) , Worksheet::BREAK_COLUMN );
    copyRange($pvEssais, 'D1:M8', Coordinate::stringFromColumnIndex(1+$c).(1));
  }
}
ElseIf ($split['test_type_abbr']=="PQC" AND $type=="Annexe")	{

  $objPHPExcel = $objReader->load("../templates/Annexe ".$split['test_type_abbr'].$language.$specific.".xlsm");

  $pvEssais=$objPHPExcel->getSheet(0);

  //job number
  $pvEssais->setCellValue("M1", $split['customer'].'-'.$split['job']);
  $pvEssais->setCellValue("A1", $split['customer'].'-'.$split['job'].'-'.$split['split']);


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;



  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 10; $row <= 59; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['nom_eprouvette'].' ');

    if ($value['d_checked']>0) {  //uniquement si checké car difficile savoir si mesure terminée ou non et évite division par 0 sans dimensionnel
      $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['val_1']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['val_2']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 15, (abs($value['val_1']-$value['val_2'])/$value['dim1']));
      $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['val_3']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['val_4']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 18, (abs($value['val_3']-$value['val_4'])/$value['dim1']));
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['val_7']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['val_8']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 21, (abs($value['val_7']-$value['val_8'])/$value['dim1']));
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['val_5']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['val_6']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, (abs($value['val_5']-$value['val_6'])/$value['dim1']));

      //style "checké"
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'9:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['checked'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "");
    }

    if ($value['valid']==0) {
      $pvEssais->getStyle(Coordinate::stringFromColumnIndex(1+$col).'10:'.Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $styleCell['void'] );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 9, "VOID");
    }




    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 60)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 59, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 60, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'60:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');
      $pvEssais->getRowDimension(60)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(60)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 61, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(Coordinate::stringFromColumnIndex(1+$col_q).'61:'.Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');
      $pvEssais->getRowDimension(61)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 61)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(61)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < (1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1) ; $c+=$nbPage) {
    $pvEssais->setBreak( Coordinate::stringFromColumnIndex(1+$c).(1) , Worksheet::BREAK_COLUMN );
    copyRange($pvEssais, 'D1:M8', Coordinate::stringFromColumnIndex(1+$c).(1));
  }
}
ElseIf ($split['test_type_abbr']=="PIX" AND $type=="Annexe")	{

  $objPHPExcel = $objReader->load("../templates/Annexe ".$split['test_type_abbr'].$language.$specific.".xlsm");

  $pvEssais=$objPHPExcel->getSheet(0);

  //job number
  $pvEssais->setCellValue("M1", $split['customer'].'-'.$split['job']);
  $pvEssais->setCellValue("A1", $split['customer'].'-'.$split['job'].'-'.$split['split']);


  $row = 10;
  $col = 0;
  $pair = 0;


  foreach ($ep as $key => $value) {

    if ($value['c_checked']>0) {

      if ($pair==0) {
        copyRange($pvEssais, 'A10:M12', 'A'.$row);

        $pvEssais->setCellValueByColumnAndRow(1, $row, (($value['prefixe']=="")?$value['nom_eprouvette']:$value['prefixe']."-".$value['nom_eprouvette']).' ');
        $prev_value = $pvEssais->getCellByColumnAndRow(15, 1)->getValue();
        $pvEssais->setCellValueByColumnAndRow(1, $row+2, $prev_value.$value['q_commentaire']);
        $pair=1;
      }
      else {
        $pvEssais->setCellValueByColumnAndRow(8, $row, (($value['prefixe']=="")?$value['nom_eprouvette']:$value['prefixe']."-".$value['nom_eprouvette']).' ');
        $prev_value = $pvEssais->getCellByColumnAndRow(15, 1)->getValue();
        $pvEssais->setCellValueByColumnAndRow(8, $row+2, $prev_value.$value['q_commentaire']);
        $pair=0;

        $row=$row+3;
      }

    }

  }



  //zone d'impression
  $finalRow=($pair==1)?$row+3:$row;
  $pvEssais->getPageSetup()->setPrintArea('A1:M'.$finalRow);

}
ElseIf ($split['ST']==1 AND $type=="Report")	{

  $objPHPExcel = $objReader->load("../templates/Report ".$split['test_type_abbr'].$language.$specific.".xlsm");


  $enTete=$objPHPExcel->getSheetByName('En-tête');

  $val2Xls = array(

    'B5'=> $split['entreprise'],
    'B6'=> $split['prenom'].' '.$split['nom'],
    'B7'=> (isset($adresse[0])?$split[$adresse[0]]:''),
    'B8'=> (isset($adresse[1])?$split[$adresse[1]]:''),
    'B9'=> (isset($adresse[2])?$split[$adresse[2]]:''),
    'B10'=> (isset($adresse[3])?$split[$adresse[3]]:''),
    'B11'=> (isset($adresse[4])?$split[$adresse[4]]:''),

    'F5' => $jobcomplet,
    'F6'=> (($split['report_rev']=='')?($split['report_rev']+1-1).' - DRAFT':$split['report_rev']),
    'F7'=> date("Y-m-d"),
    'F9'=> $split['po_number'],

    'C20'=> $split['info_jobs_instruction'],
    'C22'=> $split['customer'].'-'.$split['job'],

    'C24'=> $split['ref_matiere'],

    //'C28' si .MA
    'K25'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C26'=> $MArefSubC,
    'C27'=> $MAspecifs,
    'C28'=> $split['dessin'],

    'C36'=> $split['specification'],
    'C37'=> $split['nbep'],
    'C38'=> $split['nbtestdone'],

    'C40'=> $split['entrepriseST'],
    'C41'=> $split['refSubC']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //masquage des lignes d'adresse non utilisé
  if (!isset($adresse[3])) {
    $enTete->getRowDimension(10)->setVisible(false);
    $enTete->getRowDimension(11)->setVisible(false);
  }
  if (!isset($adresse[4])) {
    $enTete->getRowDimension(11)->setVisible(false);
  }

}
ElseIf ($type=="Job" AND $specific=="_Tube")  {

  $objPHPExcel = $objReader->load("../templates/Report Default".$language.$specific.".xlsm");


  $enTete=$objPHPExcel->getSheetByName('En-tête');

  $val2Xls = array(

    'B5'=> $split['entreprise'],
    'B6'=> $split['prenom'].' '.$split['nom'],
    'B7'=> (isset($adresse[0])?$split[$adresse[0]]:''),
    'B8'=> (isset($adresse[1])?$split[$adresse[1]]:''),
    'B9'=> (isset($adresse[2])?$split[$adresse[2]]:''),
    'B10'=> (isset($adresse[3])?$split[$adresse[3]]:''),
    'B11'=> (isset($adresse[4])?$split[$adresse[4]]:''),

    'F5' => $split['customer'].'-'.$split['job'],
    'F6'=> (($split['report_rev']=='')?($split['report_rev']+1-1).' - DRAFT':$split['report_rev']),
    'F7'=> date("Y-m-d"),
    'F9'=> $split['po_number'],

    'C24'=> $split['info_jobs_instruction'],

    'C31'=> $split['ref_matiere'],

    'C37'=> $split['specification']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //masquage des lignes d'adresse non utilisé
  if (!isset($adresse[3])) {
    $enTete->getRowDimension(10)->setVisible(false);
    $enTete->getRowDimension(11)->setVisible(false);
  }
  if (!isset($adresse[4])) {
    $enTete->getRowDimension(11)->setVisible(false);
  }

  //modification de $jobcomplet pour n'avoir que numéro de job, sans split
  $jobcomplet= $split['customer'].'-'.$split['job'];
}
else {

  $objPHPExcel = $objReader->load("../templates/Report Default".$language.$specific.".xlsm");


  $enTete=$objPHPExcel->getSheetByName('En-tête');

  $val2Xls = array(

    'B5'=> $split['entreprise'],
    'B6'=> $split['prenom'].' '.$split['nom'],
    'B7'=> (isset($adresse[0])?$split[$adresse[0]]:''),
    'B8'=> (isset($adresse[1])?$split[$adresse[1]]:''),
    'B9'=> (isset($adresse[2])?$split[$adresse[2]]:''),
    'B10'=> (isset($adresse[3])?$split[$adresse[3]]:''),
    'B11'=> (isset($adresse[4])?$split[$adresse[4]]:''),

    'F5' => $jobcomplet,
    'F6'=> (($split['report_rev']=='')?($split['report_rev']+1-1).' - DRAFT':$split['report_rev']),
    'F7'=> date("Y-m-d"),
    'F9'=> $split['po_number'],

    'C20'=> $split['info_jobs_instruction'],
    'C22'=> $split['customer'].'-'.$split['job'],

    'C24'=> $split['ref_matiere'],

    //'C28' si .MA
    'K25'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C26'=> $MArefSubC,
    'C27'=> $MAspecifs,
    'C28'=> $split['dessin'],

    'C36'=> $split['specification'],
    'C37'=> $split['nbep'],
    'C38'=> $split['nbtestdone'],

    'C41'=> $split['waveform'],
    'K42'=> $split['cell_load_capacity'],
    'K43'=> $split['four'],
    'L43'=> $split['coil'],
    'C44'=> $split['ratio1']
  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //masquage des lignes d'adresse non utilisé
  if (!isset($adresse[3])) {
    $enTete->getRowDimension(10)->setVisible(false);
    $enTete->getRowDimension(11)->setVisible(false);
  }
  if (!isset($adresse[4])) {
    $enTete->getRowDimension(11)->setVisible(false);
  }

}



//define first sheet as opener
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->setIncludeCharts(TRUE);


//TEMPORAIRE le temps d'avoir tous les jobs crée avec rapport temp
$dir_rapport_temp = $ini['PATH_JOB'].$ep[0]['customer'].'/'.$ep[0]['customer'].'-'.$ep[0]['job'].'/Rapports Temp';
if (!is_dir($dir_rapport_temp)) {
  mkdir($dir_rapport_temp, 0755);
}


$file=$ini['PATH_JOB'].$ep[0]['customer'].'/'.$ep[0]['customer'].'-'.$ep[0]['job'].'/Rapports Temp/'.$jobcomplet.'_'.gmdate('Y-m-d H-i-s').'.xlsm';
$objWriter->save($file);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
header('Content-Disposition: attachment;filename="'.$jobcomplet.'.xlsm"');
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
