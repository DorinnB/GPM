<?php
class CellLoadModel
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

  public function getAllCellLoad() {
    $req='SELECT * FROM cell_load where cell_load_actif=1 ORDER BY cell_load_serial;';
    return $this->db->getAll($req);
  }

  public function getCellLoad($id_cell_load) {
    $req='SELECT * FROM cell_load where id_cell_load = '.$id_cell_load.' AND cell_load_actif=1 ORDER BY id_cell_load DESC LIMIT 1;';
    echo json_encode( $this->db->getOne($req));;
  }

  public function getAllCell_loadLocation() {
    $req='SELECT DISTINCT(cell_load_serial), cell_load.id_cell_load, machine, cell_load_capacity, cell_load_comment
    FROM cell_load
    LEFT JOIN postes ON postes.id_cell_load=cell_load.id_cell_load
    LEFT JOIN machines ON machines.id_machine=postes.id_machine

    WHERE id_poste=(
      SELECT MAX( p1.id_poste)
      FROM postes p1
      WHERE p1.id_cell_load = cell_load.id_cell_load
    );
    AND cell_load_actif=1
    ORDER BY extensometre;';

    return $this->db->getAll($req);
  }

  public function updateCell_Load(){

    $reqUpdate='UPDATE cell_load
    SET cell_load_comment = '.$this->cell_load_comment.'
    WHERE id_cell_load = '.$this->id;

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }
}
