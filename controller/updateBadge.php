<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


include '../models/badge-model.php';
$oBadge = new BadgeModel($db);





if(isset($_COOKIE['id_user']) AND $_POST['type']=="ClockINOUT"){
  if ($_POST['clock']=="in1") {
    $oBadge->insertClock();
  }
  else {
    $oBadge->updateClock($_POST['clock']);
  }
}


?>
