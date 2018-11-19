<?php
require '../config.php';

$infoSplit = $oSplit->getInfoSplit();

// Rendre votre modèle accessible
include '../models/eprouvettes-model.php';
// Création d'une instance
$oEprouvettes = new LstEprouvettesModel($db,$_POST['id_tbljob']);


$init=0;
foreach ($oEprouvettes->getAllEprouvettes() as $key => $value) {
  $srcfile = $PATH_JOB.$infoSplit['customer'].'/'.$infoSplit['customer'].'-'.$infoSplit['job'].'/OT/ConsigneList_'.$infoSplit['job'].'-'.$infoSplit['split'].'_'.gmdate('Y-m-d H-i-s').'.txt';

  if ($init==0) {

    $txt="";
    foreach ($value as $k=>$v) {
      $txt.=$k.";";
    }
    $myfile = file_put_contents($srcfile, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    $init=1;
  }

  $txt="";
  //Pour chaque element du tableau on ajoute la valeur a $txt
  foreach ($value as $v) {
    $txt.=$v.";";
  }
  $myfile = file_put_contents($srcfile, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
}




?>
