<?php
class CalibrationModel
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



  public function getAllcalibration($type=0, $id_machine=0, $idElement=0, $limit=100) {

    $filtreElement=($idElement==0 OR !isset($idElement))?"1":"id_element=".$this->db->quote($idElement);
    $filtreMachine=($id_machine==0 OR !isset($id_machine))?"1":"id_machine=".$this->db->quote($id_machine);

    $filtreType=($type==0)?"id_type":$this->db->quote($type);   //si =0 alors tous les types
    $filtreLimit=(is_numeric($limit))?$limit:$this->db->quote($limit);

    $req='SELECT
      id_calibration, machine, dessin, matiere, thermocouple, scale, adjustment, cancelprevious, compliant, date_start, date_end, operator, checker
      FROM `calibrations`
      LEFT JOIN machines ON machines.id_machine=calibrations.id_frame
      LEFT JOIN matieres ON matieres.id_matiere=calibrations.id_material
      LEFT JOIN dessins ON dessins.id_dessin=calibrations.id_format
      WHERE id_type='.$filtreType.' AND '.$filtreMachine.' AND '.$filtreElement.'
      ORDER BY date_start DESC
      LIMIT '.$filtreLimit.'
      ;';

    //echo $req;
    return $this->db->getAll($req);
  }


  public function getAllCalibrationList() {
    $req='SELECT * FROM calibration_types where calibration_type_actif=1 ORDER BY calibration_type;';
      return $this->db->getAll($req);
  }
  public function getCalibrationList($id_calibrationList) {
    $req='SELECT id_calibration_type FROM calibration_types where id_calibration_type='.$this->db->quote($id_calibrationList).';';
      return $this->db->getOne($req);
  }


  public function getAllIndTemp() {
    $req='SELECT * FROM ind_temps where ind_temp_actif=1 ORDER BY ind_temp;';
      return $this->db->getAll($req);
  }

  public function getIndTemp($id) {
    $req='SELECT * FROM ind_temps where id_ind_temp='.$this->db->quote($id).';';
      return $this->db->getOne($req);
  }




  public function insertNewTempLine() {
    $req = 'INSERT INTO calibrations
        (	id_type, id_frame, id_element, scale,  thermocouple, date_start, date_end, operator, adjustment, compliant, cancelprevious, calibrations_actif )
        VALUES (1, (SELECT id_machine FROM machines WHERE machine='.$this->frame.' LIMIT 1),  '.$this->id_element.', '.$this->scale.', '.$this->thermocouple.', '.$this->date_start.', '.$this->date_end.', '.$this->checker.', '.$this->adjustment.', '.$this->compliant.', '.$this->cancelprevious.', 1);';

    //echo $req;
    $this->db->execute($req);
      return   $this->db->lastId();
  }

  public function checkCalibration($idCalibration) {
    $reqUpdate='UPDATE calibrations SET checker = '.$this->db->quote($_COOKIE['id_user']).', compliant = 1 WHERE calibrations.id_calibration = '.$this->db->quote($idCalibration).';';

    $result = $this->db->query($reqUpdate);
    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_calibration' => $idCalibration, 'id_user' => $_COOKIE['id_user']);
    echo json_encode($maReponse);
  }

}
