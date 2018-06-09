<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()







// Rendre votre modèle accessible
include 'models/lstJobs-model.php';
$oJob = new LstJobsModel($db);
$lstInfoJob=$oJob->getWeeklyReportInfoJob($split['id_info_job']);

//$infoJobs=$oJob->getWeeklyReportJob($split['id_info_job']);



include 'views/checkSplitEmail-view.php';


 ?>
