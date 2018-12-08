<?php
class ServovalveModel
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

  public function getAllServovalve() {
    $req='SELECT * FROM servovalves where servovalve_actif=1 ORDER BY servovalve;';
    return $this->db->getAll($req);
  }

  public function getLastSeen($id) {
    $req='SELECT machine
    FROM postes
    LEFT JOIN machines ON machines.id_machine=postes.id_machine
    WHERE id_servovalve1='.$this->db->quote($id).' OR id_servovalve2='.$this->db->quote($id).'
    ORDER BY id_poste DESC
    LIMIT 1;';
    return $this->db->getOne($req);
  }

  public function getAllServovalveLocation() {
    $req='SELECT DISTINCT(servovalve), servovalves.id_servovalve, machine, servovalve, servovalve_model, servovalve_comment, servovalve_capacity
    FROM servovalves
    LEFT JOIN postes ON postes.id_servovalve1=servovalves.id_servovalve OR postes.id_servovalve2=servovalves.id_servovalve
    LEFT JOIN machines ON machines.id_machine=postes.id_machine

    WHERE id_poste=(
      SELECT MAX( p1.id_poste)
      FROM postes p1
      WHERE p1.id_servovalve1 = servovalves.id_servovalve OR p1.id_servovalve2 = servovalves.id_servovalve
    )
    AND servovalve_actif=1
    ORDER BY servovalve;';

    return $this->db->getAll($req);
  }

  public function updateServovalve(){

    $reqUpdate='UPDATE servovalves
    SET servovalve_comment = '.$this->comments.'
    WHERE id_servovalve = '.$this->id;

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }
}
