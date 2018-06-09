<?php
class ExtensometreModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllExtensometre() {
      $req='SELECT * FROM extensometres where extensometre_actif=1 ORDER BY extensometre;';
        return $this->db->getAll($req);
    }

}
