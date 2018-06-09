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
      SET '.$type.' = '.$date.',
      d_checked = '.(($date!="NULL")?'1':'0').'
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
    FROM master_eprouvettes
    LEFT JOIN info_jobs ON info_jobs.id_info_job=master_eprouvettes.id_info_job
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE master_eprouvette_inOut_A IS NULL
    AND master_eprouvette_actif = 1
    AND info_job_actif=1
    AND tbljob_actif=1
    AND etape!=100
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
    AND etape!=100
    GROUP BY id_tbljob
    ORDER BY id_tbljob DESC';

    return $this->db->getAll($req);
  }

  public function overdueOut(){
    $req='SELECT job, max(id_tbljob) as id_tbljob
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    WHERE (
      SELECT sum(if(eprouvettes.eprouvette_inOut_B IS NOT NULL,0,1))
      FROM master_eprouvettes
      LEFT JOIN eprouvettes ON eprouvettes.id_master_eprouvette=master_eprouvettes.id_master_eprouvette
      WHERE master_eprouvettes.id_info_job=info_jobs.id_info_job
      AND master_eprouvettes.master_eprouvette_inOut_B IS NULL
      AND master_eprouvettes.master_eprouvette_actif=1
      AND eprouvettes.eprouvette_actif=1
      GROUP BY master_eprouvettes.id_info_job
    ) = 0
    AND invoice_type=2
    GROUP BY job
    ORDER BY job DESC';

    return $this->db->getAll($req);
  }

  public function FQC(){
    $req='SELECT id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE (etape = 70 OR etape=71)
    AND info_job_actif=1
    AND tbljob_actif=1
    ORDER BY job DESC, split ASC
    ';
    return $this->db->getAll($req);
  }

  public function reportReady(){
    $req='SELECT id_tbljob, job, split
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE etape = 80
    AND info_job_actif=1
    AND tbljob_actif=1
    ORDER BY job DESC, split ASC
    ';
    return $this->db->getAll($req);
  }
  public function notInvoiced(){
    $req='SELECT max(id_tbljob) as id_tbljob, job
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp

    WHERE etape = 90
    AND info_job_actif=1
    AND tbljob_actif=1
    AND invoice_type!=2

    GROUP BY job
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function invoicedNotCompleted(){
    $req='SELECT max(id_tbljob) as id_tbljob, job
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp

    WHERE etape = 90
    AND info_job_actif=1
    AND tbljob_actif=1
    AND invoice_type=2

    GROUP BY job
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

  public function inOutError(){

    /*SELECT job, (id_tbljob) as id_tbljob, eprouvettes.id_eprouvette
    from eprouvettes
    left join master_eprouvettes on master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    WHERE (

     (master_eprouvettes.master_eprouvette_inOut_A is null and eprouvettes.eprouvette_inOut_A is not null)
    OR
        (eprouvettes.eprouvette_inOut_A is null and eprouvettes.eprouvette_inOut_B is not null)
    OR

    )
    AND job=0






    SELECT job, (id_tbljob) as id_tbljob, eprouvettes.id_eprouvette, master_eprouvettes.nom_eprouvette, master_eprouvettes.prefixe
        from eprouvettes
        left join master_eprouvettes on master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        WHERE (

         (master_eprouvettes.master_eprouvette_inOut_A is null and eprouvettes.eprouvette_inOut_A is not null)
        OR
            (eprouvettes.eprouvette_inOut_A is null and eprouvettes.eprouvette_inOut_B is not null)
        OR
        (eprouvettes.eprouvette_inOut_A is not null and (select count(eps.id_eprouvette) from eprouvettes eps left join tbljobs tbl on tbl.id_tbljob=eps.id_job where eps.id_master_eprouvette=eprouvettes.id_master_eprouvette and tbl.phase<tbljobs.phase and eps.eprouvette_actif=1 and eps.eprouvette_inOut_A is null group by eps.id_master_eprouvette
                )>=1)
        )
        AND job=0
    */
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
      )>=1)
    )
    AND etape!=100
    AND tbljobs.tbljob_actif=1
    GROUP BY job
    ORDER BY job DESC
    ';
    return $this->db->getAll($req);
  }

}
