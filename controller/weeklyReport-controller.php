<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()




// Rendre votre modèle accessible
include 'models/lstContact-model.php';
$oCustomers = new ContactModel($db);
$entreprises=$oCustomers->getAllref_customer();




if (!isset($_GET['customer']))	{
  $customer['id_entreprise']='';
  $customer['entreprise'] ="Choose your customer";
  $_GET['customer']=0;
}
else {
  $customer=$oCustomers->getClient($_GET['customer']);
}




// Rendre votre modèle accessible
include 'models/lstJobs-model.php';
$oJob = new LstJobsModel($db);
$lstJobCust=$oJob->getWeeklyReportCust($_GET['customer']);



foreach ($lstJobCust as $key => $value) {
  $infoJobs[$value['id_info_job']]=$oJob->getWeeklyReportJob($value['id_info_job']);
}

//var_dump($infoJobs);


    ?>
