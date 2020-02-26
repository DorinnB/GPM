<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


$now = time();
$june = strtotime("1st June");

if ($now > $june) {
  $getBegin=date("y-m-d", strtotime('+0 year', $june));
  $getEnd=date("y-m-d", strtotime('+1 year ', $june));
}
else {
  $getBegin=date("y-m-d", strtotime('-1 year ', $june));
  $getEnd=date("y-m-d", strtotime('0 year', $june));
}



if (!isset($_GET['begin']) OR !isset($_GET['end'])) {
  echo "<script>loc= (window.location.href) + '&begin=".$getBegin."&end=".$getEnd."' ;window.location.href = loc ;</script>";
}


$getBegin=$_GET['begin'];
$getEnd=$_GET['end'];

$begin = new DateTime($getBegin);
$end = new DateTime($getEnd);

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);




// Rendre votre modèle accessible
include_once 'models/lstPlanningUsers-model.php';
$oPlanningUser = new PlanningUsersModel($db);

$lstUsers=$oPlanningUser->getAllUsers();
$lstUsersManaged=$oPlanningUser->getAllUsersManaged();
$lstPlanningTypes=$oPlanningUser->getAllPlanningTypes();
$planningUser=$oPlanningUser->getAllPlanningUsers($getBegin,$getEnd);
$planningValidated=$oPlanningUser->getAllPlanningModifValidated($getBegin,$getEnd);
$planningAwaiting=$oPlanningUser->getAllPlanningModifAwaiting($getBegin,$getEnd);

$planningDone=$oPlanningUser->getAllPlanningDone($getBegin,$getEnd);

foreach ($planningUser as $key => $value) {
  $planning[$value['dateplanned']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['type'], "planning_type" => $value['planning_type']);
}
foreach ($planningValidated as $key => $value) {
  $planning[$value['datemodif']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['id_type'], "planning_type" => $value['planning_type']);
}


foreach ($planningAwaiting as $key => $value) {
  $planningUnconfirmed[$value['datemodif']][$value['id_user']]=array("quantity" => $value['quantity'], "type" => $value['id_type'], "planning_type" => $value['planning_type']);
}

foreach ($planningDone as $key => $value) {
  $heureDecimal=($value['validation'])?round($value['validation'],1):$value['validation2'];
  $planningDone[$value['date']][$value['id_user']]=array("quantity" => $heureDecimal);
}








/*---------------------------------------------------------------*/
/*
Titre : Quel sont les jours fériés en France

URL   : https://phpsources.net/code_s.php?id=641
Auteur           : developpeurweb
Website auteur   : http://rodic.fr
Date édition     : 02 Mai 2011
Date mise à jour : 13 Aout 2019
Rapport de la maj:
- fonctionnement du code vérifié
- amélioration du code
*/
/*---------------------------------------------------------------*/

function dimanche_paques($annee)
{
  return date("Y-m-d", easter_date($annee));
}
function vendredi_saint($annee)
{
  $dimanche_paques = dimanche_paques($annee);
  return date("Y-m-d", strtotime("$dimanche_paques -2 day"));
}
function lundi_paques($annee)
{
  $dimanche_paques = dimanche_paques($annee);
  return date("Y-m-d", strtotime("$dimanche_paques +1 day"));
}
function jeudi_ascension($annee)
{
  $dimanche_paques = dimanche_paques($annee);
  return date("Y-m-d", strtotime("$dimanche_paques +39 day"));
}
function lundi_pentecote($annee)
{
  $dimanche_paques = dimanche_paques($annee);
  return date("Y-m-d", strtotime("$dimanche_paques +50 day"));
}


function jours_feries($annee, $alsacemoselle=false)
{
  $jours_feries = array
  (    dimanche_paques($annee)
  ,    lundi_paques($annee)
  ,    jeudi_ascension($annee)
  ,    lundi_pentecote($annee)

  ,    "$annee-01-01"        //    Nouvel an
  ,    "$annee-05-01"        //    Fête du travail
  ,    "$annee-05-08"        //    Armistice 1945
  ,    "$annee-05-15"        //    Assomption
  ,    "$annee-07-14"        //    Fête nationale
  ,    "$annee-11-11"        //    Armistice 1918
  ,    "$annee-11-01"        //    Toussaint
  ,    "$annee-12-25"        //    Noël
);
if($alsacemoselle)
{
  $jours_feries[] = "$annee-12-26";
  $jours_feries[] = vendredi_saint($annee);
}
sort($jours_feries);
return $jours_feries;
}
function est_ferie($jour, $alsacemoselle=false)
{
  $jour = date("Y-m-d", strtotime($jour));
  $annee = substr($jour, 0, 4);
  return in_array($jour, jours_feries($annee, $alsacemoselle));
}


