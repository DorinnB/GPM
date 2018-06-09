<?php
class PosteModel
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

  public function getPoste() {

    $req = 'SELECT *
    FROM postes
    LEFT JOIN machines on machines.id_machine=postes.id_machine
    LEFT JOIN cell_load ON cell_load.id_cell_load=postes.id_cell_load
    LEFT JOIN cell_displacement ON cell_displacement.id_cell_displacement=postes.id_cell_displacement
    WHERE id_poste='.$this->id.'
    ORDER BY machines.machine ASC';
    //echo $req;
    return $this->db->getOne($req);
  }

  public function getAllMachine() {

    $req = 'SELECT max(id_poste) as id_poste, machines.id_machine, machines.machine
    FROM machines
    LEFT JOIN postes ON postes.id_machine=machines.id_machine
    WHERE machines.machine_actif=1
    GROUP BY id_machine
    ORDER BY machines.machine ASC';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllPoste() {

    $req = 'SELECT postes.id_poste, poste,
          GROUP_CONCAT(DISTINCT job SEPARATOR " ") as job,
          GROUP_CONCAT(DISTINCT dessin SEPARATOR " ") as dessin,
          GROUP_CONCAT(DISTINCT ref_matiere SEPARATOR " ") as matiere,

          cell_displacement_serial, cell_load_serial,
          cartouche_stroke, cartouche_load, cartouche_strain, enregistreur, extensometre, o1.outillage as outillage_top, o2.outillage as outillage_bot, chauffage, i1.ind_temp as ind_temp_top, i2.ind_temp as ind_temp_strap, i3.ind_temp as ind_temp_bot,  IF( compresseur = 1,  "&#10004;",  "" ) as compresseur, postes.date,
          Disp_P,	Disp_i,	Disp_D,	Disp_Conv,	Disp_Sens,	Load_P,	Load_i,	Load_D,	Load_Conv,	Load_Sens,	Strain_P,	Strain_i,	Strain_D,	Strain_Conv,	Strain_Sens,
          poste_commentaire, poste_reason, id_operateur
				FROM postes
				LEFT JOIN enregistreurs ON enregistreurs.id_enregistreur=postes.id_enregistreur
        LEFT JOIN cell_displacement ON cell_displacement.id_cell_displacement=postes.id_cell_displacement
        LEFT JOIN cell_load ON cell_load.id_cell_load=postes.id_cell_load
				LEFT JOIN extensometres ON extensometres.id_extensometre=postes.id_extensometre
				LEFT JOIN outillages o1 ON o1.id_outillage = postes.id_outillage_top
				LEFT JOIN outillages o2 ON o2.id_outillage = postes.id_outillage_bot
				LEFT JOIN chauffages ON chauffages.id_chauffage=postes.id_chauffage
				LEFT JOIN ind_temps i1 ON i1.id_ind_temp = postes.id_ind_temp_top
				LEFT JOIN ind_temps i2 ON i2.id_ind_temp = postes.id_ind_temp_strap
				LEFT JOIN ind_temps i3 ON i3.id_ind_temp = postes.id_ind_temp_bot
        LEFT JOIN prestart ON prestart.id_poste=postes.id_poste
				LEFT JOIN tbljobs ON tbljobs.id_tbljob=prestart.id_tbljob
				LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN eprouvettes ON eprouvettes.id_job=tbljobs.id_tbljob
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg

				WHERE id_machine=(select id_machine from postes where id_poste='.$this->id.')
        GROUP BY postes.id_poste
        ORDER BY postes.date DESC';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function newPoste(){
    $reqInsert='INSERT INTO postes
    (
      poste,
      id_cell_displacement,
      id_cell_load,
      id_extensometre,
      Disp_P,
      Disp_i,
      Disp_D,
      Disp_Conv,
      Disp_Sens,
      Load_P,
      Load_i,
      Load_D,
      Load_Conv,
      Load_Sens,
      Strain_P,
      Strain_i,
      Strain_D,
      Strain_Conv,
      Strain_Sens,
      id_outillage_top,
      id_outillage_bot,
      id_enregistreur,
      id_chauffage,
      id_ind_temp_top,
      id_ind_temp_strap,
      id_ind_temp_bot,
      compresseur,
      poste_commentaire,
      poste_reason,
      id_operateur,
      id_machine
    )

    VALUES (
      '.$this->poste.',
      '.$this->id_cell_displacement.',
      '.$this->id_cell_load.',
      '.$this->id_extensometre.',
      '.$this->Disp_P.',
      '.$this->Disp_i.',
      '.$this->Disp_D.',
      '.$this->Disp_Conv.',
      '.$this->Disp_Sens.',
      '.$this->Load_P.',
      '.$this->Load_i.',
      '.$this->Load_D.',
      '.$this->Load_Conv.',
      '.$this->Load_Sens.',
      '.$this->Strain_P.',
      '.$this->Strain_i.',
      '.$this->Strain_D.',
      '.$this->Strain_Conv.',
      '.$this->Strain_Sens.',
      '.$this->id_outillage_top.',
      '.$this->id_outillage_bot.',
      '.$this->id_enregistreur.',
      '.$this->id_chauffage.',
      '.$this->id_ind_temp_top.',
      '.$this->id_ind_temp_strap.',
      '.$this->id_ind_temp_bot.',
      '.$this->compresseur.',
      '.$this->poste_commentaire.',
      '.$this->poste_reason.',
      '.$this->id_operateur.',
      '.$this->id_machine.'
    );';

      //echo $reqInsert;
      $this->db->execute($reqInsert);
    }

  }
