<?php
class ChauffageModel
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

    public function getAllChauffage() {
      $req='SELECT * FROM chauffages where chauffage_actif=1 ORDER BY chauffage;';
        return $this->db->getAll($req);
    }

    public function getAllHeatingLocation() {
      $req='SELECT DISTINCT(chauffage), chauffages.id_chauffage, machine, type_chauffage, chauffage_comment
      FROM chauffages
      LEFT JOIN postes ON postes.id_chauffage=chauffages.id_chauffage
      LEFT JOIN machines ON machines.id_machine=postes.id_machine

      WHERE id_poste=(
        SELECT MAX( p1.id_poste)
        FROM postes p1
        WHERE p1.id_chauffage = chauffages.id_chauffage
      );
      AND chauffage_actif=1
      ORDER BY chauffage;';

      return $this->db->getAll($req);
    }

    public function updateHeating(){

      $reqUpdate='UPDATE chauffages
      SET chauffage_comment = '.$this->chauffage_comment.'
      WHERE id_chauffage = '.$this->id;

      //echo $reqUpdate;
      $this->db->query($reqUpdate);
    }
}
