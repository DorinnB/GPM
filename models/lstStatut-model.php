<?php
class LstStatutModel
{

protected $db;
private $id;

public function __construct($db,$id)
{
    $this->db = $db;
    $this->id = $id;
}

    public function getStatut() {

		$req = 'SELECT *
				FROM statuts
        WHERE statut_actif=1
        ORDER BY etape';
//echo $req;
        return $this->db->getAll($req);
    }
}
