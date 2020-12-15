<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/lab-model.php';
// Création d'une instance
$oTest = new LabModel($db);
$test=$oTest->getTest();

$splitToDo=$oTest->getTestToStart();

include_once 'models/planningLab-model.php';
$oPlanningLab = new PLANNINGLAB($db);
$planned=$oPlanningLab->getPlanningDay();


$view=(isset($_GET['view'])?$_GET['view']:"default");

//variable des etats des machines (run, stop, wip) pour la vue lab
$runStop=array();


foreach ($test as $value) {
  $poste[$value['poste']]=$value;
  $poste[$value['poste']]['planned']=$oPlanningLab->getPlanningDayFrame($value['id_machine']);

  //initialisation couleur
  $poste[$value['poste']]['background-color']='Yellow';
  $poste[$value['poste']]['color']='white';

  switch ($value['currentBlock_temp']) {
    case "Init":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "Menu":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "Parameters":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "Adv.":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "Check":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "Amb.":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "ET":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "Ramp":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "RampTemp":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "RampTemp1h":
      $poste[$value['poste']]['background-color']='yellow';
      $poste[$value['poste']]['color']='black';
      $runStop[]="WIP";
    break;
    case "RampTemp3h":
      $poste[$value['poste']]['background-color']='pink';
      $poste[$value['poste']]['color']='black';
      $runStop[]="WIP";
    break;
    case "Strain":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "Switchable":
      $poste[$value['poste']]['background-color']='yellow';
      $poste[$value['poste']]['color']='black';
      $runStop[]="RUN";
    break;
    case "Not":
      $poste[$value['poste']]['background-color']='#108800';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "STL":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "Load":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "Dwell":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "Fluage":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "Relaxation":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "Tensile":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "Stop":
      $poste[$value['poste']]['background-color']='darkred';
      $poste[$value['poste']]['color']='white';
      $runStop[]="STOP";
    break;
    case "Straightening":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "Report":
      $poste[$value['poste']]['background-color']='gray';
      $poste[$value['poste']]['color']='white';
      $runStop[]="STOP";
    break;
    case "Send":
      $poste[$value['poste']]['background-color']='dimgray';
      $poste[$value['poste']]['color']='white';
      $runStop[]="STOP";
      if ($poste[$value['poste']]['etape']==53) {
        $poste[$value['poste']]['background-color']='Gold';
        $poste[$value['poste']]['color']='black';
      }
    break;
    case "send":
      $poste[$value['poste']]['background-color']='dimgray';
      $poste[$value['poste']]['color']='white';
      $runStop[]="STOP";
      if ($poste[$value['poste']]['etape']==53) {
        $poste[$value['poste']]['background-color']='Gold';
        $poste[$value['poste']]['color']='black';
      }
    break;
    case "Analysis":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "Restart":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
    case "":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
      $runStop[]="WIP";
    break;
  }
  //$runStop[]=$poste[$value['poste']]['background-color'];



  if ($value['d_frequence']>0) {
    $frequence=$value['d_frequence'];
    $frequenceSTL=$value['d_frequence_STL'];
  }
  else {
    $frequence=$value['c_frequence'];
    $frequenceSTL=$value['c_frequence_STL'];
  }

  if ($value['c_cycle_STL']>0) {
    if ($value['Cycle_final_temp']<$value['c_cycle_STL']) {
      $poste[$value['poste']]['tempsRestant']=round(($value['c_cycle_STL']-$value['Cycle_final_temp'])/$frequence/3600, 1);
      //$poste[$value['poste']]['tempsRestant']='STL a faire bientot';
      //$poste[$value['poste']]['tempsRestant']=$value['c_cycle_STL']-$value['Cycle_final'];
    }
    else {
      if($value['runout']>0)  {
        $poste[$value['poste']]['tempsRestant']=round(($value['runout']-$value['Cycle_final_temp'])/$frequenceSTL/3600, 1);
        //$poste[$value['poste']]['tempsRestant']='STL deja fait';
      }
      else {
        $poste[$value['poste']]['tempsRestant']='&infin;';
      }
    }
  }
  else {
    if($value['runout']>0)  {
      if ($frequence<=0) {
        $poste[$value['poste']]['tempsRestant']="Frequency NULL";
      }
      else {
        $poste[$value['poste']]['tempsRestant']=round(($value['runout']-$value['Cycle_final_temp'])/$frequence/3600, 1);
        //$poste[$value['poste']]['tempsRestant']='pas de STL prevu';
      }
    }
    else {
      $poste[$value['poste']]['tempsRestant']='&infin;';
      //$poste[$value['poste']]['tempsRestant']='pas de STL prevu';
    }
  }




}






?>
