<?php

// Rendre votre modèle accessible
include 'models/split-model.php';
// Création d'une instance
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);
$split=$oSplit->getSplit();
$splitEp=$oSplit->getEprouvettes();




include 'models/eprouvettes-model.php';
$oEprouvettes = new LstEprouvettesModel($db,$_GET['id_tbljob']);
//$ep=$oEprouvettes->getAllEprouvettes();


include 'models/histo-model.php';
$oHisto = new HistoModel($db);


include 'models/planningLab-model.php';
$oPlanningLab = new PLANNINGLAB($db);

$machines=$oPlanningLab->getPlanningSplit($_GET['id_tbljob']);



// Affichage des DATA selon le type de test
$filenameData = 'controller/splitData_'.$split['test_type_abbr'].'-controller.php';
if (file_exists($filenameData)) {
  $splitData_ctrl=$filenameData;
}
else{
  if (substr($split['test_type_abbr'],0,1)==".") {
  $splitData_ctrl=  'controller/splitData_.default-controller.php';
  }
  else {
  $splitData_ctrl=  'controller/splitData_default-controller.php';
  }

}

// Affichage des EPROUVETTES selon le type de test
$filenameEP = 'controller/splitEprouvette_'.$split['test_type_abbr'].'-controller.php';
if (file_exists($filenameEP)) {
  $splitEp_ctrl=$filenameEP;
}
else{
  $splitEp_ctrl=  'controller/splitEprouvette_default-controller.php';
}

//Changement de la VUE-EPROUVETTES chargé selon le menu choisi
$eprouvetteConsigne=(isset($_GET['modif']) AND $_GET['modif']=="eprouvetteConsigne")?"Consigne":"";
$eprouvetteValue=(isset($_GET['modif']) AND $_GET['modif']=="eprouvetteValue")?"Value":"";

// Affichage des EPROUVETTES selon le type de test
$filenameEPView = 'views/splitEprouvette'.$eprouvetteConsigne.$eprouvetteValue.'_'.$split['test_type_abbr'].'-view.php';
if (file_exists($filenameEPView)) {
  $splitEp_View=$filenameEPView;
}
else{
    if (substr($split['test_type_abbr'],0,1)==".") {
  $splitEp_View= 'views/splitEprouvette'.$eprouvetteConsigne.$eprouvetteValue.'_.default-view.php';
    }
    else {
  $splitEp_View= 'views/splitEprouvette'.$eprouvetteConsigne.$eprouvetteValue.'_default-view.php';
    }

}














// Affichage du résultat
include 'views/split-view.php';
