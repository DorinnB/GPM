<?php
class MatiereModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllMatiere() {
      $req='SELECT * FROM matieres where matiere_actif=1 ORDER BY id_matiere=0, type_matiere, matiere;';
      //echo $req;
        return $this->db->getAll($req);
    }


}
