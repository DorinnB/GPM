<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_tbljob']) OR $_GET['id_tbljob']=="")	{
  exit();

}



// Rendre votre modèle accessible
include '../models/split-model.php';

$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);

$split=$oSplit->getSplit();



$cmdReport='';
$cmdAnnexe='';

$report = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Temp'.'/'.$split['customer'].'-'.$split['job'].'-'.$split['split'].'.pdf';
if (file_exists($report)) {
  $cmdReport='Report';
}
$Annexe = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Annexe PDF'.'/'.$split['customer'].'-'.$split['job'].'-'.$split['split'];
if(is_dir($Annexe)){
  $cmdAnnexe='Annexe';
}



if ($cmdReport!='') { //si $cmd n'est pas vide, on execute le bon batch

  $filename = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Finals'.'/'.$split['customer'].'-'.$split['job'].'-'.$split['split'].'.pdf';

  if (file_exists($filename)) { unlink ($filename); }

  //Copy de l'excel vers rapport final
  $srcfile = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Temp'.'/'.$split['customer'].'-'.$split['job'].'-'.$split['split'].'.xlsx';
  $dstfile = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Finals'.'/'.$split['customer'].'-'.$split['job'].'-'.$split['split'].'.xlsx';
  copy($srcfile, $dstfile);

  $cmd=$ini['PATH_GPMlocal'].'lib/'.$cmdReport.$cmdAnnexe.'PDF.bat '.$split['customer'].' '.$split['customer'].'-'.$split['job'].' '.$split['customer'].'-'.$split['job'].'-'.$split['split'];
  //echo $cmd.'</br>';

  pclose(popen("start /B ". $cmd, "r"));

  //system("cmd /k C:/wamp/www/GPM/temp/test.bat");


  $tempMax=0;
  while( !file_exists($filename) OR $tempMax>60)  {
    sleep(1);
    $tempMax+=1;
  }


  header("Content-type:application/pdf");
  // It will be called downloaded.pdf
  header("Content-Disposition:attachment;filename=".$split['customer']."-".$split['job']."-".$split['split'].".pdf");

  // The PDF source is in original.pdf
  readfile($filename);
}
elseif ($cmdAnnexe!='') { //si $cmd n'est pas vide, on execute le bon batch

  $filename = $ini['PATH_JOB'].$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/Rapports Finals/Annexe_'.$split['customer'].'-'.$split['job'].'-'.$split['split'].'.pdf';

  if (file_exists($filename)) { unlink ($filename); }


  $cmd=$ini['PATH_GPMlocal'].'lib/'.$cmdAnnexe.'PDF.bat '.$split['customer'].' '.$split['customer'].'-'.$split['job'].' '.$split['customer'].'-'.$split['job'].'-'.$split['split'];
  //echo $cmd.'</br>';
  pclose(popen("start /B ". $cmd, "r"));

  //system("cmd /k C:/wamp/www/GPM/temp/test.bat");


  $tempMax=0;
  while( !file_exists($filename) OR $tempMax>60)  {
    sleep(1);
    $tempMax+=1;
  }


  header("Content-type:application/pdf");
  // It will be called downloaded.pdf
  header("Content-Disposition:attachment;filename=Annexe_".$split['customer']."-".$split['job']."-".$split['split'].".pdf");

  // The PDF source is in original.pdf
  readfile($filename);
}
else {  //aucun fichier à faire
  echo $report.' was not found<br/>Please convert your xlsx report to pdf with the right filename';
}


?>
