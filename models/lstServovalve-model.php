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

    public function getLastSeen($id) {
      $req='SELECT machine
        FROM postes
        LEFT JOIN machines ON machines.id_machine=postes.id_machine
        WHERE id_servovalve1='.$this->db->quote($id).' OR id_servovalve2='.$this->db->quote($id).'
        ORDER BY id_poste DESC
        LIMIT 1;';
      return $this->db->getOne($req);
    }
}
