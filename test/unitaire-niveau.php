<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';


$oEp = new EprouvetteModel($db,1);

?>
<table>
  <tr>
    <th></th>
    <th>c_1</th>
    <th>c_2</th>
    <th>R</th>
    <th>A</th>
    <th>Max</th>
    <th>Mean</th>
    <th>Alt</th>
    <th>Min</th>
  </tr>
  <?php

$n=0;
  $ep[$n]=array(
    "c_1_type"=> "R",
    "c_2_type"=> "Max",
    "c_type_1_val"=> "0",
    "c_type_2_val"=> "100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "R",
    "c_2_type"=> "Alt",
    "c_type_1_val"=> "0",
    "c_type_2_val"=> "100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "R",
    "c_2_type"=> "Max",
    "c_type_1_val"=> "-1",
    "c_type_2_val"=> "100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "R",
    "c_2_type"=> "Max",
    "c_type_1_val"=> "0.1",
    "c_type_2_val"=> "100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Min",
    "c_type_1_val"=> "0",
    "c_type_2_val"=> "-100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Min",
    "c_type_1_val"=> "0",
    "c_type_2_val"=> "100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Min",
    "c_2_type"=> "Max",
    "c_type_1_val"=> "0",
    "c_type_2_val"=> "100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Min",
    "c_type_1_val"=> "-100",
    "c_type_2_val"=> "0"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "R",
    "c_2_type"=> "Max",
    "c_type_1_val"=> "0",
    "c_type_2_val"=> "-100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Mean",
    "c_type_1_val"=> "100",
    "c_type_2_val"=> "50"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Mean",
    "c_type_1_val"=> "100",
    "c_type_2_val"=> "0"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Mean",
    "c_type_1_val"=> "0",
    "c_type_2_val"=> "50"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Alt",
    "c_type_1_val"=> "100",
    "c_type_2_val"=> "50"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Range",
    "c_type_1_val"=> "100",
    "c_type_2_val"=> "100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Mean",
    "c_2_type"=> "Alt",
    "c_type_1_val"=> "50",
    "c_type_2_val"=> "100"
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "Mean",
    "c_2_type"=> "Range",
    "c_type_1_val"=> "100",
    "c_type_2_val"=> "50"
  );$n++;


  $ep[$n]=array(
    "c_1_type"=> "Max",
    "c_2_type"=> "Min",
    "c_type_1_val"=> "",
    "c_type_2_val"=> ""
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "-",
    "c_2_type"=> "-",
    "c_type_1_val"=> "",
    "c_type_2_val"=> ""
  );$n++;
  $ep[$n]=array(
    "c_1_type"=> "",
    "c_2_type"=> "1",
    "c_type_1_val"=> "",
    "c_type_2_val"=> ""
  );$n++;







  foreach ($ep as $key => $value) {
    echo '
    <tr><td>';
    $oEp->niveaumaxmin($value['c_1_type'], $value['c_2_type'],$value['c_type_1_val'], $value['c_type_2_val']);

    echo '
    </td>
    <td>'.$value['c_1_type'].' '.$value['c_type_1_val'].'</td>
    <td>'.$value['c_2_type'].' '.$value['c_type_2_val'].'</td>
    <td>'.$oEp->R().'</td>
    <td>'.$oEp->A().'</td>
    <td>'.$oEp->MAX().'</td>
    <td>'.$oEp->MEAN().'</td>
    <td>'.$oEp->ALT().'</td>
    <td>'.$oEp->MIN().'</td>
    </tr>
    ';
  }

  ?>
  <style>
  table {
    border-collapse: collapse;
  }

  table, th, td {
    border: 1px solid black;
  }
  td {
    width:100px;
  }
</style>
