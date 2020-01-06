<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


$now = time();
$june = strtotime("1st June");

if ($now > $june) {
  $getBegin=date("y-m-d", strtotime('+0 year', $june));
  $getEnd=date("y-m-d", strtotime('+1 year -1 day', $june));
}
else {
  $getBegin=date("y-m-d", strtotime('-1 year -1 day', $june));
  $getEnd=date("y-m-d", strtotime('0 year', $june));
}






$getBegin=(isset($_GET['begin']))?$_GET['begin']:$getBegin;
$getEnd=(isset($_GET['end']))?$_GET['end']:$getEnd;

$begin = new DateTime($getBegin);
$end = new DateTime($getEnd);

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);





// Rendre votre modèle accessible
include_once 'models/lstPlanningUsers-model.php';
$oPlanningUser = new PlanningUsersModel($db);

$lstUsers=$oPlanningUser->getAllUsers();
$planningUser=$oPlanningUser->getAllPlanningUsers($getBegin,$getEnd);
$planningValidated=$oPlanningUser->getAllPlanningModifValidated($getBegin,$getEnd);
$planningAwaiting=$oPlanningUser->getAllPlanningModifAwaiting($getBegin,$getEnd);


foreach ($planningUser as $key => $value) {
  $planning[$value['dateplanned']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['type']);
}
foreach ($planningValidated as $key => $value) {
  $planning[$value['datemodif']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['id_type']);
}


foreach ($planningAwaiting as $key => $value) {
  $planningUnconfirmed[$value['datemodif']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['id_type']);
}


?>
