<?php
class WORKFLOW
{

protected $db;
private $id;

public function __construct($db,$id)
{
    $this->db = $db;
    $this->id = $id;
}

    public function getAllEprouvettes() {

		$req = 'SELECT eprouvettes.id_eprouvette, master_eprouvettes.id_master_eprouvette,
          master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, master_eprouvettes.id_dwg, dessins.dessin, eprouvettes.id_job as id_tbljob,
          n_fichier,
          eprouvette_inOut_A, eprouvette_inOut_B, master_eprouvette_inOut_A, master_eprouvette_inOut_B,
          flag_qualite, d_checked, report_creation_date, enregistrementessais.date as enregistrementessais_date

        FROM eprouvettes
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette

        LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg

        WHERE master_eprouvettes.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->id.')
        AND master_eprouvette_actif=1 AND eprouvette_actif=1';
          //echo $req;
        return $this->db->getAll($req);
    }


    public function getAllGroupes() {

		$req = 'SELECT DISTINCT GROUP_CONCAT(DISTINCT id_job ORDER BY phase,"") AS ordre
            FROM eprouvettes
            LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
            WHERE id_info_job=(SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->id.')
            AND eprouvette_actif=1 AND tbljob_actif=1
            GROUP BY id_master_eprouvette';
          //echo $req;
        return $this->db->getAll($req);
    }

    public function getAllSplit() {

    $req = 'SELECT id_tbljob, test_type, test_type_abbr, test_type_cust, split, phase, sum(if(eprouvette_actif=1,1,0)) as nbep, ST, auxilaire,
        DyT_expected, DyT_SubC, DyT_Cust, refSubC, statut, statut_color, etape,
        report_Q, report_TM, report_date, report_rev, report_rawdata, id_rawData, report_send,
        count(distinct master_eprouvettes.id_master_eprouvette) as expected,
        sum(if(master_eprouvettes.master_eprouvette_inOut_B is not null,1,0)) as shipped

        FROM tbljobs
        LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
        LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
        LEFT JOIN eprouvettes ON eprouvettes.id_job=tbljobs.id_tbljob
        LEFT JOIN master_eprouvettes on master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
        WHERE tbljobs.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->id.')
          AND tbljob_actif=1
          AND master_eprouvette_actif=1
          AND eprouvette_actif=1
        GROUP BY id_tbljob
        ORDER BY phase ASC';
        //echo $req;
        return $this->db->getAll($req);
    }


    public function getEmptySplit() {

    $req = 'SELECT id_tbljob, sum(if(eprouvette_actif=1,1,0)) as nbep

        FROM tbljobs
        LEFT JOIN eprouvettes ON eprouvettes.id_job=tbljobs.id_tbljob
        WHERE tbljobs.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->id.')
          AND tbljob_actif=1
        GROUP BY id_tbljob';
        //echo $req;
        return $this->db->getAll($req);
    }


    public function delSplit($idsplit) {
        $reqDel='UPDATE tbljobs SET tbljob_actif=0 WHERE id_tbljob='.$idsplit;
        echo $reqDel.'<br/>';
        $this->db->execute($reqDel);
        echo ' split detruit .';

    }


}
