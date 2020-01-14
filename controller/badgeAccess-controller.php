<?php


if ($isBadge==1) {
  $clockState=$oBadge->getClockState();
  $clockCount=$oBadge->getClockCount();

  if ($clockState['unclocked']!=0 OR $clockState['unclocked']=="") {
      echo '<script> $("#notification").modal("show");</script>';
  }

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
