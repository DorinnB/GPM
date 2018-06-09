<?php
class ComputerModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllComputer() {
      $req='SELECT * FROM enregistreurs where enregistreur_actif=1 ORDER BY enregistreur;';
        return $this->db->getAll($req);
    }


}
