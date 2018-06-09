<?php
class CellDisplacementModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCellDisplacement() {
      $req='SELECT * FROM cell_displacement where cell_displacement_actif=1 ORDER BY cell_displacement_serial;';
        return $this->db->getAll($req);
    }

    public function getCellDisplacement($id_cell_displacement) {
      $req='SELECT * FROM cell_displacement where id_cell_displacement = '.$id_cell_displacement.' AND cell_displacement_actif=1 ORDER BY id_cell_displacement DESC LIMIT 1;';
        echo json_encode( $this->db->getOne($req));;
    }
}
