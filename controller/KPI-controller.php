<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/invoice-model.php';
// Création d'une instance
$oInvoices = new InvoiceModel($db);

$lstData=$oInvoices->getAllProdIndicator($_GET['dateStart']);


$old_ubrMRSAS=0; $old_ubrSubC=0;
$c_inv_mrsas=0; $c_inv_subc=0; $c_ubrMRSAS=0; $c_ubrSubC=0; $c_var_ubrMRSAS=0; $c_var_ubrSubC=0;

foreach ($lstData as $key => $value) {

  if (date("m",strtotime($value ['inv_date']))=='01') {  //new year
    $c_inv_mrsas=0; $c_inv_subc=0; $c_ubrMRSAS=0; $c_ubrSubC=0; $c_var_ubrMRSAS=0; $c_var_ubrSubC=0;
  }

$value['var_ubrMRSAS']=$value['ubrMRSAS'] - $old_ubrMRSAS; $old_ubrMRSAS=$value['ubrMRSAS'];
$value['var_ubrSubC']=$value['ubrSubC'] - $old_ubrSubC; $old_ubrSubC=$value['ubrSubC'];

$value['c_inv_mrsas']=$c_inv_mrsas=$value['inv_mrsas'] + $c_inv_mrsas;
$value['c_inv_subc']=$c_inv_subc=$value['inv_subc'] + $c_inv_subc;
$value['c_ubrMRSAS']=$c_ubrMRSAS=$value['ubrMRSAS'] + $c_ubrMRSAS;
$value['c_ubrSubC']=$c_ubrSubC=$value['ubrSubC'] + $c_ubrSubC;
$value['c_var_ubrMRSAS']=$c_var_ubrMRSAS=$value['var_ubrMRSAS'] + $c_var_ubrMRSAS;
$value['c_var_ubrSubC']=$c_var_ubrSubC=$value['var_ubrSubC'] + $c_var_ubrSubC;





  $tableau[$value['inv_date']]=$value;
}

//$tableau=array_reverse($tableau);


//var_dump($tableau);

/*
foreach ($tableau as $key => $value) {
foreach ($value as $k => $v) {
  $tab[$k][$key]=$v;
}
}

var_dump($tab);
*/
?>
