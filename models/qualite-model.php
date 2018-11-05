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

        WHERE checked = 0 AND tbljob_actif=1 AND invoice_type!=2
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

    public function RawData(){
      $req='SELECT id_tbljob, job, split
      FROM tbljobs
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
      LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
      WHERE id_rawdata!=0 AND report_rawdata<=0
      AND info_job_actif=1
      AND tbljob_actif=1
      AND invoice_type!=2
      ORDER BY job DESC, split ASC
      ';
      return $this->db->getAll($req);
    }

    public function getFlagJob() {
      $req='SELECT customer, job, split, tbljobs.id_tbljob, count(*) as nb
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

    public function getFlagPareto($startDate='2000-01-01',$endDate="NOW()") {
      $req='SELECT count(*) as nb, incident_cause
        FROM `flagqualite_incidentcauses`
        LEFT JOIN incident_causes ON incident_causes.id_incident_cause=flagqualite_incidentcauses.id_incident_cause
        LEFT JOIN eprouvettes ON flagqualite_incidentcauses.id_eprouvette=eprouvettes.id_eprouvette
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
        WHERE enregistrementessais.date BETWEEN '.$this->db->quote($startDate).' AND '.$this->db->quote($endDate).'
        GROUP BY incident_cause
        ORDER BY count(*) DESC';
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

    public function getTDR($id) {
      $req='SELECT id_TDR, id_eprouvette, TDRs.id_TDR_type, cyclenumber, TDR_text,TDR_type, TDR_type_MRI, technicien
        FROM TDRs
        LEFT JOIN TDR_types ON TDR_types.id_TDR_type=TDRs.id_TDR_type
        LEFT JOIN techniciens ON techniciens.id_technicien=TDRs.TDR_user
        WHERE TDRs.id_TDR='.$this->db->quote($id).'
        ';
        //echo $req;
        return $this->db->getOne($req);
    }
}
