<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php
$ini = parse_ini_file('../var/config.ini');



// Rendre votre modèle accessible
include '../models/split-model.php';
$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);
$infoSplit = $oSplit->getInfoSplit();
$split=$oSplit->getSplit();


include '../models/workflow.class.php';
$oWorkflow = new WORKFLOW($db,$_POST['id_tbljob']);
$splits=$oWorkflow->getAllSplit();

include '../models/eprouvettes-model.php';
$oEprouvettes = new LstEprouvettesModel($db,$_POST['id_tbljob']);
$n_fichier=$oEprouvettes->getAllTests();

//Update du statut des splits
include '../models/statut-model.php';
$oStatut = new StatutModel($db);



if ($_POST['type']=="check") {

  //check trans/n_fichier
  if ($n_fichier) {
    foreach ($n_fichier as $key => $value) {
      if(!is_dir($ini['PATH_TRANS'].$value['n_fichier']) AND !is_dir($ini['PATH_TRANS'].$value['n_fichier'].' [A]')){
        $return['missingTrans'][]=$value['n_fichier'];
      }
    }
  }
  //check TestFile/n_fichier
  if ($n_fichier) {
    foreach ($n_fichier as $key => $value) {
      if(!is_dir($ini['PATH_JOB'].$infoSplit['customer'].'/'.$infoSplit['customer'].'-'.$infoSplit['job'].'/TestFile/'.$value['n_fichier'])){
        $return['missingTestFile'][]=$value['n_fichier'];
      }
    }
  }

  //check report sent
  foreach ($splits as $splitJob){

    $return['splitStatus'][]='<div style="background-color:'.(($splitJob['etape']>=90)?'darkgreen':'darkred').';">'.$splitJob['split'].'-'.$splitJob['test_type_abbr'].' - '.$splitJob['statut'].'</div>';



    if (is_numeric($splitJob['split'])){

      $return['unchecked'][]='<div style="background-color:'.(($splitJob['nb_unDchecked']>0)?'darkred':'darkgreen').';">'.$splitJob['split'].'-'.$splitJob['test_type_abbr'].' ('.$splitJob['nb_unDchecked'].')</div>';

      if ($splitJob['report_send']<0 OR $splitJob['report_send']==0) {
        $return['missingReport'][]='<div style="background-color:darkred;">'.$splitJob['job'].' - '.$splitJob['split'].'</div>';
      }
      if ($splitJob['shipped']!=$splitJob['expected']) {
        $return['missingShipped'][]='<div style="background-color:darkred;">'.$splitJob['job'].' - '.$splitJob['split'].'</div>';
      }
    }
  }

  //check invoice sent
  if ($split['invoice_date']>0) {
    $return['missingInvoice']='<p style="background-color:darkgreen;">Invoiced</p>';
  }
  else {
    $return['missingInvoice']='<p style="background-color:darkred;">Missing</p>';
  }

  //check if onenote
  $filename = '../OneNote/15.0/Sauvegarder/Notebook-JOBS En Cours/'.$infoSplit['customer'].'-'.$infoSplit['job'].'*.one';

  if(count(glob($filename)) == 0){
    $return['oneNote']='<p style="background-color:darkred;">Missing</p>';
  }
  else {
    $return['oneNote']='<p style="background-color:darkgreen;">Found</p>';
  }




  $myJSON = json_encode($return);

  echo $myJSON;

}
elseif ($_POST['type']=="closeJob") {

  foreach ($splits as $key => $value) {
    $oStatut->id_tbljob=$value['id_tbljob'];
    $state=$oStatut->updateStatut2(95);
    $lstSplit[]=$value['id_tbljob'];
  }

  $maReponse = array('result' => 'OK', 'job'=> $infoSplit['customer'].'-'.$infoSplit['job'], 'splits' => $lstSplit);
  echo json_encode($maReponse);

}
elseif ($_POST['type']=="copyTestFile") {

  function copy_directory($src,$dst) {
    $dir = opendir($src);
    mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if ( is_dir($src . '/' . $file) ) {
          recurse_copy($src . '/' . $file,$dst . '/' . $file);
        }
        else {
          copy($src . '/' . $file,$dst . '/' . $file);
        }
      }
    }

    //rename($src, $src.' [A]');
    closedir($dir);
  }
  $nb=0; $lastFile='';
  //check TestFile/n_fichier
  if ($n_fichier) {
    $dir_job=$ini['PATH_JOB'].$infoSplit['customer'].'/'.$infoSplit['customer'].'-'.$infoSplit['job'].'/TestFile/';

    //Temporaire, création testfile
    if(!is_dir($dir_job)){
      mkdir($dir_job);
    }

    foreach ($n_fichier as $key => $value) {
      $dir_source = $ini['PATH_TRANS'].$value['n_fichier'];
      $dir_dest = $dir_job.$value['n_fichier'];

      if(is_dir($dir_source)){
        if(!is_dir($dir_dest)){

          copy_directory($dir_source, $dir_dest);
          $nb++;
          $lastFile=$value['n_fichier'];
        }
      }
    }
  }

  $maReponse = array('lastFile' => $lastFile, 'nb'=> $nb);
  echo json_encode($maReponse);
}
elseif ($_POST['type']=="zipJob") {


  //Enter the name of directory
  $rootPath = realpath($ini['PATH_JOB'].$infoSplit['customer'].'/'.$infoSplit['customer'].'-'.$infoSplit['job'].'/');




  // Get real path for our folder
  $rootPath = realpath($ini['PATH_JOB'].$infoSplit['customer'].'/'.$infoSplit['customer'].'-'.$infoSplit['job'].'/');

  // Initialize archive object
  $zip = new ZipArchive();
  $zip->open($ini['PATH_JOB'].$infoSplit['customer'].'/'.$infoSplit['customer'].'-'.$infoSplit['job'].'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

  // Create recursive directory iterator
  /** @var SplFileInfo[] $files */
  $files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
  );

  foreach ($files as $name => $file)
  {
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
      // Get real and relative path for current file
      $filePath = $file->getRealPath();
      $relativePath = substr($filePath, strlen($rootPath) + 1);

      // Add current file to archive
      $zip->addFile($filePath, $relativePath);
    }
  }

  // Zip archive will be created only after closing object
  $zip->close();



  /*
  * php delete function that deals with directories recursively
  */
  function delete_files($target) {
    if(is_dir($target)){
      $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

      foreach( $files as $file ){
        delete_files( $file );
      }

      rmdir( $target );
    } elseif(is_file($target)) {
      unlink( $target );
    }
  }
  delete_files($ini['PATH_JOB'].$infoSplit['customer'].'/'.$infoSplit['customer'].'-'.$infoSplit['job'].'/');


  $maReponse = array('result' => 'OK', 'job'=> $infoSplit['customer'].'-'.$infoSplit['job']);
  echo json_encode($maReponse);

}
elseif ($_POST['type']=="archiveJob") {

  foreach ($splits as $key => $value) {
    $oStatut->id_tbljob=$value['id_tbljob'];
    $state=$oStatut->updateStatut2(100);
    $lstSplit[]=$value['id_tbljob'];
  }

  $maReponse = array('result' => 'OK', 'job'=> $infoSplit['customer'].'-'.$infoSplit['job'], 'splits' => $lstSplit);
  echo json_encode($maReponse);

}
else {
  // code...
}
