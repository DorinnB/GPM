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



/*
$dir_source = '//SRVDC/DONNEES/job/'.$split['customer'].'/'.$split['customer'].'-'.$split['job'].'/'.'ANNEXE PDF/'.$split['customer'].'-'.$split['job'].'-'.$split['split'];
$dir_dest='../temp/'.$split['customer'].'-'.$split['job'].'-'.$split['split'];

//check si le repertoire source existe et on copie sur GPM les annexes
if(is_dir($dir_source)){
  //creation du repertoire client si inexistant
  if (!is_dir($dir_dest)) {
    mkdir($dir_dest, 0755);
  }


  $dir_iterator = new RecursiveDirectoryIterator($dir_source, RecursiveDirectoryIterator::SKIP_DOTS);
  $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
  //copie recursive du contenu source
  foreach($iterator as $element){

    if($element->isDir()){
      mkdir($dir_dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
    } else{
      copy($element, $dir_dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
    }
  }
}

*/

$cmd='C:/wamp/www/GPM/lib/AnnexePDF.bat '.$split['customer'].' '.$split['customer'].'-'.$split['job'].' '.$split['customer'].'-'.$split['job'].'-'.$split['split'];
//echo $cmd.'</br>';
pclose(popen("start /B ". $cmd, "r"));

//system("cmd /k C:/wamp/www/GPM/temp/test.bat");

$filename = '../temp/Annexe_'.$split['customer'].'-'.$split['job'].'-'.$split['split'].'.pdf';

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

//print '<a href="'.$filename.'">download PDF</a>';

?>
