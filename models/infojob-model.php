<?php
class InfoJob
{

  protected $db;
  private $id;


  public function __construct($db,$id)
  {
    $this->db = $db;
    $this->id = $id;
  }


  public function __set($property,$value) {
    if (is_numeric($value)){
      $this->$property = $value;
    }
    else {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
    }
  }




  public function getInfoJob() {

    $req = 'SELECT info_jobs.id_info_job,
    customer, job,
    contacts.id_contact, contacts.prenom, contacts.nom,
    contacts2.id_contact as id_contact2, contacts2.prenom as prenom2, contacts2.nom as nom2,
    contacts3.id_contact as id_contact3, contacts3.prenom as prenom3, contacts3.nom as nom3,
    contacts4.id_contact as id_contact4, contacts4.prenom as prenom4, contacts4.nom as nom4,
    id_contact2, id_contact3, id_contact4,
    ref_matiere, id_matiere_std, matiere,
    po_number, devis, info_jobs.pricing,
    activity_type, specific_test,
    instruction, commentaire, info_job_actif,
    available_expected,
    info_job_date, info_job_rev, info_job_report_Q, info_job_report_TM, info_job_send,
    DATE_FORMAT(datecreation, "%Y-%m-%d") as datecreation,
    order_val, order_val_subc, order_est, order_est_subc

    FROM info_jobs
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
    LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact
    LEFT JOIN contacts  contacts2 ON contacts2.id_contact=info_jobs.id_contact2
    LEFT JOIN contacts  contacts3 ON contacts3.id_contact=info_jobs.id_contact3
    LEFT JOIN contacts  contacts4 ON contacts4.id_contact=info_jobs.id_contact4
    LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std


    WHERE tbljobs.id_tbljob = '.$this->id;
    //echo $req;
    return $this->db->getOne($req);
  }



  public function updateInfoJob(){
    $this->id_info_job = $this->getInfoJob()['id_info_job'];

    $reqUpdate='UPDATE `info_jobs` SET
    `customer` = '.$this->customer.',
    `job` = '.$this->job.',
    `id_contact` = '.$this->id_contact.',
    `id_contact2` = '.$this->id_contact2.',
    `id_contact3` = '.$this->id_contact3.',
    `id_contact4` = '.$this->id_contact4.',
    `ref_matiere` = '.$this->ref_matiere.',
    `id_matiere_std`= '.$this->id_matiere_std.',
    `pricing` = '.$this->pricing.',
    `po_number` = '.$this->po_number.',
    `devis` = '.$this->devis.',
    `activity_type` = '.$this->activity_type.',
    `specific_test` = '.$this->specific_test.',
    `instruction` = '.$this->instruction.',
    `commentaire` = '.$this->commentaire.',
    `datecreation` = '.$this->datecreation.',
    `order_val` = '.$this->order_val.',
    `order_val_subc` = '.$this->order_val_subc.',
    `order_est` = '.$this->order_est.',
    `order_est_subc` = '.$this->order_est_subc.',
    `info_job_actif` = '.$this->info_job_actif.'
    WHERE id_info_job = '.$this->getInfoJob()['id_info_job'].';';
    //echo $reqUpdate;

    $result = $this->db->query($reqUpdate);

    //    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'info_jobs' => $this->getInfoJob()['id_info_job']);
    //    			echo json_encode($maReponse);
  }

  public function previousNextJob($sens){

    $req='SELECT id_tbljob
    FROM tbljobs
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    WHERE job'.$sens.'(SELECT job FROM tbljobs LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job WHERE id_tbljob='.$this->id.')
    AND tbljob_actif=1
    ORDER BY job '.(($sens==">")?"ASC":"DESC").'
    LIMIT 1';

    //echo $req;
    $result = $this->db->isOne($req);

    $maReponse =  $result;
    echo json_encode($maReponse);
  }

  public function updateRev(){
    //on inverse le signe de l'opérateur (sauf si 0 on fait positif)
    $reqUpdate='
    UPDATE `info_jobs`
    SET info_job_rev = if(info_job_rev is null,0,info_job_rev + 1)
    WHERE id_info_job = '.$this->getInfoJob()['id_info_job'].';';
    //echo $reqUpdate;
    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
    echo json_encode($maReponse);
  }

  public function resetRev(){
    //on inverse le signe de l'opérateur (sauf si 0 on fait positif)
    $reqUpdate='
    UPDATE `info_jobs`
    SET info_job_rev = NULL
    WHERE id_info_job = '.$this->getInfoJob()['id_info_job'].';';
    //echo $reqUpdate;
    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
    echo json_encode($maReponse);
  }

  public function updateReportDate($reportDate){
    $reqUpdate='UPDATE `info_jobs` SET
    info_job_send = '.$_COOKIE['id_user'].',
    info_job_date = '.$this->db->quote($reportDate).'
    WHERE id_info_job = '.$this->getInfoJob()['id_info_job'].';';
    //echo $reqUpdate;
    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
    echo json_encode($maReponse);
  }

  public function resetReportDate(){
    $reqUpdate='UPDATE `info_jobs` SET
    info_job_send = '.-$_COOKIE['id_user'].',
    info_job_date = NULL
    WHERE id_info_job = '.$this->getInfoJob()['id_info_job'].';';
    //echo $reqUpdate;
    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
    echo json_encode($maReponse);
  }

  public function updateCheckQ(){
    //on inverse le signe de l'opérateur (sauf si 0 on fait positif)
    $reqUpdate='UPDATE `info_jobs`
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    SET `info_job_report_Q` = if(info_job_report_Q=0,'.$_COOKIE['id_user'].',sign(info_job_report_Q)*-'.$_COOKIE['id_user'].'),
    `report_Q` = info_job_report_Q,
    info_job_rev = if(info_job_rev is null,0,info_job_rev)
    WHERE tbljobs.id_info_job = '.$this->getInfoJob()['id_info_job'].';';
    //echo $reqUpdate;
    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
    echo json_encode($maReponse);
  }

  public function updateCheckTM(){
    //on inverse le signe de l'opérateur (sauf si 0 on fait positif)

    $reqUpdate='UPDATE `info_jobs`
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    SET `info_job_report_TM` = if(info_job_report_TM=0,'.$_COOKIE['id_user'].',sign(info_job_report_TM)*-'.$_COOKIE['id_user'].'),
    `report_TM` = info_job_report_TM
    WHERE tbljobs.id_info_job = '.$this->getInfoJob()['id_info_job'].';';
    //echo $reqUpdate;
    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
    echo json_encode($maReponse);
  }

  public function getCountInfoJob() {

    $req = 'SELECT COUNT(DISTINCT master_eprouvettes.id_master_eprouvette) as expected,
    COUNT(DISTINCT CASE WHEN master_eprouvettes.master_eprouvette_inOut_B IS NOT NULL THEN master_eprouvettes.id_master_eprouvette END) AS shipped

    FROM info_jobs
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_info_job=info_jobs.id_info_job
    LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job


    WHERE tbljobs.id_tbljob = '.$this->id;
    //echo $req;
    return $this->db->getOne($req);
  }
}
