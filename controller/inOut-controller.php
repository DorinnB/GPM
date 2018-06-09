<?php


// Rendre votre modèle accessible
include 'models/split-model.php';
// Création d'une instance
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);
$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include 'models/workflow.class.php';
// Création d'une instance
$oWorkflow = new WORKFLOW($db,$_GET['id_tbljob']);
$splits=$oWorkflow->getAllSplit();
$eprouvettes=$oWorkflow->getAllEprouvettes();
  //var_dump($eprouvettes);
for($i=0;$i < count($eprouvettes);$i++)  {
  $epA[$eprouvettes[$i]['id_eprouvette']]=$eprouvettes[$i];

//on attribue un flag 0 1 2 a chaque essai/eprouvette pour dire si l'essai realisé (dcheck validé), en flag qualité ou non fait.
$epA[$eprouvettes[$i]['id_eprouvette']]['done']=($epA[$eprouvettes[$i]['id_eprouvette']]['d_checked']>0)?(($epA[$eprouvettes[$i]['id_eprouvette']]['flag_qualite']>0)?1:2):0;
  //on ajoute a $ep la liste des id_tbljobs où cette ep est présente
  $ep[$eprouvettes[$i]['id_master_eprouvette']][$eprouvettes[$i]['id_tbljob']]=$eprouvettes[$i]['id_eprouvette'];
  //On ajoute les données de l'ep sur ce array
  foreach ($eprouvettes[$i] as $key=>$val){
    $ep[$eprouvettes[$i]['id_master_eprouvette']][$key]=$val;
  }
}


// Rendre votre modèle accessible
include 'models/inOut-model.php';
// Création d'une instance
$oInOut = new INOUT($db);
$lstInOut=$oInOut->getAllInOut($_GET['id_tbljob']);

// Affichage du résultat
include 'views/inOut-view.php';
