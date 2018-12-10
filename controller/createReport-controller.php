<?php
require '../config.php';

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_tbljob']) OR $_GET['id_tbljob']=="")	{
  exit();

}
if (isset($_GET['language']) && $_GET['language']!='')	{
  $language='_'.$_GET['language'];
}
else {
  $language='';
}
if (isset($_GET['version']) && $_GET['version']!='')	{
  $version=$_GET['version'];
}
else {
  $version='';
}


// Rendre votre modèle accessible
include '../models/split-model.php';

$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);

$split=$oSplit->getSplit();

//adresse
$i=0;
if (isset($split['departement'])) {
  $adresse[$i]='departement';
  $i++;
}
if (isset($split['rue1'])) {
  $adresse[$i]='rue1';
  $i++;
}
if (isset($split['rue2'])) {
  $adresse[$i]='rue2';
  $i++;
}
if (isset($split['ville'])) {
  $adresse[$i]='ville';
  $i++;
}
if (isset($split['pays'])) {
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






  if (isset($ep[$k]['split']))		//groupement du nom du job avec ou sans indice
  $jobcomplet= $ep[$k]['customer'].'-'.$ep[$k]['job'].'-'.$ep[$k]['split'];
  else
  $jobcomplet= $ep[$k]['customer'].'-'.$ep[$k]['job'];


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


// Create new \PhpOffice\PhpSpreadsheet\Spreadsheet object
$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
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



$style_gray = array(
  'fill' => array(
    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'color' => array('rgb'=>'C0C0C0')
  )
);

$style_running = array(
  'font'  => array(
    'italic'  => true,
    'color' => array('rgb' => '0000CC'),
    'size'  => 8
  )
);
$style_checked = array(
  'font'  => array(
    'italic'  => false,
    'color' => array('rgb' => '000000')
  )
);
$style_unchecked = array(
  'font'  => array(
    'italic'  => true,
    'color' => array('rgb' => '888888'),
    'size'  => 8
  )
);












If (isset($_GET['Cust']) AND $_GET['Cust']=="SAE" AND $split['test_type_abbr']=="Str")	{

  if( $template!='')  {
    $objPHPExcel = $objReader->load($template);
  }
  else {
    $objPHPExcel = $objReader->load("../templates/SAE_Str3.xlsx");
  }

  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV essai');
  //$piecesJointes=$objPHPExcel->getSheetByName('Pièces Jointes');



  $val2Xls = array(

    'F6' => $jobcomplet,
    'F7'=> date("Y-m-d"),
    'F43'=> $split['tbljob_commentaire_qualite']

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }



  $row = 0; // 1-based index
  $col = 4;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;

  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 1; $row <= 125; $row++) {
      $style = $pvEssais->getStyleByColumnAndRow(1+5, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 6, (isset($value['prefixe'])?$value['prefixe'].'-'.$value['nom_eprouvette'].' ':$value['nom_eprouvette'].' '));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 7, $value['n_fichier']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['date']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $split['ref_matiere']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['machine']);


    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, '12mm manuel');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['type_chauffage']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['gage']);




    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 25, $value['dim2']);
    }
    elseif (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim1']);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['c_temperature']);


    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, (isset($value['max'])?$value['max']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, (isset($value['max'])?$value['min']/$value['max']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, str_replace(array("True","Tapered"), "", $value['waveform']));


    $pvEssais->setCellValueByColumnAndRow(1+$col, 40, $value['c1_E_montant']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $value['c1_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 48, $value['c1_min_stress']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['c1_min_strain']);


    $pvEssais->setCellValueByColumnAndRow(1+$col, 70, $value['c2_cycle']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 71, $value['c2_E_montant']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 73, $value['c2_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 74, $value['c2_min_stress']);

    //PROBLEME VBA qui reecrivait pseudo stress sur l'emplacement min strain
    $value['c2_min_strain']=($value['c2_min_strain']>$value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_delta_strain']:$value['c2_min_strain'];
    $pvEssais->setCellValueByColumnAndRow(1+$col, 76, $value['c2_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 77, $value['c2_min_strain']);



    $pvEssais->setCellValueByColumnAndRow(1+$col, 83, ($value['Cycle_STL']==0)?"":$value['Cycle_STL']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 86, $value['Cycle_final']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 87, (($value['Ni']=="")?"":$value['Ni'].'(Ni)'));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 88, (($value['Nf75']=="")?"":$value['Nf75']));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 119, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 120, $value['Fracture']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 91, (($value['valid']=0)?'Non Valide':'Valide'));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 95, $value['Cycle_min']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 96, (($value['Cycle_final']<$value['Cycle_min'])?'Non conforme':'Conforme'));


    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 123, "RUNNING");
    }
    elseif (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 123, "Unchecked");
    }
    else {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 123, "");
    }



    $pvEssais->setCellValueByColumnAndRow(1+$col, 117, $value['q_commentaire']);



    $col++;
  }



}

