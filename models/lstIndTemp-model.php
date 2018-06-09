<?php
class IndTempModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllIndTemp() {
      $req='SELECT * FROM ind_temps where ind_temp_actif=1 ORDER BY ind_temp;';
        return $this->db->getAll($req);
    }


}
