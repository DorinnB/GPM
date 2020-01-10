<?php
$ini = parse_ini_file('../var/config.ini');

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



echo 'C est lent, mais ca va ouvrir tous les rapports simplifiés de ce client.';

// Rendre votre modèle accessible
include '../models/lstJobs-model.php';
$oJob = new LstJobsModel($db);
$lstJobCust=$oJob->getWeeklyReportCust($_GET['customer']);


foreach ($lstJobCust as $key => $value) {
  $infoJobs[$value['id_info_job']]=$oJob->getWeeklyReportJob($value['id_info_job']);
}



//pour chaque job ayant une invoiceline, on crée la facture dans UBR
echo '<script>function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}';






//window.open("createInvoice-controller.php?UBR=1&id_tbljob='.$value['id_tbljob'].'", "'.$value['id_tbljob'].'", "width=200, height=100");
foreach ($infoJobs as $key => $value) {
foreach ($value as $k => $v) {
if (is_numeric($v['split'])) {
  echo '
    setTimeout(function() {window.open("createReport-controller.php?id_tbljob='.$v['id_tbljob'].'&type=Report&language=USA&specific=Std", "'.$v['id_tbljob'].'", "width=200, height=100");
      ';
    }
  }
}
foreach ($infoJobs as $key => $value) {
  foreach ($value as $k => $v) {
      if (is_numeric($v['split'])) {
      echo '
    }, 10000);
    ';
  }
}
}

echo '</script>';
