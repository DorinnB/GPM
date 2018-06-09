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

//groupement du nom du job avec ou sans indice
if (isset($essai['split'])) {
  $essai['jobcomplet']= $essai['customer'].'-'.$essai['job'].'-'.$essai['split'];
}
else {
  $essai['jobcomplet']= $essai['customer'].'-'.$essai['job'];
}


$essai['area'] = $oEprouvette->calculArea($essai['id_dessin_type'],$essai['dim1'],$essai['dim2'],$essai['dim3'])['area'];







$oEprouvette->niveaumaxmin($essai['c_1_type'], $essai['c_2_type'], $essai['c_type_1_val'], $essai['c_type_2_val']);
$essai['max'] = $oEprouvette->MAX();
$essai['min'] = $oEprouvette->MIN();
$essai['runout'] = ($essai['runout'] > 0)?$essai['runout']:9999999999;


$essai['HighTemperatureTested'] = ($essai['c_temperature'] >= 50)?'Yes':'No';

$true=($essai['signal_true']==1)?'True':'';
$tapered=($essai['signal_tapered']==1)?'Tapered':'';
if ($essai['c_waveform']=='Sinus') {
  $waveform='Sine';
}
elseif ($essai['c_waveform']=='Triangle') {
  $waveform='Ramp';
}
elseif ($essai['c_waveform']=='Carre') {
  $waveform='Square';
}
else {
  $waveform='INCONNU';
}

$essai['ts_waveform'] = $true.$waveform.$tapered;

$essai['tempCorrected']=$oEprouvette->getTempCorrected();

$essai['GE']=($essai['GE'] == 1 )?'Yes':'No';

//Crp
$essai['niveau1']=($essai['c_unite']=="MPa")?$essai['c_type_1_val']*$essai['area']/1000:$essai['c_type_1_val'];
$essai['niveau2']=($essai['c_unite']=="MPa")?$essai['c_type_3_val']*$essai['area']/1000:$essai['c_type_3_val'];
$essai['rampe1']=$essai['c_type_2_val'];
$essai['rampe2']=$essai['c_type_4_val'];



//Loa & LoS
$niveauMax=($essai['c_unite']=="MPa")?$oEprouvette->MAX()*$essai['area']/1000:$oEprouvette->MAX();
$niveauMin=($essai['c_unite']=="MPa")?$oEprouvette->MIN()*$essai['area']/1000:$oEprouvette->MIN();

$essai['ts_niveau1'] = array($niveauMax);
$essai['ts_niveau2'] = array($niveauMin);
$essai['ts_cycle'] = array($essai['runout']);
$essai['ts_frequence'] = array($essai['c_frequence']);
if ($essai['c_cycle_STL']!="") {
  array_push($essai['ts_niveau1'], $niveauMax);
  array_push($essai['ts_niveau2'], $niveauMin);
  $essai['ts_cycle'] = array($essai['c_cycle_STL'],$essai['runout']);
  array_push($essai['ts_frequence'], $essai['c_frequence_STL']);
}

if ($essai['stepcase_val']!="") {
  for ($i=1; $i <10 ; $i++) {
    $oEprouvette->niveaumaxmin(
      $essai['c_1_type'],
      $essai['c_2_type'],
      $essai['c_type_1_val']+(($essai['c_1_type']==$essai['steptype'])?$i*$essai['stepcase_val']:0),
      $essai['c_type_2_val']+(($essai['c_2_type']==$essai['steptype'])?$i*$essai['stepcase_val']:0)
    );
    $niveauMax=($essai['c_unite']=="MPa")?$oEprouvette->MAX()*$essai['area']/1000:$oEprouvette->MAX();
    $niveauMin=($essai['c_unite']=="MPa")?$oEprouvette->MIN()*$essai['area']/1000:$oEprouvette->MIN();

    array_push($essai['ts_niveau1'], $niveauMax);
    array_push($essai['ts_niveau2'], $niveauMin);
    array_push($essai['ts_cycle'], $essai['runout']*($i+1));
    array_push($essai['ts_frequence'], $essai['c_frequence']);
  }
}




//on charge le model
include '../models/lstXMLforTS-model.php';
$oXMLforTS = new XMLforTSModel($db);
//on ajoute dans $variableTS_GPM les equivalent entre les noms de la base et les noms de variables TS
$variableTS_GPM = array();
$variableUnit = array();



foreach ($oXMLforTS->getAllXMLforTS($essai['id_test_type']) as $key => $value) {
  $variableTS_GPM[$value['ts']]=$essai[$value['xml']];
  $variableUnit[$value['ts']]=$value['unit'];
}

//var_dump($variableTS_GPM);
//on ajoute manuellement le nom d'ep
//  $variableTS_GPM['SpecimenId'] = isset($essai['prefixe'])?$essai['prefixe'].'-'.$essai['nom_eprouvette']:$essai['nom_eprouvette'];



$xml_doc = new DOMDocument('1.0', 'utf-8');

$xml_doc->appendChild($AoAoVariableData_node = $xml_doc->createElement('ArrayOfArrayOfVariableData', ''));

$AoAoVariableData_node->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
$AoAoVariableData_node->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');


$AoVariableData_node = $xml_doc->createElement('ArrayOfVariableData');
$AoAoVariableData_node->appendChild($AoVariableData_node);


foreach ($variableTS_GPM as $key => $value) {
  $VariableData_node = $xml_doc->createElement('VariableData');

  $VariableData_node->appendChild($name_node = $xml_doc->createElement('Name', $key));

  $VariableData_node->appendChild($Values = $xml_doc->createElement('Values'));
  if (is_array($value)) {
foreach ($value as $k => $v) {
  $Values->appendChild($Value = $xml_doc->createElement('Value', $v));
}
  }
  else {
  $Values->appendChild($Value = $xml_doc->createElement('Value', $value));
  }

  if (isset($variableUnit[$key])) {
    $VariableData_node->appendChild($unit_node = $xml_doc->createElement('Unit', $variableUnit[$key]));
  }


  $AoVariableData_node->appendChild($VariableData_node);
}


$xml_string = $xml_doc->saveXML();

//echo $xml_string;



$fp = fopen('//SRVDC/DONNEES/labo/Computer/BDD/XMLforTS/'.$essai['n_fichier'].'.xml', 'w');
fwrite($fp, $xml_string);
fclose($fp);
$fp = fopen('//SRVDC/DONNEES/labo/Computer/GPM/XMLforTS/'.$essai['n_fichier'].'.xml', 'w');
fwrite($fp, $xml_string);
fclose($fp);
?>
