<?php
class QualiteModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUncheckedJob() {
      $req='SELECT customer, job, split, id_tbljob
        FROM tbljobs
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job

        WHERE checked = 0 AND tbljob_actif=1
        GROUP BY id_tbljob
        ORDER BY job, split';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getUncheckedStartedJob() {
      $req='SELECT customer, job, split, tbljobs.id_tbljob
        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        WHERE c_checked =0
        GROUP BY id_job
        ORDER BY job, split';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getFlagJob() {
      $req='SELECT customer, job, split, tbljobs.id_tbljob
        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        WHERE flag_qualite > 0
        GROUP BY id_job
        ORDER BY job, split';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getTemperatureCorrectionParameters() {
      $req='SELECT *
        FROM temperature_correction_parameters
        ORDER BY date_temperature_correction_parameter DESC
        LIMIT 20';
        //echo $req;
        return $this->db->getAll($req);
    }
}
