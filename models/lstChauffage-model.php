<?php
class ChauffageModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllChauffage() {
      $req='SELECT * FROM chauffages where chauffage_actif=1 ORDER BY chauffage;';
        return $this->db->getAll($req);
    }


}
