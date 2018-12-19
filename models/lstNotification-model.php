<?php
class NotificationModel
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function __set($property,$value) {
    if (is_numeric($value)){
      $this->$property = $value;
    }
    else {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
    }
  }

  public function getAllNotificationFrom($history="No") {

    if (isset($_COOKIE['id_user'])) {
      $req_user = 'id_transmitter='.$this->db->quote($_COOKIE['id_user']);
    }
    else {
      $req_user ='id_transmitter IS NULL';
    }

    if ($history=="No") {
      $notification_state="AND notification_state != 0";
    }
    else {
      $notification_state="";
    }

    $req='SELECT id_notification, subject, notification, notification_date, notification_state, t1.technicien as transmitter, t2.technicien as receiver_user, machine as receiver_frame
    FROM notifications
    LEFT JOIN techniciens t1 ON t1.id_technicien=notifications.id_transmitter
    LEFT JOIN techniciens t2 ON t2.id_technicien=notifications.id_receiver_user
    LEFT JOIN machines ON machines.id_machine=notifications.id_receiver_frame
    WHERE '.$req_user.'
    '.$notification_state.'
    AND notification_date > DATE_SUB(NOW(),INTERVAL 1 YEAR)
    ORDER BY id_notification;';

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllNotificationTo($history="No") {

    if (isset($_COOKIE['id_user'])) {
      $req_user = 'id_receiver_user='.$this->db->quote($_COOKIE['id_user']);
    }
    else {
      $req_user ='false';
    }

    if (isset($_COOKIE['id_machine'])) {
      $req_frame = 'id_receiver_frame='.$this->db->quote($_COOKIE['id_machine']);
    }
    else {
      $req_frame ='false';
    }

    if ($history=="No") {
      $notification_state="AND notification_state != 0";
    }
    else {
      $notification_state="";
    }

    $req='SELECT id_notification, subject, notification, notification_date, notification_state, t1.technicien as transmitter, t2.technicien as receiver_user, machine as receiver_frame
    FROM notifications
    LEFT JOIN techniciens t1 ON t1.id_technicien=notifications.id_transmitter
    LEFT JOIN techniciens t2 ON t2.id_technicien=notifications.id_receiver_user
    LEFT JOIN machines ON machines.id_machine=notifications.id_receiver_frame
    WHERE ('.$req_user.' OR '.$req_frame.')
    '.$notification_state.'
        AND notification_date > DATE_SUB(NOW(),INTERVAL 1 YEAR)
    ORDER BY id_notification;';

    //    echo $req;
    return $this->db->getAll($req);
  }

  public function getCountNotificationTo() {

    if (isset($_COOKIE['id_user'])) {
      $req_user = 'id_receiver_user='.$this->db->quote($_COOKIE['id_user']);
    }
    else {
      $req_user ='false';
    }

    if (isset($_COOKIE['id_machine'])) {
      $req_frame = 'id_receiver_frame='.$this->db->quote($_COOKIE['id_machine']);
    }
    else {
      $req_frame ='false';
    }

    $req='SELECT sum(if(notification_state=2,1,0)) as unread, sum(if(notification_state>=1,1,0)) as countNotification
    FROM notifications
    WHERE '.$req_user.' OR '.$req_frame.'
    AND notification_state != 0';

    //echo $req;
    $notifications = $this->db->getOne($req);

    $maReponse = array('result' => 'correct', 'unread' => $notifications['unread'], 'countNotification' => $notifications['countNotification']);

    echo json_encode($maReponse);
  }

  public function updateNotificate($id){
    $reqUpdate='UPDATE notifications
    SET notification_state=IF(notification_state=2,1,2)
    WHERE id_notification = '.$this->db->quote($id);
    //echo $reqUpdate;
    $result = $this->db->execute($reqUpdate);
  }

  public function updateDelete($id){
    $reqUpdate='UPDATE notifications
    SET notification_state=0
    WHERE id_notification = '.$this->db->quote($id);
    //echo $reqUpdate;
    $result = $this->db->execute($reqUpdate);
  }

  public function newNotification(){
    var_dump($this);
    $req='INSERT INTO `notifications`
    (id_transmitter, id_receiver_user, id_receiver_frame, subject, notification)
    VALUES
    ('.$this->transmitter.', '.$this->user.', '.$this->frame.', '.$this->subject.', '.$this->text.')';
    //echo $req;
    $result = $this->db->execute($req);
  }

}
