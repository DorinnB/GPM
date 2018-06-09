<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEp']);

$oEprouvette->Rupture=$_POST['Rupture'];
$oEprouvette->Fracture=$_POST['Fracture'];
$oEprouvette->d_commentaire=($_POST['d_commentaire']=="")?"":$_POST['d_commentaire'].'('.$_POST['technicien'].')';




$eprouvette=$oEprouvette->update_d_commentaire();
?>
