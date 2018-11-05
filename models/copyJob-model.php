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


  public function copyInfoJob() {

    $req = 'INSERT INTO info_jobs
    (customer, job, id_contact, id_contact2, id_contact3, id_contact4, ref_matiere, id_matiere_std, pricing, instruction, commentaire, info_job_actif)
    SELECT customer, (SELECT max(job)+1 FROM info_jobs WHERE info_job_actif=1), id_contact, id_contact2, id_contact3, id_contact4, ref_matiere, id_matiere_std, pricing, instruction, commentaire, 1
    FROM info_jobs
    WHERE id_info_job='.$this->id;

    //echo $req;

    $this->db->execute($req);
    $this->newIdInfoJob=$this->db->lastId();
    return $this->newIdInfoJob;
  }

  public function getMasterEprouvettes() {

    $req = 'SELECT *
    FROM master_eprouvettes
    WHERE id_info_job='.$this->id.'
      AND master_eprouvette_actif=1';
    echo '<br/>'.$req;

    return $this->db->getAll($req);
  }

  public function copyMasterEprouvette($oldId) {

    $req = 'INSERT INTO master_eprouvettes
    (id_info_job, prefixe, nom_eprouvette, id_dwg, master_eprouvette_actif)
    SELECT '.$this->newIdInfoJob.', NULL, "-", id_dwg, 1
    FROM master_eprouvettes
    WHERE id_master_eprouvette='.$oldId.'
      AND master_eprouvette_actif=1';

    echo '<br/>'.$req;

    $this->db->execute($req);
    return $this->db->lastId();
  }

  public function copyMasterEprouvetteID($oldId) {

    $req = 'INSERT INTO master_eprouvettes
    (id_info_job, prefixe, nom_eprouvette, id_dwg, master_eprouvette_actif)
    SELECT '.$this->newIdInfoJob.', prefixe, nom_eprouvette, id_dwg, 1
    FROM master_eprouvettes
    WHERE id_master_eprouvette='.$oldId.'
      AND master_eprouvette_actif=1';

    echo '<br/>'.$req;

    $this->db->execute($req);
    return $this->db->lastId();
  }

  public function getTbljobs() {

    $req = 'SELECT *
    FROM tbljobs
    WHERE id_info_job='.$this->id.'
      AND tbljob_actif=1';
    echo '<br/>'.$req;

    return $this->db->getAll($req);
  }

  public function copyTbljobs($old_id_tbljob) {

    $req = 'INSERT INTO tbljobs
    (id_info_job, phase, split, id_contactST, specification, id_type_essai, c_1, c_2, c_unite, tbljob_frequence, waveform, id_rawData, GE, comments, special_instruction, specific_protocol, staircase, other_1, other_2, other_3, other_4, other_5, tbljob_commentaire, tbljob_instruction, tbljob_actif)
    SELECT
    '.$this->newIdInfoJob.', phase, split, id_contactST, specification, id_type_essai, c_1, c_2, c_unite, tbljob_frequence, waveform, id_rawData, GE, comments, special_instruction, specific_protocol, staircase, other_1, other_2, other_3, other_4, other_5, tbljob_commentaire, tbljob_instruction, tbljob_actif
    FROM tbljobs
    WHERE id_tbljob='.$old_id_tbljob;

    echo '<br/>'.$req;

    $this->db->execute($req);
    $lastId=$this->db->lastId();

    $req2 = 'INSERT INTO tbljobs_temp
      (id_tbljobs_temp, id_statut_temp)
      VALUES
      ('.$lastId.', 1);';

    echo '<br/>'.$req2;
    $this->db->execute($req2);


    return $lastId;
  }

  public function getEprouvettes($id_tbljob) {

    $req = 'SELECT *
    FROM eprouvettes
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    WHERE id_job='.$id_tbljob.'
      AND eprouvette_actif=1
      AND master_eprouvette_actif=1';
    echo '<br/>'.$req;

    return $this->db->getAll($req);
  }

  public function copyEprouvettes($id_tbljob, $id_masterep, $id_eprouvette) {

    $req = 'INSERT INTO eprouvettes
    (id_master_eprouvette, id_job, eprouvette_actif)

    SELECT
    '.$id_masterep.', '.$id_tbljob.', eprouvette_actif

    FROM eprouvettes
    WHERE id_eprouvette='.$id_eprouvette.'
    ORDER BY id_eprouvette';

    echo '<br/>'.$req;

    $this->db->execute($req);
    //return $this->db->lastId();
  }

  public function copyEprouvettesConsigne($id_tbljob, $id_masterep, $id_eprouvette) {

    $req = 'INSERT INTO eprouvettes
    (id_master_eprouvette, id_job, c_temperature, c_type_1_val, c_type_2_val, c_type_3_val, c_type_4_val, c_type_5_val, c_cycle_STL, c_commentaire, c_frequence, c_frequence_STL, Cycle_min, runout, eprouvette_actif)

    SELECT
    '.$id_masterep.', '.$id_tbljob.', c_temperature, c_type_1_val, c_type_2_val, c_type_3_val, c_type_4_val, c_type_5_val, c_cycle_STL, c_commentaire, c_frequence, c_frequence_STL, Cycle_min, runout, eprouvette_actif

    FROM eprouvettes
    WHERE id_eprouvette='.$id_eprouvette.'
    ORDER BY id_eprouvette';

    echo '<br/>'.$req;

    $this->db->execute($req);
    //return $this->db->lastId();
  }






}
