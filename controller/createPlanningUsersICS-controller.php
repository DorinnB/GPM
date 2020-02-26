<?php
include_once('../models/db.class.php'); // call db.class.php
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
include_once '../models/lstPlanningUsers-model.php';
$oPlanningUser = new PlanningUsersModel($db);

$lstUsers=$oPlanningUser->getAllUsers();
$planningUser=$oPlanningUser->getAllPlanningUsers($getBegin,$getEnd);
$planningValidated=$oPlanningUser->getAllPlanningModifValidated($getBegin,$getEnd);
$planningAwaiting=$oPlanningUser->getAllPlanningModifAwaiting($getBegin,$getEnd);


foreach ($planningUser as $key => $value) {
  $planning[$value['dateplanned']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['planning_type'], "workable" => $value['workable']);
}
foreach ($planningValidated as $key => $value) {
  $planning[$value['datemodif']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['planning_type'], "workable" => $value['workable']);
}

$ics = "BEGIN:VCALENDAR\n";
$ics .= "VERSION:2.0\n";
$ics .= "PRODID:-//hacksw/handcal//NONSGML v1.0//EN\n";


foreach ($period as $key => $value) {

  if (isset($planning[$value->format("Y-m-d")][$_COOKIE['id_user']])) { //entrée dans l'agenda GPM


    if ($planning[$value->format("Y-m-d")][$_COOKIE['id_user']]['workable']==1 OR $planning[$value->format("Y-m-d")][$_COOKIE['id_user']]['workable']==6) {  //travaillé

      $objet = $planning[$value->format("Y-m-d")][$_COOKIE['id_user']]['type'].' - '.$planning[$value->format("Y-m-d")][$_COOKIE['id_user']]['quantity'];

      $ics .= "BEGIN:VEVENT\n";
      $ics .= "X-WR-TIMEZONE:Europe/Paris\n";
      $ics .= "DTSTART:".$value->format("Ymd")."\n";
      $ics .= "DTEND:".$value->format("Ymd")."\n";
      $ics .= "SUMMARY:".$objet."\n";
      $ics .= "UID:GPM".$value->format("Ymd")."@google.com\n";
      $ics .= "STATUS:CONFIRMED\n";
      $ics .= "END:VEVENT\n";
    }
    else {  //non travaillé
      //  echo $value->format("Y-m-d").' - '.$planning[$value->format("Y-m-d")][$_COOKIE['id_user']]['workable'].' - '.($planning[$value->format("Y-m-d")][$_COOKIE['id_user']]['workable']==1).'</br>';

      $ics .= "BEGIN:VEVENT\n";
      $ics .= "METHOD:CANCEL\n";
      $ics .= "X-WR-TIMEZONE:Europe/Paris\n";
      $ics .= "DTSTART:".$value->format("Ymd")."\n";
      $ics .= "DTEND:".$value->format("Ymd")."\n";
      $ics .= "SUMMARY:non travaillé\n";
      $ics .= "UID:GPM".$value->format("Ymd")."@google.com\n";
      $ics .= "STATUS:CANCELLED\n";
      $ics .= "END:VEVENT\n";
    }

  }
  else {    //date manquante dans l'agenda GPM
    $ics .= "BEGIN:VEVENT\n";
    $ics .= "METHOD:CANCEL\n";
    $ics .= "X-WR-TIMEZONE:Europe/Paris\n";
    $ics .= "DTSTART:".$value->format("Ymd")."\n";
    $ics .= "DTEND:".$value->format("Ymd")."\n";
    $ics .= "SUMMARY:absent\n";
    $ics .= "UID:GPM".$value->format("Ymd")."@google.com\n";
    $ics .= "STATUS:CANCELLED\n";
    $ics .= "END:VEVENT\n";
  }


}
$ics .= "END:VCALENDAR\n";



$filename = "planning_id".$_COOKIE['id_user'].".ics";
$f = fopen('../temp/'.$filename, 'w+');
fputs($f, $ics);

$file_url = '../temp/'.$filename;
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
readfile($file_url);

?>
