<?php
class OutillageModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllOutillage() {
      $req='SELECT * FROM outillages where outillage_actif=1 ORDER BY outillage;';
        return $this->db->getAll($req);
    }

    public function getLastSeen($id) {
      $req='SELECT machine
        FROM postes
        LEFT JOIN machines ON machines.id_machine=postes.id_machine
        WHERE id_outillage_top='.$this->db->quote($id).' OR id_outillage_bot='.$this->db->quote($id).'
        ORDER BY id_poste DESC
        LIMIT 1;';
      return $this->db->getOne($req);
    }

}
