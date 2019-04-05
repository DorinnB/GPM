<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()




// Rendre votre modèle accessible
include 'models/lstContact-model.php';
$oCustomers = new ContactModel($db);
$entreprises=$oCustomers->getAllref_customer();




if (!isset($_GET['customer']))	{
  $customer['id_entreprise']='';
  $customer['entreprise'] ="Choose your SubC";
  $_GET['customer']=0;
}
else {
  $customer=$oCustomers->getClient($_GET['customer']);
}




// Rendre votre modèle accessible
include 'models/lstJobs-model.php';
$oJob = new LstJobsModel($db);
$lstJobSubC=$oJob->getWeeklyReportSubC($_GET['customer']);



foreach ($lstJobSubC as $key => $value) {
  $infoJobs[$value['id_info_job']]=$oJob->getWeeklyReportSubCJob($value['id_info_job'], $_GET['customer']);
}

//var_dump($infoJobs);


    ?>
