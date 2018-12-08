<?php
class ExtensometreModel
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

  public function getAllExtensometre() {
    $req='SELECT * FROM extensometres where extensometre_actif=1 ORDER BY extensometre;';
    return $this->db->getAll($req);
  }

  public function getExtensometreLocation($id) {
    $req='SELECT t1.id_poste, t1.id_extensometre, extensometre, machine
    FROM postes t1
    LEFT JOIN extensometres ON extensometres.id_extensometre = t1.id_extensometre
    LEFT JOIN machines ON machines.id_machine=t1.id_machine
    WHERE t1.id_poste = (
      SELECT MAX( t2.id_poste )
      FROM postes t2
      WHERE t2.id_extensometre = t1.id_extensometre
    )
    AND extensometres.id_extensometre='.$this->db->quote($id).'
    ORDER BY t1.id_extensometre;';

    return $this->db->getAll($req);
  }

  public function getAllExtensometreLocation() {
    $req='SELECT DISTINCT(extensometre), extensometres.id_extensometre, machine, type_extensometre, Lo, extensometre_comment
    FROM extensometres
    LEFT JOIN postes ON postes.id_extensometre=extensometres.id_extensometre
    LEFT JOIN machines ON machines.id_machine=postes.id_machine

    WHERE id_poste=(
      SELECT MAX( p1.id_poste)
      FROM postes p1
      WHERE p1.id_extensometre = extensometres.id_extensometre
    );
    AND extensometre_actif=1
    ORDER BY extensometre;';

    return $this->db->getAll($req);
  }

  public function updateExtensometre(){

    $reqUpdate='UPDATE extensometres
    SET extensometre_comment = '.$this->extensometre_comment.'
    WHERE id_extensometre = '.$this->id;

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }

}
