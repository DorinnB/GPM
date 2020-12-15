<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/lab-model.php';
// Création d'une instance
$oTest = new LabModel($db);
$test=$oTest->getTest();

//variable des etats des machines (run, stop, wip) pour la vue lab
$runStop=array();

foreach ($test as $value) {
  $poste[$value['poste']]=$value;


  //initialisation couleur
  $poste[$value['poste']]['background-color']='Yellow';
  $poste[$value['poste']]['color']='white';



  switch ($value['currentBlock_temp']) {
    case "Init":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Menu":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Parameters":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Adv.":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Check":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Amb.":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "ET":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Ramp":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "RampTemp":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "RampTemp1h":
      $poste[$value['poste']]['background-color']='yellow';
      $poste[$value['poste']]['color']='black';
    break;
    case "RampTemp3h":
      $poste[$value['poste']]['background-color']='pink';
      $poste[$value['poste']]['color']='black';
    break;
    case "Strain":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
    break;
    case "Switchable":
      $poste[$value['poste']]['background-color']='yellow';
      $poste[$value['poste']]['color']='black';
    break;
    case "Not":
      $poste[$value['poste']]['background-color']='#108800';
      $poste[$value['poste']]['color']='white';
    break;
    case "STL":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Load":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
    break;
    case "Dwell":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
    break;
    case "Fluage":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
    break;
    case "Relaxation":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
    break;
    case "Tensile":
      $poste[$value['poste']]['background-color']='darkgreen';
      $poste[$value['poste']]['color']='white';
      $runStop[]="RUN";
    break;
    case "Stop":
      $poste[$value['poste']]['background-color']='darkred';
      $poste[$value['poste']]['color']='white';
    break;
    case "Straightening":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Report":
      $poste[$value['poste']]['background-color']='gray';
      $poste[$value['poste']]['color']='white';
    break;
    case "Send":
      $poste[$value['poste']]['background-color']='dimgray';
      $poste[$value['poste']]['color']='white';
      if ($poste[$value['poste']]['etape']==53) {
        $poste[$value['poste']]['background-color']='Gold';
        $poste[$value['poste']]['color']='black';
      }
    break;
    case "send":
      $poste[$value['poste']]['background-color']='dimgray';
      $poste[$value['poste']]['color']='white';
      if ($poste[$value['poste']]['etape']==53) {
        $poste[$value['poste']]['background-color']='Gold';
        $poste[$value['poste']]['color']='black';
      }
    break;
    case "Analysis":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "Restart":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
    case "":
      $poste[$value['poste']]['background-color']='Sienna';
      $poste[$value['poste']]['color']='white';
    break;
  }



}





// Affichage du résultat
include 'views/lab-small-view.php';
?>