//ancienne version. A supprimer après la transition
ElseIf ($version=="OLD" AND ($split['test_type_abbr']=="Loa" OR $split['test_type_abbr']=="Flx"))	{

  $objPHPExcel = $objReader->load("../templates/Report Loa".$language."_OLD.xlsx");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');

  $specifEssais = ($enTete->getCellByColumnAndRow(1+10, 19)->getValue()).$split['specification'].($enTete->getCellByColumnAndRow(1+11, 19)->getValue());
  $val2Xls = array(

    'J5' => $jobcomplet,
    'J9'=> $split['po_number'],
    'C5'=> $split['prenom'].' '.$split['nom'],
    'C6'=> $split['entreprise']."\n".$split['adresse'],
    'J7'=> date("Y-m-d"),

    'E16'=> $split['ref_matiere'],

    'E17'=> $split['info_jobs_instruction'],

    'E19'=> $specifEssais,
    'E26'=> $split['dessin'],

    'E34'=> $split['temperature'].' °C',

    'E38'=> $split['c_frequence'].' Hz',

    'E41'=> $split['waveform']

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 14, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 15, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

  $pvEssais->setCellValueByColumnAndRow(1+2, 27, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 28, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 29, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 30, $split['c_unite']);


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=15;
  $maxheight=0;

  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 5; $row <= 48; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 5, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 6, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 7, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 8, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 9, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 17, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 21, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(21)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(17)->setVisible(FALSE);
      $pvEssais->getRowDimension(21)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 18, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(22)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(18)->setVisible(FALSE);
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 19, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(19)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }


    $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['max']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 28, ($value['max']+$value['min'])/2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, ($value['max']-$value['min'])/2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['min']);


    $pvEssais->setCellValueByColumnAndRow(1+$col, 44, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 45, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'44:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'44')->applyFromArray( $style_running );
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $style_running );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "RUNNING");
    }
    elseif ($value['d_checked']<=0 AND $value['n_fichier']>0) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'47')->applyFromArray( $style_unchecked );

    }
    else {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'47')->applyFromArray( $style_checked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "");
    }



    //tableau pour le stepcase
    if ($value['stepcase_val']!='' AND $value['Cycle_final']>0) {
      $stepcaseDone=floor($value['Cycle_final']/($value['runout']+1));  //+1 pour eviter qu'un essay NR soit considérer du step d'apres.
      $nbCycleStepcase=$value['Cycle_final']-$value['runout']*$stepcaseDone;
      $stepcaseInitial=($split['c_type_1']==$value['steptype'])?$value['c_type_1_val']:$value['c_type_2_val'];




      $oEprouvette->niveaumaxmin(
        $value['c_1_type'],
        $value['c_2_type'],
        $value['c_type_1_val']+(($value['c_1_type']==$value['steptype'])?$stepcaseDone*$value['stepcase_val']:0),
        $value['c_type_2_val']+(($value['c_2_type']==$value['steptype'])?$stepcaseDone*$value['stepcase_val']:0)
      );
      $value['max']=$oEprouvette->MAX();
      $value['min']=$oEprouvette->MIN();

      //on réecrit les niveaux et nb cycle final du step final
      $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['max']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 28, ($value['max']+$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 29, ($value['max']-$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['min']);

      $pvEssais->setCellValueByColumnAndRow(1+$col, 44, $nbCycleStepcase);

      //$texte="Stepcase sur ".$value['steptype']." pas ".$value['stepcase_val']." ".$split['c_unite']." et runout à ".$value['runout']." cycles, niveau initial ".$stepcaseInitial.", final ".($stepcaseInitial+$value['stepcase_val']*($stepcaseDone)).". Cycle d'arrêt au niveau ".$nbCycleStepcase;
      //$texte="Stepcase sur ".$value['steptype'].", pas ".enleverZero($value['stepcase_val'])." ".$split['c_unite'].", runout à ".number_format($value['runout'], 0, '.', ' ')." cycles et niveau initial ".enleverZero($stepcaseInitial)." ".$split['c_unite'].". Arrêt cycle ".number_format($value['Cycle_final'], 0, '.', ' ')." : ".($stepcaseDone+1)."ème pas.";

      //commentaire test incrémental selon la langue
      if ($language=='_USA') {
        $texte="Incrémental test, initial step ".enleverZero($stepcaseInitial)." ".$split['c_unite']." (".$value['steptype']."), step ".enleverZero($value['stepcase_val'])." ".$split['c_unite']." every ".number_format($value['runout'], 0, '.', ' ')." cycles. Total ".number_format($value['Cycle_final'], 0, '.', ' ')." cycles : step ".($stepcaseDone+1).".";
      }
      else {
        $texte="Test incrémental, niveau initial ".enleverZero($stepcaseInitial)." ".$split['c_unite']." (".$value['steptype']."), pas ".enleverZero($value['stepcase_val'])." ".$split['c_unite']." tous les ".number_format($value['runout'], 0, '.', ' ')." cycles. Total ".number_format($value['Cycle_final'], 0, '.', ' ')." cycles : pas ".($stepcaseDone+1).".";
      }



      $value['q_commentaire']=$texte.' '.$value['q_commentaire'];
    }


    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 50, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 50)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 48, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 50, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'50:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'50');
      $pvEssais->getRowDimension(50)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(50)->setRowHeight($maxheight * 12.75 + 13.25);


    }


    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 51, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'51:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'51');
      $pvEssais->getRowDimension(51)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 51)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(51)->setRowHeight($maxheight * 12.75 + 13.25);


    }


    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(50));



  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c).(1) , \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN );
    $pvEssais->setCellValueByColumnAndRow(1+$c-1, 1, $jobcomplet);
    $pvEssais->setCellValueByColumnAndRow(1+$c-3, 1,$pvEssais->getCellByColumnAndRow(1+$nbPage, 1)->getValue());
    $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c-3).(1))->getFont()->setBold(true);
  }






}
ElseIf ($version=="OLD" AND ($split['test_type_abbr']=="LoS" OR $split['test_type_abbr']=="Dwl"))	{

  $objPHPExcel = $objReader->load("../templates/Report LoS".$language."_OLD.xlsx");





  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');

  $specifEssais = ($enTete->getCellByColumnAndRow(1+10, 19)->getValue()).$split['specification'].($enTete->getCellByColumnAndRow(1+11, 19)->getValue());
  $val2Xls = array(

    'J5' => $jobcomplet,
    'J9'=> $split['po_number'],
    'C5'=> $split['prenom'].' '.$split['nom'],
    'C6'=> $split['entreprise']."\n".$split['adresse'],
    'J7'=> date("Y-m-d"),

    'E16'=> $split['ref_matiere'],

    'E17'=> $split['info_jobs_instruction'],

    'E19'=> $specifEssais,
    'E26'=> $split['dessin'],

    'E34'=> $split['temperature'].' °C',

    'E38'=> $split['c_frequence'].' Hz',

    'E41'=> $split['waveform']

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 14, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 15, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

  $pvEssais->setCellValueByColumnAndRow(1+2, 27, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 28, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 29, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 30, $split['c_unite']);


  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=15;
  $maxheight=0;

  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 5; $row <= 48; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 5, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 6, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 7, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 8, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 9, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 17, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 21, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(21)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(17)->setVisible(FALSE);
      $pvEssais->getRowDimension(21)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 18, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(22)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(18)->setVisible(FALSE);
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 19, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(19)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }


    $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['max']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 28, ($value['max']+$value['min'])/2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, ($value['max']-$value['min'])/2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['min']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 41, $value['Cycle_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 44, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 45, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'44:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'44')->applyFromArray( $style_running );
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $style_running );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "RUNNING");
    }
    elseif ($value['d_checked']<=0 AND $value['n_fichier']>0) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'47')->applyFromArray( $style_unchecked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "Unchecked");
    }
    else {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'47')->applyFromArray( $style_checked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "");
    }


    if ($value['c_cycle_STL']!='') {  //on affiche les lignes du STL
      $pvEssais->getRowDimension(13)->setVisible();
      $pvEssais->getRowDimension(41)->setVisible();
    }


    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 50, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 50)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 48, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 50, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'50:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'50');
      $pvEssais->getRowDimension(50)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(50)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 51, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'51:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'51');
      $pvEssais->getRowDimension(51)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 51)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(51)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(50));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c).(1) , \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN );
    $pvEssais->setCellValueByColumnAndRow(1+$c-1, 1, $jobcomplet);
    $pvEssais->setCellValueByColumnAndRow(1+$c-3, 1,$pvEssais->getCellByColumnAndRow(1+$nbPage, 1)->getValue());
    $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c-3).(1))->getFont()->setBold(true);
  }




}
ElseIf ($version=="OLD" AND $split['test_type_abbr']=="Str")	{

  $objPHPExcel = $objReader->load("../templates/Report Str".$language."_OLD.xlsx");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');

  $specifEssais = ($enTete->getCellByColumnAndRow(1+10, 19)->getValue()).$split['specification'].($enTete->getCellByColumnAndRow(1+11, 19)->getValue());

  $val2Xls = array(

    'J5' => $jobcomplet,
    'J9'=> $split['po_number'],
    'C5'=> $split['prenom'].' '.strtoupper($split['nom']),
    'C6'=> $split['entreprise']."\n".$split['adresse'],
    'J7'=> date("Y-m-d"),
    'E16'=> $split['ref_matiere'],

    'E17'=> $split['info_jobs_instruction'],
    'E19'=> $specifEssais,

    'E26'=> $split['dessin'],

    'E34'=> $split['temperature'].' °C',

    'H38'=> $split['c_frequence'].' Hz',
    'H39'=> $split['c_frequence_STL'].' Hz',

    'E41'=> $split['waveform']

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

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
    for ($row = 5; $row <= 48; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 5, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 6, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 7, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 8, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 9, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['c_type_1_val']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['c_type_2_val']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, str_replace(array("TRUE","TAPERED"), "", strtoupper($value['waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 17, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 21, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(21)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(17)->setVisible(FALSE);
      $pvEssais->getRowDimension(21)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 18, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(22)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(18)->setVisible(FALSE);
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 19, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(19)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['E_RT']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 24, (isset($value['dilatation'])?$value['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 25, (isset($value['dilatation'])?$value['Lo']*$value['dilatation']:''));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['c1_E_montant']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 28, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, $value['c1_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['c1_min_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, $value['c2_cycle']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, (isset($value['c2_max_stress'])?$value['c2_max_stress']-$value['c2_min_stress']:''));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['c2_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, $value['c2_min_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['c2_E_montant']);

    //PROBLEME VBA qui reecrivait pseudo stress sur l'emplacement min strain
    $value['c2_min_strain']=($value['c2_min_strain']>$value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_delta_strain']:$value['c2_min_strain'];


    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']-$value['c2_calc_inelastic_strain']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 38, $value['c2_calc_inelastic_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 39, $value['c2_meas_inelastic_strain']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 40,(isset($value['c2_max_strain'])?(($value['name']=="GE")?$value['c1_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10:$value['c2_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10):''));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 41, ($value['Cycle_STL']==0)?"NA":$value['Cycle_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 42, (($value['Ni']=="")?"NA":$value['Ni']));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 43, (($value['Nf75']=="")?"NA":$value['Nf75']));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 44, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 45, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $value['Fracture']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'44:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'44')->applyFromArray( $style_running );
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $style_running );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "RUNNING");
    }
    elseif (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'47')->applyFromArray( $style_unchecked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "Unchecked");
    }
    else {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'47')->applyFromArray( $style_checked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "");
    }



    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 50, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 50)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 48, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 50, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'50:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'50');
      $pvEssais->getRowDimension(50)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(50)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 51, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'51:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'51');
      $pvEssais->getRowDimension(51)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 51)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(51)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(50));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)+$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c).(1) , \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN );
    $pvEssais->setCellValueByColumnAndRow(1+$c-1, 1, $jobcomplet);
    $pvEssais->setCellValueByColumnAndRow(1+$c-3, 1,$pvEssais->getCellByColumnAndRow(1+$nbPage, 1)->getValue());
    $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c-3).(1))->getFont()->setBold(true);
  }







}
ElseIf ($version=="OLD" AND $split['test_type_abbr']=="PS")	{

  $objPHPExcel = $objReader->load("../templates/Report PS".$language."_OLD.xlsx");

  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');

  $val2Xls = array(

    'J5' => $jobcomplet,
    'J9'=> $split['po_number'],
    'C5'=> $split['genre'].' '.$split['prenom'].' '.$split['nom'],
    'C6'=> $split['adresse'],

    'E16'=> $split['ref_matiere'],

    'E17'=> $split['info_jobs_instruction'],

    'E23'=> $split['specification'],
    'E26'=> $split['dessin'],

    'E34'=> $split['temperature'].' °C',

    'H38'=> $split['c_frequence'].' Hz',
    'H39'=> $split['c_frequence_STL'].' Hz',

    'E41'=> $split['waveform']

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+1, 14, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+3, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+1, 15, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+3, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

  //on masque l'orientation 2 s'il n'y en a pas
  if ($split['other_1']==0) {
    for ($j=34; $j <=40 ; $j++) {
      $pvEssais->getRowDimension($j)->setVisible(FALSE);
    }
  }


  $row = 0; // 1-based index
  $col = 4;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;

  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 5; $row <= 48; $row++) {
      $style = $pvEssais->getStyleByColumnAndRow(1+4, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 5, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 6, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 7, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 8, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 9, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['c_type_1_val']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['c_type_2_val']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, str_replace(array("True","Tapered"), "", $value['waveform']));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 17, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 21, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(21)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(17)->setVisible(FALSE);
      $pvEssais->getRowDimension(21)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 18, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(22)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(18)->setVisible(FALSE);
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 19, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(19)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['E_RT']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 24, (isset($value['dilatation'])?$value['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 25, (isset($value['dilatation'])?$value['Lo']*$value['dilatation']:''));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['other_1']);


    $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 28, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, $value['c1_max_stress']/$area*1000);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['c1_min_stress']/$area*1000);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, $value['val_1']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['val_2']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, $value['c2_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['c2_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, $value['c2_max_stress']/$area*1000);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, $value['c2_min_stress']/$area*1000);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 38, $value['val_3']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 39, $value['runout']*2);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 40, $value['val_4']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $value['val_5']);



    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'44:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'44')->applyFromArray( $style_running );
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $style_running );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "RUNNING");
    }
    elseif ($value['d_checked']<=0 AND $value['n_fichier']>0) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'47')->applyFromArray( $style_unchecked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "Unchecked");
    }
    else {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'47')->applyFromArray( $style_checked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "");
    }

    $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
    //suppression commentaire precedent si 1er de la cellule, sinon recup des autres
    if ($col_q==$col) {
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 50, '');
      $prev_value='';
    }
    else {
      $prev_value = $pvEssais->getCellByColumnAndRow(1+$col_q, 50)->getValue();
    }


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 48, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 50, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'50:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'50');
      $pvEssais->getRowDimension(50)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(50)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 51, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'51:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'51');
      $pvEssais->getRowDimension(51)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 51)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(51)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(50));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c).(1) , \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN );
  }







}


