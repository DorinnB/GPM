<?php




$DataInput=(isset($_GET['modif']) AND $_GET['modif']=="dataInput")?"Input":"";

// Rendre votre modèle accessible
include 'models/lstDrawing-model.php';
$lstDwg = new DwgModel($db);
$Dwg = $lstDwg->getDwg($split['id_dessin']);


$dimDenomination=$lstDwg->dimensions($Dwg['id_dessin_type']);

//suppression des dimensions null
foreach ($dimDenomination as $index => $data) {

  if ($data=='') {
    unset($dimDenomination[$index]);
  }
}
$dimDenomination = array_values($dimDenomination);  //Conversion de l'array "keys" en "numeric"

// Affichage du résultat
include 'views/splitData'.$DataInput.'_IQC-view.php';
