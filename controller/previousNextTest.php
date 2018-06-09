<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEp']);


if ($_POST['type']=="machine") {

$oEprouvette->previousNextMachine($_POST['sens']);
}
elseif ($_POST['type']=="split") {
$oEprouvette->previousNextTest($_POST['sens']);
}
elseif ($_POST['type']=="specimen") {
$oEprouvette->previousNextSpecimen($_POST['sens']);
}

?>