ElseIf ($split['test_type_abbr']=="Loa" OR $split['test_type_abbr']=="LoS" OR $split['test_type_abbr']=="Dwl" OR $split['test_type_abbr']=="Flx")	{

  $objPHPExcel = $objReader->load("../templates/Report Loa".$version.$language.".xlsx");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');


  $val2Xls = array(

    'B3'=> $split['test_type_cust'],

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

    'C28'=> $split['ref_matiere'],
    'C29'=> $split['nbep'],
    'C30'=> $split['nbtestdone'],

    //'C28' si .MA
    'K32'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C33'=> $MArefSubC,
    'C34'=> $MAspecifs,
    'C35'=> $split['dessin'],

    'C39'=> $split['specification'],

    'C42'=> $split['waveform'],
    'K43'=> $split['cell_load_capacity'],
    'C44'=> $split['ratio1'],
    'K45'=> $split['four'],
    'L45'=> $split['coil']
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

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 14, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 15, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

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
  $nbPage=15;
  $maxheight=0;



  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 5; $row <= 61; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 5, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 6, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 7, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 8, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 9, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 19, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(19)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 20, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 24, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(24)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(20)->setVisible(FALSE);
      $pvEssais->getRowDimension(24)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 21, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 25, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 25, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(25)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(21)->setVisible(FALSE);
      $pvEssais->getRowDimension(25)->setVisible(FALSE);
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
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'49:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_running );
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $style_running );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "RUNNING");
    }
    elseif ($value['d_checked']<=0 AND $value['n_fichier']>0) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_unchecked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "Unchecked");
    }
    else {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_checked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "");
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
      $pvEssais->setCellValueByColumnAndRow(1+$col, 16, $value['stepcase_val']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['steptype']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 29, $stepcaseDone);

      $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['max']);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 31, ($value['max']+$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 32, ($value['max']-$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['min']);

      $pvEssais->setCellValueByColumnAndRow(1+$col, 50, $nbCycleStepcase);

      $pvEssais->getRowDimension(16)->setVisible(TRUE);
      $pvEssais->getRowDimension(17)->setVisible(TRUE);
      $pvEssais->getRowDimension(29)->setVisible(TRUE);
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
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'60:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');
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
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'61:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');
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
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c).(1) , \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN );
    $pvEssais->setCellValueByColumnAndRow(1+$c-1, 1, $jobcomplet);
    $pvEssais->setCellValueByColumnAndRow(1+$c-3, 1,$pvEssais->getCellByColumnAndRow(1+$nbPage, 1)->getValue());
    $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c-3).(1))->getFont()->setBold(true);
  }






}
ElseIf ($split['test_type_abbr']=="Str")	{

  $objPHPExcel = $objReader->load("../templates/Report Str".$version.$language.".xlsx");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');


  $val2Xls = array(

    'B3'=> $split['test_type_cust'],

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

    'C28'=> $split['ref_matiere'],
    'C29'=> $split['nbep'],
    'C30'=> $split['nbtestdone'],

    //'C28' si .MA
    'K32'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C33'=> $MArefSubC,
    'C34'=> $MAspecifs,
    'C35'=> $split['dessin'],

    'C39'=> $split['specification'],

    'C42'=> $split['waveform'],
    'K43'=> $split['cell_load_capacity'],
    'C44'=> $split['ratio1'],
    'K45'=> $split['four'],
    'L45'=> $split['coil']
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

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(1+0, 14, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 15, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

  $pvEssais->setCellValueByColumnAndRow(1+0, 41, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 41, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(1+0, 42, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 42, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");


  $pvEssais->setCellValueByColumnAndRow(1+2, 27, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 28, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 29, $split['c_unite']);
  $pvEssais->setCellValueByColumnAndRow(1+2, 30, $split['c_unite']);


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
    for ($row = 5; $row <= 61; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 5, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 6, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 7, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 8, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 9, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 17, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 21, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(21)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(17)->setVisible(FALSE);
      $pvEssais->getRowDimension(21)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 18, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(22)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(18)->setVisible(FALSE);
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 19, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(19)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['E_RT']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 24, (isset($value['dilatation'])?$value['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 25, (isset($value['dilatation'])?$value['Lo']*$value['dilatation']:''));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['c1_E_montant']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 28, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, $value['c1_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['c1_min_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, $value['c2_cycle']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, (isset($value['c2_max_stress'])?$value['c2_max_stress']-$value['c2_min_stress']:''));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['c2_max_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, $value['c2_min_stress']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['c2_E_montant']);

    //PROBLEME VBA qui reecrivait pseudo stress sur l'emplacement min strain
    $value['c2_min_strain']=($value['c2_min_strain']>$value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_delta_strain']:$value['c2_min_strain'];


    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']-$value['c2_calc_inelastic_strain']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 38, $value['c2_calc_inelastic_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 39, $value['c2_meas_inelastic_strain']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 40,(isset($value['c2_max_strain'])?(($value['name']=="GE")?$value['c1_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10:$value['c2_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10):''));

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

    $pvEssais->setCellValueByColumnAndRow(1+$col, 41, $mivie_val_1);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 42, $mivie_val_2);






    $pvEssais->setCellValueByColumnAndRow(1+$col, 45, ($value['Cycle_STL']==0)?"NA":$value['Cycle_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $value['Cycle_min']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 48, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 49, (($value['Ni']=="")?"NA":$value['Ni']));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 50, (($value['Nf75']=="")?"NA":$value['Nf75']));

    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 52, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 53, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'46:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_running );
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $style_running );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "RUNNING");
    }
    elseif ($value['d_checked']<=0 AND $value['n_fichier']>0) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_unchecked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "Unchecked");
    }
    else {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_checked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "");
    }

    //s'il y a un mini, on affiche la lignes
    if ($value['Cycle_min']>0) {
      $pvEssais->getRowDimension(47)->setVisible(TRUE);
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
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'60:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');
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
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'61:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');
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
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c).(1) , \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN );
    $pvEssais->setCellValueByColumnAndRow(1+$c-1, 1, $jobcomplet);
    $pvEssais->setCellValueByColumnAndRow(1+$c-3, 1,$pvEssais->getCellByColumnAndRow(1+$nbPage, 1)->getValue());
    $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c-3).(1))->getFont()->setBold(true);
  }






}
ElseIf ($split['test_type_abbr']=="PS")	{

  $objPHPExcel = $objReader->load("../templates/Report PS".$version.$language.".xlsx");


  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');


  $val2Xls = array(

    'B3'=> $split['test_type_cust'],

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

    'C28'=> $split['ref_matiere'],
    'C29'=> $split['nbep'],
    'C30'=> $split['nbtestdone'],

    //'C28' si .MA
    'K32'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C33'=> $MArefSubC,
    'C34'=> $MAspecifs,
    'C35'=> $split['dessin'],

    'C39'=> $split['specification'],

    'C42'=> $split['waveform'],
    'K43'=> $split['cell_load_capacity'],
    'C44'=> $split['ratio1'],
    'K45'=> $split['four'],
    'L45'=> $split['coil']
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
  $nbPage=15;
  $maxheight=0;



  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 5; $row <= 59; $row++) {
      $style = $pvEssais->getStyleByColumnAndrow(1+3, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 5, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 6, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 7, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 8, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 9, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 10, $value['date']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 11, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['c_type_1_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['c_type_2_val']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 17, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 21, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(21)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(17)->setVisible(FALSE);
      $pvEssais->getRowDimension(21)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 18, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 22, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 22, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(22)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(18)->setVisible(FALSE);
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow(1+$col, 19, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1+1, 19, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1+1, 23, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(19)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 24, (isset($value['dilatation'])?$value['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 26, $value['other_2']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 27, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 28, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 29, $value['c1_max_stress']/$area*1000);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 30, $value['c1_min_stress']/$area*1000);


    $degreeOrientation=180/($value['other_1']+1);
    $nbParRotation=$value['runout']/($value['other_1']+1);

    $cycleRotation1=($value['Cycle_final']>=($nbParRotation*1))?$nbParRotation:(($value['Cycle_final']==0)?"":($nbParRotation)%$value['Cycle_final']);
    $cycleRotation2=($value['Cycle_final']>=($nbParRotation*2))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*1))?0:($nbParRotation*2)%$value['Cycle_final']);
    $cycleRotation3=($value['Cycle_final']>=($nbParRotation*3))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*2))?0:($nbParRotation*3)%$value['Cycle_final']);
    $cycleRotation4=($value['Cycle_final']>=($nbParRotation*4))?$nbParRotation:(($value['Cycle_final']<($nbParRotation*3))?0:($nbParRotation*4)%$value['Cycle_final']);


    $pvEssais->setCellValueByColumnAndRow(1+$col, 31, $cycleRotation1);
    $pvEssais->setCellValueByColumnAndRow(1+2, 31, '0°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 32, $value['val_1']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 33, $value['val_3']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 34, $cycleRotation2);
    $pvEssais->setCellValueByColumnAndRow(1+2, 34, ($degreeOrientation).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 35, $value['val_2']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 36, $value['val_4']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 37, $cycleRotation3);
    $pvEssais->setCellValueByColumnAndRow(1+2, 37, ($degreeOrientation*2).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 38, $value['val_6']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 39, $value['val_8']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 40, $cycleRotation4);
    $pvEssais->setCellValueByColumnAndRow(1+2, 40, ($degreeOrientation*3).'°');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 41, $value['val_7']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 42, $value['val_9']);

    //affichage des orientations demandées
    if ($value['other_1']>=1) {
      $pvEssais->getRowDimension(34)->setVisible(TRUE);
      $pvEssais->getRowDimension(35)->setVisible(TRUE);
      $pvEssais->getRowDimension(36)->setVisible(TRUE);
    }
    if ($value['other_1']>=2) {
      $pvEssais->getRowDimension(37)->setVisible(TRUE);
      $pvEssais->getRowDimension(38)->setVisible(TRUE);
      $pvEssais->getRowDimension(39)->setVisible(TRUE);
    }
    if ($value['other_1']>=3) {
      $pvEssais->getRowDimension(40)->setVisible(TRUE);
      $pvEssais->getRowDimension(41)->setVisible(TRUE);
      $pvEssais->getRowDimension(42)->setVisible(TRUE);
    }


    $pvEssais->setCellValueByColumnAndRow(1+$col, 43, $value['val_5']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 46, $value['runout']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 47, $value['Cycle_min']);

    $pvEssais->setCellValueByColumnAndRow(1+$col, 48, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 51, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 52, $value['Fracture']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 53, ceil(($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'46:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_running );
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4')->applyFromArray( $style_running );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "RUNNING");
    }
    elseif ($value['d_checked']<=0 AND $value['n_fichier']>0) {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_unchecked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "Unchecked");
    }
    else {
      $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'4:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col).'58')->applyFromArray( $style_checked );
      $pvEssais->setCellValueByColumnAndRow(1+$col, 4, "");
    }

    //s'il y a un mini, on affiche la lignes
    if ($value['Cycle_min']>0) {
      $pvEssais->getRowDimension(47)->setVisible(TRUE);
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
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'60:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'60');
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
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'61:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'61');
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
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(61));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c).(1) , \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN );
    $pvEssais->setCellValueByColumnAndRow(1+$c-1, 1, $jobcomplet);
    $pvEssais->setCellValueByColumnAndRow(1+$c-3, 1,$pvEssais->getCellByColumnAndRow(1+15, 1)->getValue());
    $pvEssais->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c-3).(1))->getFont()->setBold(true);
  }






}
ElseIf ($split['test_type_abbr']=="Ovl")	{

  $objPHPExcel = $objReader->load("../templates/Report Ovl".$language.".xlsx");


  $pvEssais=$objPHPExcel->getSheetByName('OVL');


  $val2Xls = array(

    'L1' => $jobcomplet,

    'L7'=> $split['ref_matiere']

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $pvEssais->setCellValue($key, $value);
  }



  $row = 0; // 1-based index
  $col = 2;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;

  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 12; $row <= 24; $row++) {
      $style = $pvEssais->getStyleByColumnAndRow(1+2, $row);
      $dstCell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow(1+$col, 12, $value['prefixe'].' ');
    $pvEssais->setCellValueByColumnAndRow(1+$col, 13, $value['nom_eprouvette'].' ');

    $pvEssais->setCellValueByColumnAndRow(1+$col, 14, $value['val_1']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 15, $value['val_2']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 16, (abs($value['val_1']-$value['val_2'])/$value['dim1']));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 17, $value['val_3']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 18, $value['val_4']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 19, (abs($value['val_3']-$value['val_4'])/$value['dim1']));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 20, $value['val_7']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 21, $value['val_8']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 22, (abs($value['val_7']-$value['val_8'])/$value['dim1']));
    $pvEssais->setCellValueByColumnAndRow(1+$col, 23, $value['val_5']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 24, $value['val_6']);
    $pvEssais->setCellValueByColumnAndRow(1+$col, 25, (abs($value['val_5']-$value['val_6'])/$value['dim1']));


    if ($value['q_commentaire']!="") {

      $nb_q+=1; //on incremente le nombre de commentaire

      $pvEssais->setCellValueByColumnAndRow(1+$col, 28, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 30, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'30:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'50');
      $pvEssais->getRowDimension(30)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(30)->setRowHeight($maxheight * 12.75 + 13.25);


    }
    if ($split['tbljob_commentaire_qualite']!="") {

      $pvEssais->setCellValueByColumnAndRow(1+$col_q, 51, $split['tbljob_commentaire_qualite']);
      $pvEssais->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q).'51:'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$col_q+($nbPage-1)).'51');
      $pvEssais->getRowDimension(51)->setRowHeight(-1);


      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $pvEssais->getCellByColumnAndRow(1+$col_q, 51)->getValue());
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(51)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+(ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(50));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1+$c).(1) , \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN );
  }







}

