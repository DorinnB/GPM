<?php
class LstEprouvettesModel
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

    public function getAllEprouvettes() {

		$req = 'SELECT eprouvettes.id_eprouvette,
          master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_essai, round(c_temperature,0) as c_temp, c_frequence, c_cycle_STL, c_frequence_STL,
          c_type_1_val, c_type_2_val, c_type_3_val, c_type_4_val, c_type_5_val, c1.consigne_type as c_1_type, c2.consigne_type as c_2_type, c_unite,
           flag_qualite,
           Cycle_min, runout, cycle_estime, c_commentaire, c_checked, d_checked, dim_1, dim_2, dim_3, dessins.type, id_dessin_type,
           d_commentaire, young,
           n_fichier, machine, enregistrementessais.date, eprouvettes.waveform, Cycle_STL, Cycle_final, Rupture, Fracture

				FROM eprouvettes
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN consigne_types c1 ON c1.id_consigne_type=tbljobs.c_1
        LEFT JOIN consigne_types c2 ON c2.id_consigne_type=tbljobs.c_2
        LEFT JOIN prestart ON enregistrementessais.id_prestart=prestart.id_prestart
        LEFT JOIN postes ON prestart.id_poste=postes.id_poste
        LEFT JOIN machines ON postes.id_machine=machines.id_machine
        LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg
        LEFT JOIN info_jobs ON info_jobs.id_info_job=master_eprouvettes.id_info_job
        LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std

				WHERE tbljobs.id_tbljob='.$this->id.'
          AND eprouvette_actif = 1
          AND master_eprouvette_actif = 1
        ORDER by master_eprouvettes.id_master_eprouvette ASC';
                      //  echo $req;
        return $this->db->getAll($req);
    }

    public function delEp() {
      echo ' delEp() ';
      //Check si l'eprouvette existe dans ce split
      $reqDel='SELECT id_eprouvette FROM eprouvettes where id_job='.$this->id.' AND id_master_eprouvette='.$this->id_master.' AND eprouvette_actif=1';
      echo $reqDel;
      $result=$this->db->isOne($reqDel);
      //var_dump($result);
      //si existant, on supprime l'eprouvette
      if ($result) {
        $reqDel='UPDATE eprouvettes SET eprouvette_actif=0 WHERE id_eprouvette='.$result['id_eprouvette'];
        echo $reqDel;
        $this->db->execute($reqDel);
        echo ' ep detruite .';
      }
    }

    public function addEp() {
      echo ' addEp() ';
      $reqDel='SELECT id_eprouvette FROM eprouvettes where id_job='.$this->id.' AND id_master_eprouvette='.$this->id_master.' AND eprouvette_actif=1';
      echo $reqDel;
      $result=$this->db->isOne($reqDel);
      //var_dump($result);
      //si existant, on supprime l'eprouvette
      if (!$result) {
        $reqInsert='INSERT INTO eprouvettes
        (id_job, id_master_eprouvette, eprouvette_actif)
        VALUES ('.$this->id.','.$this->id_master.',1);';
        echo $reqInsert;
        $this->db->execute($reqInsert);
      }
    }

}

//Liste des commande pour alimenter master_eprouvettes

//INSERT IGNORE INTO master_eprouvettes (master_eprouvettes.id_master_eprouvette, master_eprouvettes.id_info_job, master_eprouvettes.id_eprouvette,master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, master_eprouvettes.id_dwg) SELECT eprouvettes.id_eprouvette, tbljobs.id_info_job, eprouvettes.id_eprouvette, eprouvettes.prefixe, eprouvettes.nom_eprouvette, tbljobs.id_dessin FROM eprouvettes left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job where eprouvettes.eprouvette_actif=1 AND eprouvettes.heritage is null
//update eprouvettes set id_master_eprouvette=id_eprouvette where heritage is null
//update eprouvettes set id_master_eprouvette=heritage where heritage is not null
