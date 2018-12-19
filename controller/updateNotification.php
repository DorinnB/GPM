<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/lstNotification-model.php';
$oNotification = new NotificationModel($db);



if ($_POST['type']=="notificate") {
  $oNotification->updateNotificate($_POST['id_notification']);
}
elseif ($_POST['type']=="delete") {
  $oNotification->updateDelete($_POST['id_notification']);
}
elseif ($_POST['type']=="checkNotification") {
  $oNotification->getCountNotificationTo();
}
elseif ($_POST['type']=="sendNotification") {
  if (isset($_POST['receiver_type'])) {  //frame
    foreach ($_POST['id_receiver_frame'] as $key => $value) {

      $oNotification = new NotificationModel($db);

      $oNotification->transmitter=$_COOKIE['id_user'];
      $oNotification->subject=$_POST['subject'];
      $oNotification->text=$_POST['text'];
      $oNotification->user="";
      $oNotification->frame=$value;
      $oNotification->newNotification('frame');
    }
  }
  else {  //user
    foreach ($_POST['id_receiver_user'] as $key => $value) {

      $oNotification = new NotificationModel($db);
      
      $oNotification->transmitter=$_COOKIE['id_user'];
      $oNotification->subject=$_POST['subject'];
      $oNotification->text=$_POST['text'];
      $oNotification->user=$value;
      $oNotification->frame="";
      $oNotification->newNotification('user');
    }
  }

  //$oNotification->newNotification();
}




?>
