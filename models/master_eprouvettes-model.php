<?php
class LstMasterEprouvettesModel
{

  protected $db;
  private $id;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function __set($property,$value) {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
  }

  public function addMasterEp() {

    $reqInsert='INSERT INTO master_eprouvettes
    (id_info_job, prefixe, nom_eprouvette, id_dwg, master_eprouvette_actif)
    VALUES ('.$this->id_info_job.','.$this->prefixe.','.$this->nom_eprouvette.','.$this->id_dwg.',1);';
    echo '<br/>'.$reqInsert;
    $this->db->execute($reqInsert);
    return   $this->db->lastId();
  }

  public function updateMasterEp($idMasterEp) {

    $reqUpdate='UPDATE master_eprouvettes SET prefixe='.$this->prefixe.', nom_eprouvette='.$this->nom_eprouvette.', id_dwg='.$this->id_dwg.'
    WHERE id_master_eprouvette='.$idMasterEp;
    echo '<br/>'.$reqUpdate;
    $this->db->query($reqUpdate);
  }


  public function delMasterEp($id) {

    $reqDelete='UPDATE master_eprouvettes SET master_eprouvette_actif=0 WHERE id_master_eprouvette='.$id;
    echo '<br/>'.$reqDelete;
    $this->db->execute($reqDelete);

  }
}

//Liste des commande pour alimenter master_eprouvettes

//INSERT IGNORE INTO master_eprouvettes (master_eprouvettes.id_master_eprouvette, master_eprouvettes.id_info_job, master_eprouvettes.id_eprouvette,master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, master_eprouvettes.id_dwg) SELECT eprouvettes.id_eprouvette, tbljobs.id_info_job, eprouvettes.id_eprouvette, eprouvettes.prefixe, eprouvettes.nom_eprouvette, tbljobs.id_dessin FROM eprouvettes left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job where eprouvettes.eprouvette_actif=1 AND eprouvettes.heritage is null
//update eprouvettes set id_master_eprouvette=id_eprouvette where heritage is null
//update eprouvettes set id_master_eprouvette=heritage where heritage is not null
