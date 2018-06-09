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

//on crée un array split2 qui a pour clé son id_tbljob
foreach ($splits as $key=>$val)  {
  $split2[$splits[$key]['id_tbljob']]=$splits[$key];
}

//pour chaque groupe, on regarde la date par rapport a la date précédente
foreach ($groupes as $key=>$val)  {
  foreach ($groupes[$key]['split_explode'] as $k => $v) {
    if ($k>0) { //uniquement pour le 2eme de la liste, on compare avec n-1
      if ($split2[$groupes[$key]['split_explode'][$k]]['DyT_expected']<$split2[$groupes[$key]['split_explode'][$k-1]]['DyT_expected']) {
        $split2[$groupes[$key]['split_explode'][$k]]['erreur_DyT_expected'] = '1';
      }
      if ($split2[$groupes[$key]['split_explode'][$k]]['DyT_SubC']<$split2[$groupes[$key]['split_explode'][$k-1]]['DyT_SubC'] OR $split2[$groupes[$key]['split_explode'][$k]]['DyT_SubC']<$split2[$groupes[$key]['split_explode'][$k-1]]['DyT_Cust']) {
        $split2[$groupes[$key]['split_explode'][$k]]['erreur_DyT_SubC'] = '1';
      }
      if ($split2[$groupes[$key]['split_explode'][$k]]['DyT_Cust']<$split2[$groupes[$key]['split_explode'][$k-1]]['DyT_Cust'] OR $split2[$groupes[$key]['split_explode'][$k]]['DyT_Cust']<$split2[$groupes[$key]['split_explode'][$k-1]]['DyT_SubC']) {
        $split2[$groupes[$key]['split_explode'][$k]]['erreur_DyT_Cust'] = '1';
      }
    }
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
include 'views/schedule-view.php';
