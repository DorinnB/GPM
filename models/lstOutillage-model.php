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


}
