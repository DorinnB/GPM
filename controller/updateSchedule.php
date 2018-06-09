<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php







// Rendre votre modèle accessible
include '../models/schedule-model.php';
// Création d'une instance
$oSchedule = new SCHEDULE($db);


//envoi des attributs du Schedule
$oSchedule->dateSchedule=date('Y-m-d H:i:s');
$oSchedule->schedule_commentaire=$_POST['schedule_commentaire'];
$oSchedule->id_info_job=$_POST['id_info_job'];
$oSchedule->schedule_recommendation=$_POST['schedule_recommendation'];

$oSchedule->updateSchedule();






//Pour la date de reception initiale (si non null)
$formScheduleInitial = explode("&", $_POST['formScheduleInitial']);
if ($formScheduleInitial[0]!="") {
//on ne prend que le th (0) a cause du dédoublement des th avec datatables
		$date = (explode("=", $formScheduleInitial[0])[1]=="")? "NULL" : explode("=", $formScheduleInitial[0])[1];

		$oSchedule->updateScheduleInitial($date);
	}


//Pour chaque split
$formScheduleSplit = explode("&", $_POST['formScheduleSplit']);
if ($formScheduleSplit[0]!="") {
	foreach ($formScheduleSplit as $key => $value) {
		$arraysplit[explode("=", $value)[0]]['id']=explode("-", explode("=", $value)[0])[0];
		$arraysplit[explode("=", $value)[0]]['type']=explode("-", explode("=", $value)[0])[1];
		$arraysplit[explode("=", $value)[0]]['date'] = (explode("=", $value)[1]=="")? "NULL" : explode("=", $value)[1];

	}
//on regroupe les demandes ensembles (a cause du dédoublement des th avec datatables)
	foreach ($arraysplit as $key => $value) {
		$oSchedule->updateScheduleSplit($value['type'], $value['id'], $value['date']);
	}

}
//echo 'en attendant que ca marche';

//Update du statut des splits
include '../models/statut-model.php';
$oStatut = new StatutModel($db);
foreach ($oStatut->getJobFromInfoJob($_POST['id_info_job']) as $key => $value) {
	$oStatut->id_tbljob=$value['id_tbljob'];
	$state=$oStatut->findStatut();
}


$maReponse = array('id_tbljob' => $_POST['id_info_job']);
echo json_encode($maReponse);
?>
