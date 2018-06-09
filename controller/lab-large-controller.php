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


//variable des etats des machines (run, stop, wip) pour la vue lab
$runStop=array();


foreach ($test as $value) {
  $poste[$value['poste']]=$value;

  //initialisation couleur
  $poste[$value['poste']]['background-color']='Yellow';
  $poste[$value['poste']]['color']='white';


  //recuperation des couleurs des blocs
  if ($value['currentBlock_temp']=='Init') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='Menu') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='Parameters') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='Adv.') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='Check') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='Amb.') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='ET') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='Ramp') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='Strain') {
    $poste[$value['poste']]['background-color']='darkgreen';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock_temp']=='Switchable') {
    $poste[$value['poste']]['background-color']='yellow';
    $poste[$value['poste']]['color']='black';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock_temp']=='Not') {
    $poste[$value['poste']]['background-color']='#108800';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock_temp']=='STL') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock_temp']=='Load') {
    $poste[$value['poste']]['background-color']='darkgreen';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock_temp']=='Dwell') {
    $poste[$value['poste']]['background-color']='darkgreen';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock_temp']=='Fluage') {
    $poste[$value['poste']]['background-color']='darkgreen';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock_temp']=='Relaxation') {
    $poste[$value['poste']]['background-color']='darkgreen';
    $runStop[]="RUN";
  }
  elseif($value['currentBlock_temp']=='Stop') {
    $poste[$value['poste']]['background-color']='darkred';
    $runStop[]="STOP";
  }
  elseif ($value['currentBlock_temp']=='Straightening') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif($value['currentBlock_temp']=='Report') {
    $poste[$value['poste']]['background-color']='gray';
    $runStop[]="STOP";
  }
  elseif($value['currentBlock_temp']=='Send' OR $value['currentBlock_temp']=='send') {
    $poste[$value['poste']]['background-color']='dimgray';
    $runStop[]="STOP";
//affichage en violet si "new condition ready"
    if ($poste[$value['poste']]['etape']==53) {
      $poste[$value['poste']]['background-color']='purple';
    }

  }
  elseif($value['currentBlock_temp']=='Analysis') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif($value['currentBlock_temp']=='Restart') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif($value['currentBlock_temp']=='') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
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
      $poste[$value['poste']]['tempsRestant']=round(($value['runout']-$value['Cycle_final_temp'])/$frequence/3600, 1);
      //$poste[$value['poste']]['tempsRestant']='pas de STL prevu';
    }
    else {
      $poste[$value['poste']]['tempsRestant']='&infin;';
      //$poste[$value['poste']]['tempsRestant']='pas de STL prevu';
    }
  }




}






?>
