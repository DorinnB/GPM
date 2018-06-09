<?php
class SCHEDULE
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


  public function getAllSchedule($id){
    $req='SELECT * from schedules
    LEFT JOIN techniciens ON techniciens.id_technicien=schedules.schedule_user
    WHERE id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob = '.$id.')
      AND schedule_actif = 1
    ORDER BY schedule_date DESC, id_schedule DESC
    ';
    //echo $req;
    return $this->db->getAll($req);
  }


  public function updateSchedule(){
    $reqUpdate='INSERT INTO schedules
        (schedule_commentaire ,id_info_job, schedule_date, schedule_user)
        VALUES
        ( '.$this->schedule_commentaire.','.$this->id_info_job.','.$this->dateSchedule.', '.$this->db->quote($_COOKIE['id_user']).')
        ';
        //echo $reqUpdate;
        $result = $this->db->query($reqUpdate);
        //    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'info_jobs' => $this->getInfoJob()['id_info_job']);
        //    			echo json_encode($maReponse);

        $reqUpdate2='UPDATE info_jobs
        SET schedule_recommendation = '.$this->schedule_recommendation.'
        WHERE id_info_job = '.$this->id_info_job;
      //echo $reqUpdate;
            $result = $this->db->query($reqUpdate2);
        //    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'info_jobs' => $this->getInfoJob()['id_info_job']);
        //    			echo json_encode($maReponse);
  }

  public function updatescheduleInitial($date){
    $reqUpdate='UPDATE info_jobs
    SET  	available_expected  = '.$this->db->quote($date).'
    WHERE id_info_job = '.$this->id_info_job;
//echo $reqUpdate;
        $result = $this->db->execute($reqUpdate);
  }

  public function updatescheduleSplit($type, $id, $date){

      $reqUpdate='UPDATE tbljobs
      SET '.$type.' = '.$this->db->quote($date).'
      WHERE id_tbljob = '.$this->db->quote($id);

  //echo $reqUpdate;
    $result = $this->db->execute($reqUpdate);
  }


}