else {

  $objPHPExcel = $objReader->load("../templates/Report Default".$version.$language.".xlsx");


  $enTete=$objPHPExcel->getSheetByName('En-tête');

  $val2Xls = array(

    'G1'=> $split['test_type_cust'],

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
    'C21'=> $split['customer'].'-'.$split['job'],

    'C28'=> $split['ref_matiere'],
    'C29'=> $split['nbep'],
    'C30'=> $split['nbtestdone'],

    //'C28' si .MA
    'K32'=> ((isset($MArefSubC) AND $MArefSubC!="")?1:0),
    'C33'=> $MArefSubC,
    'C34'=> $MAspecifs,
    'C35'=> $split['dessin'],

    'C39'=> $split['specification'],

    'C42'=> $split['waveform'],
    'K43'=> $split['cell_load_capacity'],
    'C44'=> $split['ratio1'],
    'K45'=> $split['four'],
    'L45'=> $split['coil']
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




//exit;



$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->setIncludeCharts(TRUE);


//TEMPORAIRE le temps d'avoir tous les jobs crée avec rapport temp
$dir_rapport_temp = $PATH_JOB.$ep[0]['customer'].'/'.$ep[0]['customer'].'-'.$ep[0]['job'].'/Rapports Temp';
if (!is_dir($dir_rapport_temp)) {
  mkdir($dir_rapport_temp, 0755);
}


$file=$PATH_JOB.$ep[0]['customer'].'/'.$ep[0]['customer'].'-'.$ep[0]['job'].'/Rapports Temp/'.$jobcomplet.'_'.gmdate('Y-m-d H-i-s').'.xlsx';
$objWriter->save($file);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$jobcomplet.'.xlsx"');
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
