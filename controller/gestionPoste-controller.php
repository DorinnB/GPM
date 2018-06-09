<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

//Si le cookie existe, on recupere l'id poste. Sinon on met 0
if (isset($_COOKIE['id_machine'])) {
  include 'models/lstposte-model.php';
  $oLstPoste = new LstPosteModel($db);
  $array_id=$oLstPoste->getLastPoste($_COOKIE['id_machine']);
  $idPoste=$array_id['id_poste'];
}
else {
  $idPoste=0;
}


// Rendre votre modèle accessible
include 'models/poste-model.php';

//si on vient de changer de poste depuis le menu déroulant (ou l'url) on change l'idposte
$idPoste=isset($_GET['id_poste'])?$_GET['id_poste']:$idPoste;

// Création d'une instance
$oPoste = new PosteModel($db, $idPoste);

$postes=$oPoste->getAllMachine();
$poste=$oPoste->getPoste();
$history=$oPoste->getAllPoste();



// Rendre votre modèle accessible
include 'models/lstCellLoad-model.php';
// Création d'une instance
$oLstCellLoad = new CellLoadModel($db);
$lstCellLoad=$oLstCellLoad->getAllCellLoad();

// Rendre votre modèle accessible
include 'models/lstCellDisplacement-model.php';
// Création d'une instance
$oLstCellDisplacement = new CellDisplacementModel($db);
$lstCellDisplacement=$oLstCellDisplacement->getAllCellDisplacement();




// Rendre votre modèle accessible
include 'models/lstExtensometre-model.php';
// Création d'une instance
$oLstExtensometre = new ExtensometreModel($db);
$lstExtensometre=$oLstExtensometre->getAllExtensometre();


// Rendre votre modèle accessible
include 'models/lstOutillage-model.php';
// Création d'une instance
$oLstOutillage = new OutillageModel($db);
$lstOutillage=$oLstOutillage->getAllOutillage();

// Rendre votre modèle accessible
include 'models/lstComputer-model.php';
// Création d'une instance
$oLstComputer = new ComputerModel($db);
$lstComputer=$oLstComputer->getAllComputer();

// Rendre votre modèle accessible
include 'models/lstChauffage-model.php';
// Création d'une instance
$oLstChauffage = new ChauffageModel($db);
$lstChauffage=$oLstChauffage->getAllChauffage();

// Rendre votre modèle accessible
include 'models/lstIndTemp-model.php';
// Création d'une instance
$oLstIndTemp = new IndTempModel($db);
$lstIndTemp=$oLstIndTemp->getAllIndTemp();
