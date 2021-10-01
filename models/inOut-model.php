<?php
class INOUT
{

  protected $db;


  public function __construct($db)
  {
    $this->db = $db;
  }


  public function __set($property,$value) {
    if (is_numeric($value)){
      $this->$property = $value;
    }
    else {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
    }
  }


  public function getAllInOut($id){
    $req='SELECT * from inOut_ep
    WHERE id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob = '.$id.')
    AND inOut_actif = 1
    ORDER BY InOut_date DESC, id_inOut DESC
    ';
    //echo $req;
    return $this->db->getAll($req);
  }


  public function updateinOut(){
    $reqUpdate='INSERT INTO inOut_ep
    (inOut_commentaire ,id_info_job, inOut_date)
    VALUES
    ( '.$this->inOut_commentaire.','.$this->id_info_job.','.$this->dateInOut.')
    ';
    //echo $reqUpdate;
    $result = $this->db->query($reqUpdate);
    //    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'info_jobs' => $this->getInfoJob()['id_info_job']);
    //    			echo json_encode($maReponse);

    $reqUpdate2='UPDATE info_jobs
    SET inOut_recommendation = '.$this->inOut_recommendation.'
    WHERE id_info_job = '.$this->id_info_job;
    //echo $reqUpdate;
    $result = $this->db->query($reqUpdate2);
    //    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'info_jobs' => $this->getInfoJob()['id_info_job']);
    //    			echo json_encode($maReponse);
  }

  public function updateinOutMasterEp($type, $idMaster, $date){
    $reqUpdate='UPDATE master_eprouvettes
    SET '.$type.' = '.$date.'
    WHERE id_master_eprouvette = '.$idMaster;
    //echo $reqUpdate;
    $result = $this->db->execute($reqUpdate);
  }

  public function updateinOutEp($type, $id, $date){
    if ($type=='eprouvette_inOut_A') {
      $reqUpdate='UPDATE eprouvettes
      SET '.$type.' = '.$date.'
      WHERE id_eprouvette = '.$id;
    }
    elseif ($type=='eprouvette_inOut_B') {
      $reqUpdate='UPDATE eprouvettes
      SET '.$type.' = '.$date.'
      WHERE id_eprouvette = '.$id;
    }
    else {
      $reqUpdate = 'erreur de type de modif !';
    }

    //echo $reqUpdate;
    $result = $this->db->execute($reqUpdate);
  }


  public function awaitingArrival(){
    $req='SELECT min(tbljobs.id_tbljob) as id_tbljob, min(job) as job
    FROM eprouvettes
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    LEFT JOIN info_jobs ON info_jobs.id_info_job=master_eprouvettes.id_info_job
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE master_eprouvette_inOut_A IS NULL
    AND master_eprouvette_actif = 1
    AND eprouvette_actif = 1
    AND info_job_actif= 1
    AND tbljob_actif= 1
    AND etape != 100
    GROUP BY info_jobs.id_info_job
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function ReadyToSend(){
    $req='SELECT tbljobs.id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE tbljob_actif=1 AND etape = 40 AND ST = 1
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function oneWeek(){
    $req='SELECT tbljobs.id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE DyT_expected <= NOW() + INTERVAL 10 DAY
    AND  DyT_expected > NOW()
    AND info_job_actif=1
    AND tbljob_actif=1
    AND ST = 1
    AND etape < 90
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function overdueSubC(){
    $req='SELECT tbljobs.id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE DyT_expected < NOW()
    AND info_job_actif=1
    AND tbljob_actif=1
    AND ST = 1
    AND etape < 70
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function errorInOut(){
    $req='SELECT tbljobs.id_tbljob, max(job) AS job, split
    FROM eprouvettes
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    LEFT JOIN info_jobs ON info_jobs.id_info_job=master_eprouvettes.id_info_job
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
    WHERE master_eprouvette_inOut_A > master_eprouvette_inOut_B
    OR eprouvette_inOut_A > eprouvette_inOut_B
    AND master_eprouvette_actif = 1
    AND info_job_actif=1
    AND tbljob_actif=1
    GROUP BY tbljobs.id_tbljob
    ORDER BY job DESC, split ASC
    ';
    return $this->db->getAll($req);
  }

  public function needPO(){
    $req='SELECT min(tbljobs.id_tbljob) as id_tbljob, min(job) as job
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE po_number is null
    AND info_job_actif=1
    AND tbljob_actif=1
    AND etape!=100
    GROUP BY info_jobs.id_info_job
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function noRefSubC(){
    $req='SELECT tbljobs.id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE refSubc is null
    AND tbljob_actif=1
    AND etape = 40
    AND ST = 1
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function outReady(){ //chercher l'etape et si =100, ne pas afficher
    $req='SELECT job, split, id_tbljob
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE (
      SELECT sum(if(eprouvettes.eprouvette_inOut_B IS NOT NULL,0,1))
      FROM master_eprouvettes
      LEFT JOIN eprouvettes ON eprouvettes.id_master_eprouvette=master_eprouvettes.id_master_eprouvette
      WHERE eprouvettes.id_job=tbljobs.id_tbljob
      AND master_eprouvettes.master_eprouvette_inOut_B IS NULL
      AND master_eprouvettes.master_eprouvette_actif=1
      AND eprouvettes.eprouvette_actif=1
      GROUP BY eprouvettes.id_job
    ) = 0
    AND split>0
    AND etape<95
    GROUP BY id_tbljob
    ORDER BY id_tbljob DESC';

    return $this->db->getAll($req);
  }

  public function toBeInvoiced(){
    $req='SELECT job, MAX(id_tbljob) as id_tbljob, (SELECT invoices.invoice_final FROM invoices WHERE invoices.inv_job=info_jobs.job ORDER BY invoices.id_invoice DESC LIMIT 1) AS invoice_final
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE cast(split AS UNSIGNED)
    AND info_job_actif=1
    AND tbljob_actif=1
    AND etape=90
    AND job NOT IN (
      SELECT job
      FROM tbljobs
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
      LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
      WHERE cast(split AS UNSIGNED)
      AND info_job_actif=1
      AND tbljob_actif=1
      AND etape<90
      GROUP BY job
    )
    GROUP BY job
    ';
    return $this->db->getAll($req);
  }

  public function RawData(){
    $req='SELECT id_tbljob, job, split, count(CASE WHEN rawdatasent<=0 AND n_fichier IS NOT NULL THEN 1 END) as nbrawdataunsent
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    LEFT JOIN eprouvettes ON eprouvettes.id_job=tbljobs.id_tbljob
    LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
    WHERE rawdatatobesent=1
    AND info_job_actif=1
    AND tbljob_actif=1
    AND etape != 100
    GROUP BY id_tbljob
    ORDER BY job DESC, split ASC
    ';
    return $this->db->getAll($req);
  }

  public function stepStatut($step){
    $req='SELECT id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE etape='.$this->db->quote($step).'
    AND split > 0
    AND info_job_actif=1
    AND tbljob_actif=1
    AND etape != 100
    ORDER BY job DESC, split ASC
    ';
    return $this->db->getAll($req);
  }

  public function noDateSubC(){
    $req='SELECT id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
    WHERE DyT_SubC IS NULL
    AND ST=1
    AND info_job_actif=1
    AND tbljob_actif=1
    AND etape != 100
    ORDER BY job DESC, split ASC
    ';
    return $this->db->getAll($req);
  }

  public function noDateCust(){
    $req='SELECT id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
    WHERE DyT_Cust IS NULL
    AND final=1 AND ST=0
    AND info_job_actif=1
    AND tbljob_actif=1
    AND etape != 100
    ORDER BY job DESC, split ASC
    ';
    return $this->db->getAll($req);
  }


  public function inOutError(){

    $req='SELECT job, max(id_tbljob) as id_tbljob
    from eprouvettes
    left join master_eprouvettes on master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    LEFT JOIN enregistrementessais on enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    WHERE (
      (master_eprouvettes.master_eprouvette_inOut_A is null and eprouvettes.eprouvette_inOut_A is not null)
      OR
      ((eprouvettes.eprouvette_inOut_A is null AND n_fichier is null) and eprouvettes.eprouvette_inOut_B is not null)
      OR
      (eprouvettes.eprouvette_inOut_A is not null and (select count(eps.id_eprouvette) from eprouvettes eps left join tbljobs tbl on tbl.id_tbljob=eps.id_job where eps.id_master_eprouvette=eprouvettes.id_master_eprouvette and tbl.phase<tbljobs.phase and eps.eprouvette_actif=1 and eps.eprouvette_inOut_A is null group by eps.id_master_eprouvette
    )>=1)  )
    AND etape!=100
    AND tbljobs.tbljob_actif=1
    GROUP BY job
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function WeeklyReport(){

    $req='SELECT count(DISTINCT job) AS nbJob, customer
    FROM info_jobs
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_info_job=info_jobs.id_info_job
    LEFT JOIN eprouvettes ON eprouvettes.id_master_eprouvette=master_eprouvettes.id_master_eprouvette
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE info_job_actif=1
    AND master_eprouvette_actif=1 AND eprouvette_actif=1 AND tbljob_actif=1
    AND etape<95
    GROUP BY customer
    ORDER BY customer ASC
    ';
    return $this->db->getAll($req);
  }

  public function WeeklyReportSubC(){

    $req='SELECT count(DISTINCT job) AS nbJob, contactsST.ref_customer
    FROM info_jobs
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    LEFT JOIN eprouvettes ON eprouvettes.id_job=tbljobs.id_tbljob
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    LEFT JOIN contacts  contactsST ON contactsST.id_contact=tbljobs.id_contactST
    WHERE info_job_actif=1
    AND master_eprouvette_actif=1 AND eprouvette_actif=1 AND tbljob_actif=1
    AND etape<90
    AND ref_customer IS NOT NULL
    GROUP BY ref_customer
    ORDER BY ref_customer ASC
    ';
    return $this->db->getAll($req);
  }

  public function awaitingQuotations(){
    $req='SELECT
    CASE
    WHEN id_preparer>0 THEN "A"
    ELSE "C"
    END AS state,
    id_quotation,
    concat("D", DATE_FORMAT(creation_date, "%y"), "-", LPAD(id_quotation, 5, 0)) as quotation_number
    FROM quotation
    LEFT JOIN info_jobs ON info_jobs.devis=concat("D",quotation.id_quotation) OR right(info_jobs.devis,4)=quotation.id_quotation
    WHERE (info_jobs.job IS NULL AND quotation_date IS NULL)
    AND quotation_actif=1
    ORDER BY id_quotation ASC
    ';
    return $this->db->getAll($req);
  }

}
