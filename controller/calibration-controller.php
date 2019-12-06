<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

//Si la machine/poste est demandé on l'affecte, sinon si le cookie existe, on recupere l'id poste. Sinon on met 0
if (isset($_GET['idposte'])) {
  $idPoste=$_GET['idposte'];
}
elseif (isset($_COOKIE['id_machine'])) {
  include 'models/lstposte-model.php';
  $oLstPoste = new LstPosteModel($db);
  $array_id=$oLstPoste->getLastPoste($_COOKIE['id_machine']);
  $idPoste=$array_id['id_poste'];
}
else {
  $idPoste=0;
}


// Rendre votre modèle accessible
include 'models/calibration-model.php';
$oCalibration = new CalibrationModel($db);

$lstCalib = $oCalibration->getAllCalibrationList();


// Rendre votre modèle accessible
include 'models/poste-model.php';
$oPoste = new PosteModel($db, $idPoste);

$postes=$oPoste->getAllMachine();
$poste=$oPoste->getPoste();





$type=(isset($_GET['type']))?$_GET['type']:"0";
$idElement=(isset($_GET['idElement']))?$_GET['idElement']:"";
$element="N/A";

$lstIdElement[0]['id_element']=$idElement;
$lstIdElement[0]['element']="N/A";



//var_dump($lstCalib);
//echo $type;


if ($type=="5") {  //Load
  $idElement=($idElement=="")?$poste['id_cell_load']:$idElement;
  include 'models/lstCellLoad-model.php';
  $oLstCellLoad = new CellLoadModel($db);
  $lstCellLoad=$oLstCellLoad->getAllCellLoad();
  foreach ($lstCellLoad as $key => $value) {
    $lstIdElement[$value['id_cell_load']]['id_element']=$value['id_cell_load'];
    $lstIdElement[$value['id_cell_load']]['element']=$value['cell_load_serial'];
    $element=($idElement==$value['id_cell_load'])?$value['cell_load_serial']:$element;
  }
}
elseif ($type==="6") {   //displacement
  $idElement=($idElement=="")?$poste['id_cell_displacement']:$idElement;
  include 'models/lstCellDisplacement-model.php';
  $oLstCellDisplacement = new CellDisplacementModel($db);
  $lstCellDisplacement=$oLstCellDisplacement->getAllCellDisplacement();
  foreach ($lstCellDisplacement as $key => $value) {
    $lstIdElement[$value['id_cell_displacement']]['id_element']=$value['id_cell_displacement'];
    $lstIdElement[$value['id_cell_displacement']]['element']=$value['cell_displacement_serial'];
    $element=($idElement==$value['id_cell_displacement'])?$value['cell_displacement_serial']:$element;
  }
}
elseif ($type=="4") {   //extensometer
  $idElement=($idElement=="")?$poste['id_extensometre']:$idElement;
  include 'models/lstExtensometre-model.php';
  $oLstExtensometre = new ExtensometreModel($db);
  $lstExtensometre=$oLstExtensometre->getAllExtensometre();
  foreach ($lstExtensometre as $key => $value) {
    $lstIdElement[$value['id_extensometre']]['id_element']=$value['id_extensometre'];
    $lstIdElement[$value['id_extensometre']]['element']=$value['extensometre'];
    $element=($idElement==$value['id_extensometre'])?$value['extensometre']:$element;
  }
}
elseif ($type=="2") {   //temperature
  $idElement=($idElement=="")?$poste['id_chauffage']:$idElement;
  include 'models/lstChauffage-model.php';
  $oLstChauffage = new ChauffageModel($db);
  $lstChauffage=$oLstChauffage->getAllChauffage();
  foreach ($lstChauffage as $key => $value) {
    $lstIdElement[$value['id_chauffage']]['id_element']=$value['id_chauffage'];
    $lstIdElement[$value['id_chauffage']]['element']=$value['chauffage'];
    $element=($idElement==$value['id_chauffage'])?$value['chauffage']:$element;
  }
}

$history=$oCalibration->getAllCalibration($type, $poste['id_machine'], $idElement);    //to del




/*
array(
  "alignement" => "Alignement",
  "temperature" => "Temperature",
  "extensometer" => "Extensometer",
  "tempLine" => "Temp. Line",
  "load" => "Load",
  "displacement" => "Displacement"
);
*/
