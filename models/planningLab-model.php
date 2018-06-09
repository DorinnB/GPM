<?php
class PLANNINGLAB
{

  protected $db;


  public function __construct($db)
  {
    $this->db = $db;
  }


  public function __set($property,$value) {

      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);

  }



  public function getAllPlanningLab(){
    $req='SELECT * from planningLab
    ';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllPlanningFrame($id_machine){
    $req='SELECT planningLab.id_tbljob, date, id_machine, customer, job, split from planningLab
    LEFT JOIN tbljobs ON tbljobs.id_tbljob=planningLab.id_tbljob
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    where id_machine='.$id_machine.'
    AND date >= NOW() - INTERVAL 10 DAY
    ';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function updatePlanningLab(){
    $reqUpdate='
    INSERT INTO planningLab
    (date, id_machine, id_tbljob)
    VALUES ("'.$this->date.'",'.$this->id_machine.','.$this->id_tbljob.')
    ON DUPLICATE KEY UPDATE id_tbljob=values(id_tbljob)
    ;';
    echo $reqUpdate;
    $result = $this->db->query($reqUpdate);
  }

  public function deletePlanningLab(){
    $reqDelete='DELETE FROM planningLab
    WHERE date="'.$this->date.'" AND id_machine='.$this->id_machine.'
    ;';
    echo $reqDelete;
    $result = $this->db->query($reqDelete);
  }

  public function getPlanningSplit($id_tbljob){
    $req='SELECT GROUP_CONCAT( distinct machine ORDER BY machine ASC SEPARATOR " ") as machines
    FROM planninglab
    LEFT JOIN machines ON machines.id_machine=planninglab.id_machine
    WHERE id_tbljob='.$this->db->quote($id_tbljob).'
    AND date >= NOW()
    GROUP BY id_tbljob
    ';
    //echo $req;
    return $this->db->getOne($req);
  }

  public function getPlanningDay(){
    $req='SELECT count(*) as nb
    FROM planninglab
    WHERE planninglab.id_tbljob>50
    AND date = CURDATE()
    ';
    //echo $req;
    return $this->db->getOne($req);
  }
}
