<?php


// Rendre votre modèle accessible
include 'models/lstDrawing-model.php';
$lstDwg = new DwgModel($db);
$Dwg = $lstDwg->getAllDwg();


// Rendre votre modèle accessible
include 'models/lstTestType-model.php';
$otestType = new TestTypeModel($db);
$testType=$otestType->getAllTestType();


// Rendre votre modèle accessible
include 'models/workflow.class.php';
// Création d'une instance
$oWorkflow = new WORKFLOW($db,$_GET['id_tbljob']);
$splits=$oWorkflow->getAllSplit();
$eprouvettes=$oWorkflow->getAllEprouvettes();
  //var_dump($eprouvettes);
for($i=0;$i < count($eprouvettes);$i++)  {
  //on ajoute a $ep la liste des id_tbljobs où cette ep est présente
  $ep[$eprouvettes[$i]['id_master_eprouvette']][$eprouvettes[$i]['id_tbljob']]=isset($eprouvettes[$i]['n_fichier'])?$eprouvettes[$i]['n_fichier']:1;
  //On ajoute les données de l'ep sur ce array
  foreach ($eprouvettes[$i] as $key=>$val){
    $ep[$eprouvettes[$i]['id_master_eprouvette']][$key]=$val;
  }
}

  //var_dump($ep);

// Affichage du résultat
include 'views/updateJobWorkflow-view.php';
