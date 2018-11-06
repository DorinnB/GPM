<?php
class ServovalveModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllServovalve() {
      $req='SELECT * FROM servovalves where servovalve_actif=1 ORDER BY servovalve;';
        return $this->db->getAll($req);
    }

    public function getServovalve($id_servovalve) {
      $req='SELECT * FROM servovalves where id_servovalve = '.$id_servovalve.' AND servovalve_actif=1 ORDER BY id_servovalve DESC LIMIT 1;';
        echo json_encode( $this->db->getOne($req));;
    }
}
