<?php

$clockState=$oBadge->getClockState();
$clockCount=$oBadge->getClockCount();

if ($clockState['unclocked']!=0 OR $clockState['unclocked']=="") {
    echo '<script> $("#notification").modal("show");</script>';
}

include('../views/badgeAccess-view.php');

?>
