<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

//var_dump($_POST);


// Rendre votre modèle accessible
include '../models/prestart-model.php';
// Création d'une instance
$oPrestart = new PrestartModel($db);

$oPrestart->id_poste=$_POST['id_poste'];
$oPrestart->id_tbljob=$_POST['id_tbljob'];
$oPrestart->shunt_cal=$_POST['shunt_cal'];

$oPrestart->tune=(isset($_POST['tune'])?$_POST['tune']:0);
$oPrestart->custom_frequency=(isset($_POST['custom_frequency'])?1:0);
$oPrestart->signal_tapered=(isset($_POST['signal_tapered'])?1:0);
$oPrestart->valid_alignement=(isset($_POST['valid_alignement'])?1:0);
$oPrestart->valid_extenso=(isset($_POST['valid_extenso'])?1:0);
$oPrestart->valid_temperature=(isset($_POST['valid_temperature'])?1:0);
$oPrestart->valid_temperature_line=(isset($_POST['valid_temperature_line'])?1:0);
$oPrestart->signal_true=(isset($_POST['signal_true'])?1:0);
$oPrestart->signal_tapered=(isset($_POST['signal_tapered'])?1:0);

$oPrestart->operateur=$_COOKIE['id_user'];



echo $oPrestart->newPrestart();

    ?>
