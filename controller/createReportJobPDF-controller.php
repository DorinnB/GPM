<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_tbljob']) OR $_GET['id_tbljob']=="")	{
  exit();
}


// Rendre votre modèle accessible
include '../models/splitTab-model.php';
$oTabs = new LstTabModel($db,$_GET['id_tbljob']);
$splitOrder=$oTabs->getTab();

// Rendre votre modèle accessible
include '../models/split-model.php';
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);
$split=$oSplit->getSplit();



$ini['PATH_JOB_antislash']=str_replace('/','\\', $ini['PATH_JOB']);

$reportJob = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Finals'.'/'.$split['job'].'.pdf';
if (file_exists($reportJob)) {

  $filename = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Finals'.'/'.$split['customer'].'-'.$split['job'].'.pdf';

  if (file_exists($filename)) { unlink ($filename); }


  $lstFile = '"'.$ini['PATH_JOB_antislash'].$split['customer'].'\\'.$split['customer'].'-'.$split['job'].'\\Rapports Finals'.'\\'.$split['job'].'.pdf" ';

  foreach ($splitOrder as $key => $value) {
    $Report = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Finals'.'/'.$split['job'].'-'.$value['split'].'.pdf';
    if(file_exists($Report)){
      $lstFile.=  '"'.$ini['PATH_JOB_antislash'].$split['customer'].'\\'.$split['customer'].'-'.$split['job'].'\\Rapports Finals'.'\\'.$split['job'].'-'.$value['split'].'.pdf" ';
    }
  }

  foreach ($splitOrder as $key => $value) {
    $Annexe = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Annexe PDF'.'/'.$split['customer'].'-'.$split['job'].'-'.$value['split'];
    if(is_dir($Annexe)){
      $lstFile.=  '"'.$ini['PATH_JOB_antislash'].$split['customer'].'\\'.$split['customer'].'-'.$split['job'].'\\Annexe PDF'.'\\'.$split['customer'].'-'.$split['job'].'-'.$value['split'].'" ';
    }
  }

  $lstFile.= '"'.$ini['PATH_GPMlocal'].'templates/postReport.pdf"';


  $destFile='"\\\SRVDC\DONNEES\job\\'.$split['customer'].'\\'.$split['customer'].'-'.$split['job'].'\Rapports Finals\\'.$split['customer'].'-'.$split['job'].'.pdf"';


  $cmd='wscript.exe '.$ini['PATH_GPMlocal'].'lib/JobReportAnnexePDF.vbs '.$split['customer'].' '.$split['customer'].'-'.$split['job'].' '.$lstFile.' '.$destFile;



  $WshShell = new COM("WScript.Shell");

  $obj = $WshShell->Run('cmd /C '.$cmd, 0, false);

exit;

  $tempMax=0;
  while( !file_exists($filename) OR $tempMax>60)  {
    sleep(1);
    $tempMax+=1;
  }
  sleep(2);
  header("Content-type:application/pdf");
  // It will be called downloaded.pdf
  header("Content-Disposition:attachment;filename=".$split['customer']."-".$split['job'].".pdf");

  // The PDF source is in original.pdf
  readfile($filename);



}
else {  //aucun fichier à faire
  echo $reportJob.' was not found<br/>Please convert your xlsx report to pdf with the right filename';
}


?>
