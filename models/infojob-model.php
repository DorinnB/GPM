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
          instruction, commentaire, info_job_actif,
          available_expected

				FROM info_jobs
				LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
        LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact
        LEFT JOIN contacts  contacts2 ON contacts2.id_contact=info_jobs.id_contact2
        LEFT JOIN contacts  contacts3 ON contacts3.id_contact=info_jobs.id_contact3
        LEFT JOIN contacts  contacts4 ON contacts4.id_contact=info_jobs.id_contact4
        LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std


				WHERE tbljobs.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->id.')';
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
        ref_matiere='.$this->ref_matiere.',
        id_matiere_std='.$this->id_matiere_std.',
        pricing='.$this->pricing.',
        `po_number` = '.$this->po_number.',
        `devis` = '.$this->devis.',
        `instruction` = '.$this->instruction.',
        `commentaire` = '.$this->commentaire.',
        `available_expected` = '.$this->available_expected.',
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


}