//tableau avec tous les jours ferier entre begin et end
$intervalFerie = DateInterval::createFromDateString('1 year');
$periodFerie = new DatePeriod($begin, $intervalFerie, $end);

$ferie = array();
foreach ($periodFerie as $key => $value) {
  $ferie=array_merge($ferie,jours_feries($value->format("Y")));
}
$ferie=array_merge($ferie,jours_feries($end->format("Y")));
















//formatage données table
foreach ($lstUsers as $oUser) {
  $count[$oUser['id_technicien']]['planning_type']= array(); //declaration array
  $count[$oUser['id_technicien']]['nb']= array(); //declaration array


  foreach ($period as $key => $value) {

    $workable=(($value->format("l")=="Sunday" OR $value->format("l")=="Saturday" OR in_array($value->format("Y-m-d"), $ferie)) AND (!isset($planning[$value->format("Y-m-d")][$oUser['id_technicien']]) OR $planning[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity']==0))?'notWorkable':'';


    $type= ' type_'.(isset($planning[$value->format("Y-m-d")][$oUser['id_technicien']])?$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['type']:'');
    $unconfirmed=' unconfirmed_'.(isset($planningUnconfirmed[$value->format("Y-m-d")][$oUser['id_technicien']])?$planningUnconfirmed[$value->format("Y-m-d")][$oUser['id_technicien']]['type']:'');

    $td[$oUser['id_technicien']][$value->format("Y-m-d")]['class']=$workable.$type.$unconfirmed;


    if (isset($planning[$value->format("Y-m-d")][$oUser['id_technicien']]) AND isset($planningDone[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity']) AND $planningDone[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity']==$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity']) {
      $iconeTooltip='tooltip';
    }
    else {
      $iconeTooltip='tooltip2';
    }
    $td[$oUser['id_technicien']][$value->format("Y-m-d")]['tooltip']=isset($planningDone[$value->format("Y-m-d")][$oUser['id_technicien']])?'data-toggle="'.$iconeTooltip.'" title="'.$planningDone[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity'].'"':'';

    $td[$oUser['id_technicien']][$value->format("Y-m-d")]['value']=isset($planning[$value->format("Y-m-d")][$oUser['id_technicien']])?$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity']:'';



    //calcul temps présence
    if (isset($planningDone[$value->format("Y-m-d")][$oUser['id_technicien']])) {
      if (isset($count[$oUser['id_technicien']]['nb'][$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type']])) {
        $count[$oUser['id_technicien']]['nb'][$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type']]+=$planningDone[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity'];
      }
      else {
        $count[$oUser['id_technicien']]['nb'][$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type']]=$planningDone[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity'];
      }
      array_push($count[$oUser['id_technicien']]['planning_type'],$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type']); // comptage jour planning_type
    }
    elseif (isset($planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type'])) {
      if (isset($count[$oUser['id_technicien']]['nb'][$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type']])) {
        $count[$oUser['id_technicien']]['nb'][$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type']]+=$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity'];
      }
      else {
        $count[$oUser['id_technicien']]['nb'][$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type']]=$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['quantity'];
      }
      array_push($count[$oUser['id_technicien']]['planning_type'],$planning[$value->format("Y-m-d")][$oUser['id_technicien']]['planning_type']); // comptage jour type
    }


  }
  /*
  //  echo $oUser['id_technicien'];
  //var_dump($count[$oUser['id_technicien']]['nb']);
  var_dump(array_count_values($count[$oUser['id_technicien']]['planning_type']));
  echo (array_count_values($count[$oUser['id_technicien']]['planning_type'])['Férié']);
  */
}



$planningSummary=$oPlanningUser->getAllPlanningSummary($getBegin,$getEnd);

foreach ($planningSummary as $key => $value) {
$lstSummary[$value['id_user']]=$value;

}



//var_dump($lstSummary);

$planningModifSummary=$oPlanningUser->getAllPlanningModifSummary($getBegin,$getEnd);
foreach ($planningModifSummary as $key => $value) {
$lstModifSummary[$value['id_user']]=$value;

}
?>
