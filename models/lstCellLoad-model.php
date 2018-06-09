<?php
class CellLoadModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCellLoad() {
      $req='SELECT * FROM cell_load where cell_load_actif=1 ORDER BY cell_load_serial;';
        return $this->db->getAll($req);
    }

    public function getCellLoad($id_cell_load) {
      $req='SELECT * FROM cell_load where id_cell_load = '.$id_cell_load.' AND cell_load_actif=1 ORDER BY id_cell_load DESC LIMIT 1;';
        echo json_encode( $this->db->getOne($req));;
    }
}
