<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/inOut-model.php';
// Création d'une instance
$oInOut = new INOUT($db);




// Affichage du résultat
include 'views/administrativeList-view.php';

?>
