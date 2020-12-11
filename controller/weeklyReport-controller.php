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


$lstContactsString =""; //déclaration
foreach ($lstJobCust as $key => $value) {
  $infoJobs[$value['id_info_job']]=$oJob->getWeeklyReportJob($value['id_info_job']);
$lstContactsString .= $value['contactsEmail'];
}

$lstContactsArray = explode(";", $lstContactsString);

$lstContact = implode(";", array_unique($lstContactsArray));
$lstContact .= ';'.$customer['weeklyemail'];
//var_dump($infoJobs);


// Rendre votre modèle accessible
include 'models/invoice-model.php';
$oInvoices = new InvoiceModel($db);
$invPO=$oInvoices->getInvoiceTotal($_GET['customer']);

foreach ($lstJobCust as $key => $value) {
$lstJobCust[$key]['invMetcut']=(isset($invPO[$value['id_info_job']]['invMetcut']))?$invPO[$value['id_info_job']]['invMetcut']:0;
$lstJobCust[$key]['invSubC']=(isset($invPO[$value['id_info_job']]['invSubC']))?$invPO[$value['id_info_job']]['invSubC']:0;
}

?>
