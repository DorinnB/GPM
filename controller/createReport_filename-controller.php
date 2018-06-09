<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_tbljob']) OR $_GET['id_tbljob']=="")	{
  exit();
}

// Rendre votre modèle accessible
include '../models/split-model.php';

$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);

$split=$oSplit->getSplit();

if (isset($split['split']))		//groupement du nom du job avec ou sans indice
  $jobcomplet= $split['customer'].'-'.$split['job'].'-'.$split['split'];
else
  $jobcomplet= $split['customer'].'-'.$split['job'];


copy('../lib/PHPExcel/files/DRAFT_Report_'.$jobcomplet.'.xlsx', '//Srvdc/donnees/JOB/'.$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Finals/DRAFT_Report_'.$jobcomplet.'.xlsx');

?>
