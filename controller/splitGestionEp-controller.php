<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_GET['idEp']);
$eprouvette=$oEprouvette->getEprouvette();

$checkTechSplit=(isset($_COOKIE['id_user']))?$oEprouvette->checkTechSplit($_COOKIE['id_user']):'';


// Rendre votre modèle accessible
include '../models/lstTech-model.php';

// Création d'une instance
$lstTech = new TechModel($db);


// Rendre votre modèle accessible
include '../models/lstPoste-model.php';
// Création d'une instance
$lstPoste = new LstPosteModel($db);



// Rendre votre modèle accessible
include '../models/document-model.php';

// Création d'une instance
$oDocument = new DocumentModel($db);






echo '  <script type="text/javascript">';
//affichage des champs utiles
if ($eprouvette['n_fichier']=="") {
  echo'
  document.getElementById("newTest").style.display = "block";
  document.getElementById("newTest").className += " active";
  document.getElementById("1a").className += " active";
  ';
}
elseif ($eprouvette['id_controleur']=="0") {
  echo'
  document.getElementById("prepa").style.display = "block";
  document.getElementById("prepa").className += " active";
  document.getElementById("1b").className += " active";
  ';
}
else {
  echo'
  document.getElementById("eval").className += " active";
  document.getElementById("1e").className += " active";
  ';

}

if (($eprouvette['currentBlock']=="") & ($eprouvette['n_fichier']!="")) {
  echo'
  document.getElementById("cancel").style.display = "block";
  ';
}
if ($eprouvette['retest']>1) {
  echo'
  document.getElementById("delete").style.display = "block";
  ';
}



echo '</script>';


//Suppression des elements si l'on est pas connecté
if (!isset($_COOKIE['id_user'])) {
  echo'
  <script type="text/javascript">
  document.getElementById("newTest").style.display = "none";
  document.getElementById("1a").style.display = "none";

  document.getElementById("1e").style.display = "none";
  document.getElementById("eval").style.display = "none";

  document.getElementById("logon").style.display = "block";
  document.getElementById("document").style.display = "none";
  document.getElementById("retest").style.display = "none";
  document.getElementById("delete").style.display = "none";
    document.getElementById("cancel").style.display = "none";
  </script>';
}


//choix de l'ione du flag qualité
if ($eprouvette['flag_qualite']>0) {
   $iconeFlagQualite="red";
 }
 elseif ($eprouvette['flag_qualite']<0) {
   $iconeFlagQualite="orange";
 }
 else {
   $iconeFlagQualite="blue";
 }


//affichage des commentaires (consigne, job unchecked ou read onenote)
if ($eprouvette['c_checked']<=0 OR $eprouvette['checked']<=0 OR $checkTechSplit)  {
    echo '<script type="text/javascript">
    $("#Comments").toggle();
    $("#notComments").toggle();
  $("#hideInfo_icone").toggleClass("glyphicon-eye-close").toggleClass("glyphicon-eye-open");
    </script>';
}


// Affichage du splitgestionEp selon le type de test
$filenameGestionEp = '../views/splitGestionEp_'.$eprouvette['test_type_abbr'].'-view.php';
if (file_exists($filenameGestionEp)) {
  $splitGestionEp = $filenameGestionEp;
}
else{
  $splitGestionEp = '../views/splitGestionEp_default-view.php';
}

include $splitGestionEp;
