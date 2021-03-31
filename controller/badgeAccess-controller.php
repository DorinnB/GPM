<?php

date_default_timezone_set('Europe/Paris');

if ($isBadge==1) {
  $clockState=$oBadge->getClockState();
  $clockCount=$oBadge->getClockCount();

  if ($clockState['unclocked']!=0 OR $clockState['unclocked']=="") {
      echo '<script> $("#notification").modal("show");</script>';
  }


function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}



$alreadyDone=new DateTime(date('H:i:s', strtotime($clockCount['clockCount'])));
$planned=$clockState['Q1'];
$now=date('Y-m-d H:i:s');
//planned + now
$origin = new DateTime(date('Y-m-d H:i:s',strtotime('+'.$planned.'hours')));
//minus already done
$interval = $origin->diff($alreadyDone);

$endOfWorkEstimated= $interval->format('%h:%i:%s');

console_log( $endOfWorkEstimated );




  include('../views/badgeAccess-view.php');
}
elseif ($isBadge==2) {
  $clockState=$oBadge->getClockState();
  if ($clockState['unclocked2']!=0 OR $clockState['unclocked2']=="") {
      echo '<script> $("#notification").modal("show");</script>';
  }

  include('../views/badge2Access-view.php');
}

?>
