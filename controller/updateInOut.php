<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php







// Rendre votre modèle accessible
include '../models/inOut-model.php';
// Création d'une instance
$oInOut = new INOUT($db);


//envoi des attributs du InOut
$oInOut->dateInOut=$_POST['dateInOut'];
$oInOut->inOut_commentaire=$_POST['inOut_commentaire'];
$oInOut->id_info_job=$_POST['id_info_job'];
$oInOut->inOut_recommendation=$_POST['inOut_recommendation'];

$oInOut->updateinOut();



//Update du statut des splits
include '../models/statut-model.php';
$oStatut = new StatutModel($db);
foreach ($oStatut->getJobFromInfoJob($_POST['id_info_job']) as $key => $value) {
	$oStatut->id_tbljob=$value['id_tbljob'];
	$state=$oStatut->findStatut();
}







//Pour chaque master eprouvette (si non null)

$formInOutMaster = explode("&", $_POST['formInOutMaster']);
if ($formInOutMaster[0]!="") {
	foreach ($formInOutMaster as $key => $value) {
		$type=explode("=", $value)[0];
		$idMaster=explode("_", explode("=", $value)[1])[0];
		$date = (explode("_", explode("=", $value)[1])[1]=="")? "NULL" : '"'.explode("_", explode("=", $value)[1])[1].'"';

		$oInOut->updateinOutMasterEp($type, $idMaster, $date);
	}
}

//Pour chaque eprouvette
$formInOutEp = explode("&", $_POST['formInOutEp']);
if ($formInOutEp[0]!="") {
	foreach ($formInOutEp as $key => $value) {
		$type=explode("=", $value)[0];
		$id=explode("_", explode("=", $value)[1])[0];
		$date = (explode("_", explode("=", $value)[1])[1]=="")? "NULL" : '"'.explode("_", explode("=", $value)[1])[1].'"';

		$oInOut->updateinOutEp($type, $id, $date);
	}
}


$maReponse = array('id_tbljob' => $_POST['id_tbljob']);
echo json_encode($maReponse);
?>
