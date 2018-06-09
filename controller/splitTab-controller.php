<?php


// Rendre votre modèle accessible
include 'models/splitTab-model.php';



// Création d'une instance
$lstTabs = new LstTabModel($db,$_GET['id_tbljob']);
$Tabs=$lstTabs->getTab();

//declaration des variables calculées
for($i=0;$i < count($Tabs);$i++)	{
  if ($Tabs[$i]['id_tbljob']==$_GET['id_tbljob']){
    $Tabs[$i]['class']="active";
  }
  else {
    $Tabs[$i]['class']="";
  }
}


// Affichage du résultat
include 'views/splitTab-view.php';
