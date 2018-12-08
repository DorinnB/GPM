<?php



// Rendre votre modèle accessible
include 'models/lstCellLoad-model.php';
$oLstCellLoad = new CellLoadModel($db);
$lstCellLoad=$oLstCellLoad->getAllCellLoad();

include 'models/lstCellDisplacement-model.php';
$oLstCellDisplacement = new CellDisplacementModel($db);
$lstCellDisplacement=$oLstCellDisplacement->getAllCellDisplacement();

include 'models/lstServovalve-model.php';
$oLstServovalve = new ServovalveModel($db);
$lstServovalve=$oLstServovalve->getAllServovalve();



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
