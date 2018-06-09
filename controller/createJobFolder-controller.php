<?php

$dir_source = '//Srvdc/donnees/JOB/#TEMPLATE';
$dir_dest = '//Srvdc/donnees/JOB/'.$_POST['ref_customer'].'/'.$_POST['ref_customer'].'-'.$_POST['job'];
$dir_dest_customer = '//Srvdc/donnees/JOB/'.$_POST['ref_customer'];

//check si le repertoire job n'existe pas
if(!is_dir($dir_dest)){
  //creation du repertoire client si inexistant
  if (!is_dir($dir_dest_customer)) {
    mkdir($dir_dest_customer, 0755);
  }

  mkdir($dir_dest, 0755);

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

  rename ($dir_dest."/8xxx-xxxxx Donnees Brutes MRSAS", $dir_dest."/".$_POST['ref_customer'].'-'.$_POST['job']." Donnees Brutes MRSAS");

}
?>
