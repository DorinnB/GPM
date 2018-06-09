<?php
class LstTabModel
{

protected $db;
private $id;

public function __construct($db,$id)
{
    $this->db = $db;
    $this->id = $id;
}

    public function getTab() {

		$req = 'SELECT id_tbljob, statut, statut_color, test_type, test_type_abbr, local, ST, split, etape
        FROM tbljobs
        LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
        LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
        LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        WHERE tbljobs.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->id.')
          AND tbljob_actif=1
          AND split IS NOT NULL
        ORDER BY phase';
//echo $req;
        return $this->db->getAll($req);
    }
}
