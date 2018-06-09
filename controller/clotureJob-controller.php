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
$groupes=$oWorkflow->getAllGroupes();
//$eprouvettes=$oWorkflow->getAllGroupes();


//on recupere un string des id_tbljobs dans l'ordre des groupes et on en fait un array
foreach ($groupes as $key=>$val)  {
  $groupes[$key]['split_explode']=explode(",", $val['ordre']);
  foreach ($groupes[$key]['split_explode'] as $k => $v) {
    $groupes[$key]['split'][$v]=1;
  }
}


//var_dump($split2);
//var_dump($splits);
//var_dump($groupes);


// Rendre votre modèle accessible
include 'models/schedule-model.php';
// Création d'une instance
$oSchedule = new SCHEDULE($db);
$lstSchedule=$oSchedule->getAllSchedule($_GET['id_tbljob']);


// Affichage du résultat
include 'views/clotureJob-view.php';
