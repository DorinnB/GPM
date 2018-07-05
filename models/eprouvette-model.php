<?php
class EprouvetteModel
{

  protected $db;
  private $id;
  private $_area;
  private $_dimDenomination;
  private $_R;
  private $_A;
  private $_MAX;
  private $_MIN;
  private $_MEAN;
  private $_ALT;
  private $_comm;
  private $_ST;
  private $_local;


  public function __construct($db,$id)
  {
    $this->db = $db;
    $this->id = $id;
  }

  public function area()  {
    return $this->_area;
  }
  public function dimDenomination()  {
    return $this->_dimDenomination;
  }
  public function R()  {
    return $this->_R;
  }
  public function A()  {
    return $this->_A;
  }
  public function MAX()  {
    return $this->_MAX;
  }
  public function MIN()  {
    return $this->_MIN;
  }
  public function MEAN()  {
    return $this->_MEAN;
  }
  public function ALT()  {
    return $this->_ALT;
  }



  public function __set($property,$value) {
    if (is_numeric($value)){
      $this->$property = $value;
    }
    else {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
    }
  }

  public function getEprouvette() {

    $req = 'SELECT eprouvettes.id_eprouvette,
    master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_essai, round(c_temperature,0) AS c_temp, c_temperature, c_frequence, c_cycle_STL, c_frequence_STL,
    c_type_1_val, c_type_2_val, c_type_3_val, c_type_4_val, c_type_5_val,
    Cycle_min, runout, cycle_estime, c_commentaire, c_checked, d_checked, dim_1, dim_2, dim_3,
    d_commentaire, check_rupture, flag_qualite, checked,
    n_essai, n_fichier, machine, enregistrementessais.date, eprouvettes.waveform, Rupture, Fracture,
    eprouvette_InOut_A, eprouvette_InOut_B,
    val_1,val_2,val_3,val_4,val_5,val_6,val_7,val_8,val_9,val_10,
    other_1, other_2, other_3, other_4, other_5,
    comments,

    IF(Cycle_STL is null,Cycle_STL_temp, Cycle_STL) as Cycle_STL,
    IF(Cycle_final is null,Cycle_final_temp, cycle_final) as Cycle_final,
    IF(currentBlock is null,currentBlock_temp, currentBlock) as currentBlock,

    tbljobs.waveform AS c_waveform,enregistrementessais.id_controleur, enregistrementessais.id_operateur,
    techniciens.technicien, info_jobs.job, info_jobs.customer, split, test_type, test_type_abbr, eprouvettes.id_master_eprouvette, id_job, prestart.id_prestart,
    (select count(*) from eprouvettes eps where eps.id_eprouvette<='.$this->id.' and eps.id_master_eprouvette=eprouvettes.id_master_eprouvette and eps.id_job=eprouvettes.id_job and eps.eprouvette_actif=1) AS retest

    FROM eprouvettes
    LEFT JOIN eprouvettes_temp ON eprouvettes_temp.id_eprouvettes_temp=eprouvettes.id_eprouvette
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
    LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
    LEFT JOIN prestart ON enregistrementessais.id_prestart=prestart.id_prestart
    LEFT JOIN postes ON prestart.id_poste=postes.id_poste
    LEFT JOIN machines ON postes.id_machine=machines.id_machine
    LEFT JOIN techniciens ON techniciens.id_technicien=enregistrementessais.id_controleur
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai

    WHERE eprouvettes.id_eprouvette='.$this->id;
    //echo $req;
    return $this->db->getOne($req);
  }



  public function newTest() {

    $reqSelect='SELECT * FROM enregistrementessais WHERE id_eprouvette = '.$this->id;
    //echo $reqSelect;
    if (!$this->db->isOne($reqSelect)) {  //l'eprouvette n'est pas deja enregistré

      //On insert l'enregistrementessais
      $reqInsert='INSERT INTO enregistrementessais (id_acquisition ,id_eprouvette ,id_prestart ,date ,id_operateur ,id_controleur)
      VALUES (7, '.$this->id.', '.$this->id_prestart.', "'.date('Y-m-d H:i:s').'", '.$this->id_operateur.', '.$this->id_controleur.')';
      //echo $reqInsert;
      $this->db->execute($reqInsert);


      //on cherche le numero d'essai (plus petit nombre vide)
      $reqNEssai='SELECT IFNULL(min(t1.n_essai),0)+1 AS nextNEssai
      FROM eprouvettes t1
      LEFT JOIN eprouvettes t2
      ON t1.n_essai + 1 = t2.n_essai
      AND t2.id_job=t1.id_job
      AND t2.eprouvette_actif=1
      WHERE  t2.n_essai IS NULL
      AND t1.eprouvette_actif=1
      AND t1.id_job=(SELECT id_job FROM eprouvettes WHERE id_eprouvette='.$this->id.')';

      $n_essai = $this->db->getOne($reqNEssai);



      //On update l'eprouvette avec ce n_essai et la frequence
      $reqUpdate='UPDATE eprouvettes
      SET n_essai = '.$n_essai['nextNEssai'].',
      c_frequence = '.$this->custom_frequency.'
      WHERE id_eprouvette = '.$this->id;

      //echo $reqUpdate;
      $this->db->execute($reqUpdate);

    }
    else {
      echo 'Probleme lors de l\'enregistrement.<br/> Votre éprouvette est déjà enregistrée !';
    }
  }

  public function newTestCheck() {
    //On update l'eprouvette avec ce checkeur
    $reqUpdate='UPDATE enregistrementessais
    SET id_controleur = '.$this->id_controleur.'
    WHERE id_eprouvette = '.$this->id;

    //echo $reqUpdate;
    $this->db->execute($reqUpdate);
  }


  public function copyEp() {

    $eprouvette=$this->getEprouvette();
    //on extrait les valeurs dans une variables, puis ON le ajoute au attribut de l'objet. Cela permet de remettre les null ou les guillemet pour le text.
    $this->id_master=$eprouvette['id_master_eprouvette'];
    $this->id_job=$eprouvette['id_job'];
    $this->c_temperature=$eprouvette['c_temperature'];
    $this->c_frequence=$eprouvette['c_frequence'];
    $this->c_cycle_STL=$eprouvette['c_cycle_STL'];
    $this->c_frequence_STL=$eprouvette['c_frequence_STL'];
    $this->dim_1=$eprouvette['dim_1'];
    $this->dim_2=$eprouvette['dim_2'];
    $this->dim_3=$eprouvette['dim_3'];
    $this->runout=$eprouvette['runout'];

    $reqInsert='INSERT INTO eprouvettes
    (
      id_master_eprouvette,
      id_job,
      c_temperature,
      c_frequence,
      c_cycle_STL,
      c_frequence_STL,
      dim_1,
      dim_2,
      dim_3,
      runout,
      eprouvette_actif
    )

    VALUES (
      '.$this->id_master.',
      '.$this->id_job.',
      '.$this->c_temperature.',
      '.$this->c_frequence.',
      '.$this->c_cycle_STL.',
      '.$this->c_frequence_STL.',
      '.$this->dim_1.',
      '.$this->dim_2.',
      '.$this->dim_3.',
      '.$this->runout.', 1);';

      echo $reqInsert;
      $this->db->execute($reqInsert);
    }

    public function delEp() {

      $reqUpdate='UPDATE eprouvettes
      SET eprouvette_actif = 0
      WHERE id_eprouvette = '.$this->id;

      //echo $reqUpdate;
      $this->db->execute($reqUpdate);
    }

    public function cancelTest() {

      $reqUpdate='UPDATE eprouvettes
      SET n_essai  = NULL
      WHERE id_eprouvette = '.$this->id;

      //echo $reqUpdate;
      $this->db->execute($reqUpdate);

      $reqUpdateEnregistrementEssai='UPDATE enregistrementessais
      SET id_eprouvette  = NULL
      WHERE id_eprouvette = '.$this->id;

      //echo $reqUpdateEnregistrementEssai;
      $this->db->execute($reqUpdateEnregistrementEssai);

    }

    public function updateTest() {

      $reqUpdateEnregistrementEssai='UPDATE enregistrementessais
      SET id_prestart  = (SELECT max(id_prestart) FROM prestart LEFT JOIN postes ON postes.id_poste=prestart.id_poste where prestart.id_tbljob=(SELECT id_job FROM eprouvettes where id_eprouvette='.$this->id.'))
      WHERE id_eprouvette = '.$this->id;

      //echo $reqUpdateEnregistrementEssai;
      $this->db->execute($reqUpdateEnregistrementEssai);

    }

    public function updateCheck(){
      $reqUpdate='UPDATE `eprouvettes` SET
      `c_checked` = '.$_COOKIE['id_user'].'
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';
      //echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $_COOKIE['id_user']);
      echo json_encode($maReponse);
    }

    public function updateRemoveCheck(){
      $reqUpdate='UPDATE `eprouvettes` SET
      `c_checked` = -'.$_COOKIE['id_user'].'
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';
      //echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => 0);
      echo json_encode($maReponse);
    }

    public function updateDCheck($iduser){
      $reqUpdate='UPDATE `eprouvettes` SET
      `d_checked` = '.$iduser.',
      eprouvette_InOut_B=if(eprouvette_InOut_B IS NULL, NOW(), eprouvette_InOut_B)
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';
      //echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $iduser);
      echo json_encode($maReponse);
    }

    public function updateRemoveDCheck($iduser){
      $reqUpdate='UPDATE `eprouvettes` SET
      `d_checked` = -'.$_COOKIE['id_user'].',
      eprouvette_InOut_B=NULL
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';
      //echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => 0);
      echo json_encode($maReponse);
    }

    public function updateCheckRupture($iduser){
      $reqUpdate='UPDATE `eprouvettes` SET
      `check_rupture` = IF(check_rupture<=0,'.$iduser.',-'.$iduser.')
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';
      //echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $iduser);
      echo json_encode($maReponse);
    }

  public function updateAux($data){
        $reqUpdate='UPDATE `eprouvettes` SET
         	check_rupture = -'.$this->iduser.',
          `d_checked` = -'.$this->iduser;

        foreach ($data as $key => $value) {
          $reqUpdate.=', '.$key.'='.(($value=="")? "NULL" : $this->db->quote($value));
        }

        $reqUpdate.='
          WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';

        $result = $this->db->query($reqUpdate);

        $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $_COOKIE['id_user']);
        echo json_encode($maReponse);
      }

      public function updateAuxValid(){
            $reqUpdate='UPDATE `eprouvettes` SET
             	check_rupture = '.$this->iduser.',
              `d_checked` = '.$this->iduser.'
              WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';

            $result = $this->db->query($reqUpdate);

            $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $_COOKIE['id_user']);
            echo json_encode($maReponse);
          }

    public function previousNextTest($sens){
      $reqSelect='SELECT eprouvettes.id_eprouvette, n_essai, id_job
      FROM `eprouvettes`
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      WHERE eprouvettes.id_eprouvette='.$this->id;

      $infoPrevious=$this->db->getOne($reqSelect);
      $req='SELECT eprouvettes.id_eprouvette
      FROM `eprouvettes`
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      WHERE id_job='.$infoPrevious['id_job'].'
      AND n_essai'.$sens.$infoPrevious['n_essai'].'
      ORDER BY n_essai '.(($sens=="<")?"DESC":"ASC").'
      LIMIT 1;';

      //echo $req2;
      $result = $this->db->isOne($req);

      $maReponse =  $result;
      echo json_encode($maReponse);
    }

    public function previousNextMachine($sens){
      $reqSelect='SELECT eprouvettes.id_eprouvette, n_essai, id_machine, id_job
      FROM `eprouvettes`
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
      LEFT JOIN postes ON postes.id_poste=prestart.id_poste
      WHERE eprouvettes.id_eprouvette='.$this->id;

      $infoPrevious=$this->db->getOne($reqSelect);
      $req='SELECT eprouvettes.id_eprouvette
      FROM `eprouvettes`
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
      LEFT JOIN postes ON postes.id_poste=prestart.id_poste
      WHERE id_job='.$infoPrevious['id_job'].'
      AND n_essai'.$sens.$infoPrevious['n_essai'].'
      AND id_machine='.$infoPrevious['id_machine'].'
      ORDER BY n_essai '.(($sens=="<")?"DESC":"ASC").'
      LIMIT 1;';

      //echo $req2;
      $result = $this->db->isOne($req);

      $maReponse =  $result;
      echo json_encode($maReponse);
    }

    public function previousNextSpecimen($sens){

      $req='SELECT eprouvettes.id_eprouvette
      FROM `eprouvettes`
      WHERE id_job=(SELECT id_job FROM eprouvettes WHERE id_eprouvette='.$this->id.')
      AND eprouvette_actif=1
      AND id_eprouvette'.$sens.$this->id.'
      ORDER BY id_eprouvette '.(($sens=="<")?"DESC":"ASC").'
      LIMIT 1;';

      //echo $req2;
      $result = $this->db->isOne($req);

      $maReponse =  $result;
      echo json_encode($maReponse);
    }

    public function addFlagQualite(){
      $reqUpdate='UPDATE `eprouvettes` SET
      `flag_qualite` = '.$_COOKIE['id_user'].'
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';
      //echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $_COOKIE['id_user']);
      echo json_encode($maReponse);
    }

    public function removeFlagQualite(){
      $reqUpdate='UPDATE `eprouvettes` SET
      `flag_qualite` = 0
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';
      //echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => 0);
      echo json_encode($maReponse);
    }


    public function update_Rupture()  {

      $reqUpdate='UPDATE `eprouvettes` SET
      `Rupture` = '.$this->Rupture.',
      check_rupture = -'.$this->iduser.'
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';

      $result = $this->db->execute($reqUpdate);

      $maReponse_Rupture = array('result' => 'Rupture', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $this->iduser);

      //echo json_encode($maReponse);
    }

    public function update_Fracture()  {

      $reqUpdate='UPDATE `eprouvettes` SET
      `Fracture` = '.$this->Fracture.',
      check_rupture = -'.$this->iduser.'
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';

      $result = $this->db->execute($reqUpdate);

      $maReponse_Fracture = array('result' => 'Fracture', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $this->iduser);

      //echo json_encode($maReponse);
    }

    public function checkTechSplit($id_tech){
      $req='SELECT  id_tbljob, tbljob_commentaire
        FROM    tbljobs
        LEFT JOIN tech_split e ON e.id_split=tbljobs.id_tbljob
        WHERE   NOT EXISTS
          (
          SELECT  null
          FROM    tech_split d
          WHERE   d.id_technicien = '.$this->db->quote($id_tech).'
          AND d.id_split=(SELECT id_job fROM eprouvettes WHERE id_eprouvette='.$this->id.')
          )
        AND id_tbljob=(SELECT id_job fROM eprouvettes WHERE id_eprouvette='.$this->id.');';
        //echo $req;
      return $this->db->isOne($req);

    }

    public function createTechSplit($id_tech){
      $reqCreate='INSERT INTO `tech_split`
      (id_technicien, `id_split`)
      SELECT  '.$this->db->quote($id_tech).', id_job FROM eprouvettes WHERE id_eprouvette='.$this->id.';';
//echo $reqCreate;
      $result = $this->db->execute($reqCreate);
    }




    public function update_d_commentaire()  {

      $reqUpdate='UPDATE `eprouvettes` SET
      `d_commentaire` = '.$this->d_commentaire.'
      WHERE `eprouvettes`.`id_eprouvette` = '.$this->id.';';
      //echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_eprouvette' => $this->id, 'id_user' => $this->iduser);
      echo json_encode($maReponse);
    }


    public function getTest() {

      $req = 'SELECT eprouvettes.id_eprouvette,
      master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_essai, round(c_temperature,0) AS c_temp, c_temperature, c_frequence, c_cycle_STL, c_frequence_STL,
      c_type_1_val, c_type_2_val, c_type_3_val, c_type_4_val, c_type_5_val, c1.consigne_type AS c_1_type, c2.consigne_type AS c_2_type, Lo, c_unite,
      stepcase_type, c3.consigne_type as steptype, stepcase_val,
      Cycle_min, runout, cycle_estime, c_commentaire, q_commentaire, c_checked, d_checked, type,id_dessin_type, dessin, ref_matiere, enregistreur, extensometre,
      cartouche_load, cartouche_stroke, cartouche_strain, t1.technicien AS operateur, t2.technicien AS controleur,
      n_essai, n_fichier, machine, enregistrementessais.date,
      tbljobs.waveform AS c_waveform, eprouvettes.waveform, signal_true, signal_tapered,
      IF(Cycle_STL is null,Cycle_STL_temp, Cycle_STL) as Cycle_STL, Cycle_final as truecyclefinal,
      IF(Cycle_final is null,Cycle_final_temp, cycle_final) as Cycle_final, IF(Cycle_final is null,0, 1) as Cycle_final_valid,
      Rupture, Fracture, other_1, other_2, other_3, other_4, other_5,
      info_jobs.job, info_jobs.customer, split, test_type.id_test_type, test_type, test_type_abbr, eprouvettes.id_master_eprouvette, id_job,
      signal_true, signal_tapered, young, flag_qualite, check_rupture,
      d_commentaire, special_instruction, tbljob_instruction,

      IF(currentBlock is null,currentBlock_temp, currentBlock) as currentBlock,
      E_RT, c1_E_montant, c1_max_strain, c1_min_strain, c1_max_stress, c1_min_stress, c2_cycle, c2_E_montant, c2_max_stress, c2_min_stress, c2_max_strain, c2_min_strain, c2_calc_inelastic_strain, c2_meas_inelastic_strain, Ni, Nf75, dilatation,
      c2_delta_strain, c2_strain_e,
      dim1, dim2, dim3, gage,
      type_chauffage, chauffage,
      cell_load_gamme, cell_displacement_gamme, cell_displacement_serial, cell_load_serial,
      Disp_P, Disp_i, Disp_D, Disp_Conv, Disp_Sens, Load_P, Load_i, Load_D, Load_Conv, Load_Sens, Strain_P, Strain_i, Strain_D, Strain_Conv, Strain_Sens,
      outillage_tops.outillage as outillage_top, outillage_bots.outillage as outillage_bot,

      ind_temps_top.ind_temp as ind_temp_top,
      ind_temps_strap.ind_temp as ind_temp_strap,
      ind_temps_bot.ind_temp as ind_temp_bot,
      name, GE,
      val_1,val_2,val_3,val_4,val_5,val_6,val_7,val_8,val_9,val_10,
      master_eprouvette_inOut_A,
      if(IF(Cycle_final is null,Cycle_final_temp, cycle_final) >0 AND c_frequence is not null and c_frequence !=0,
        CEIL(
          if(IF(Cycle_STL is null,Cycle_STL_temp, Cycle_STL) is null and c_cycle_STL is null,
            IF(Cycle_final is null,Cycle_final_temp, cycle_final)/eprouvettes.c_frequence/3600,
            if(IF(Cycle_final is null,Cycle_final_temp, cycle_final)>IF(Cycle_STL is null,Cycle_STL_temp, Cycle_STL),
              (IF(Cycle_STL is null,Cycle_STL_temp, Cycle_STL)/c_frequence+(IF(Cycle_final is null,Cycle_final_temp, cycle_final)-IF(Cycle_STL is null,Cycle_STL_temp, Cycle_STL))/c_frequence_STL)/3600,
              (IF(Cycle_final is null,Cycle_final_temp, cycle_final)/c_frequence)/3600
            )
          )
        ),
      "") as temps_essais_calcule,
      temps_essais,

      (SELECT count(*) FROM eprouvettes eps WHERE eps.id_eprouvette<='.$this->id.' AND eps.id_master_eprouvette=eprouvettes.id_master_eprouvette and eps.id_job=eprouvettes.id_job and eps.eprouvette_actif=1) AS retest


      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN prestart ON enregistrementessais.id_prestart=prestart.id_prestart
      LEFT JOIN postes ON prestart.id_poste=postes.id_poste

      LEFT JOIN eprouvettes_temp ON eprouvettes_temp.id_eprouvettes_temp=eprouvettes.id_eprouvette

      LEFT JOIN machines ON postes.id_machine=machines.id_machine
      LEFT JOIN enregistreurs ON enregistreurs.id_enregistreur=postes.id_enregistreur
      LEFT JOIN cell_load ON cell_load.id_cell_load=postes.id_cell_load
      LEFT JOIN cell_displacement ON cell_displacement.id_cell_displacement=postes.id_cell_displacement
      LEFT JOIN extensometres ON extensometres.id_extensometre=postes.id_extensometre
      LEFT JOIN chauffages ON chauffages.id_chauffage=postes.id_chauffage
      LEFT JOIN ind_temps as ind_temps_top ON ind_temps_top.id_ind_temp=postes.id_ind_temp_top
      LEFT JOIN ind_temps as ind_temps_strap ON ind_temps_strap.id_ind_temp=postes.id_ind_temp_strap
      LEFT JOIN ind_temps as ind_temps_bot ON ind_temps_bot.id_ind_temp=postes.id_ind_temp_bot
LEFT JOIN outillages outillage_tops ON outillage_tops.id_outillage=postes.id_outillage_top
LEFT JOIN outillages outillage_bots ON outillage_bots.id_outillage=postes.id_outillage_bot

      LEFT JOIN techniciens t1 ON t1.id_technicien=enregistrementessais.id_operateur
      LEFT JOIN techniciens t2 ON t2.id_technicien=enregistrementessais.id_controleur
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
      LEFT JOIN consigne_types c1 ON c1.id_consigne_type=tbljobs.c_1
      LEFT JOIN consigne_types c2 ON c2.id_consigne_type=tbljobs.c_2
      LEFT JOIN consigne_types c3 ON c3.id_consigne_type=eprouvettes.stepcase_type
      LEFT JOIN matieres ON matieres.id_matiere=id_matiere_std
      LEFT JOIN rawdata ON rawdata.id_rawData=tbljobs.id_rawdata


      LEFT JOIN annexe_iqc ON annexe_iqc.id_annexe_iqc=(SELECT id_eprouvette
          FROM eprouvettes
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
          LEFT JOIN annexe_iqc ON annexe_iqc.id_annexe_iqc=eprouvettes.id_eprouvette
          WHERE id_master_eprouvette= (SELECT id_master_eprouvette FROM eprouvettes WHERE id_eprouvette='.$this->id.')
          AND id_type_essai=20
          AND eprouvette_actif=1
          ORDER BY phase DESC
          LIMIT 1 )

      WHERE eprouvettes.id_eprouvette='.$this->id;
      //echo $req.'<br/><br/>';
      return $this->db->getOne($req);
    }




    public function calculArea($id_dessin_type, $dim_1, $dim_2, $dim_3){
      $req = 'SELECT calcul_area
      FROM dessin_types
      WHERE id_dessin_type='.$id_dessin_type;
      //echo $req.'<br/><br/>';
      $result = $this->db->getOne($req);

      $calcul_area=$result['calcul_area'];
      eval( "\$return['area'] = $calcul_area;" );

      return $return;
    }

    public function dimensions($id_dessin_type){
      $req = 'SELECT denomination_1, denomination_2, denomination_3
      FROM dessin_types
      WHERE id_dessin_type='.$id_dessin_type;
      //echo $req.'<br/><br/>';
      return $this->db->getOne($req);
    }



    public function OLDdenomination($id_dessin_type, $dim_1, $dim_2, $dim_3){
      $req = 'SELECT denomination_1, denomination_2, denomination_3, calcul_area
      FROM dessin_types
      WHERE id_dessin_type='.$id_dessin_type;
      //echo $req.'<br/><br/>';
      $return = $this->db->getOne($req);

      $calcul_area=$return['calcul_area'];
      eval( "\$return['area'] = $calcul_area;" );

      return $return;
    }


    public function OLDdimension($format, $dim1, $dim2, $dim3){
      if ($format=="Cylindrique")	{
        $this->_dimDenomination=array("Diam.");
        $this->_area=$dim1*$dim1*pi()/4;
      }
      elseif ($format=="Tube")	{
        $this->_dimDenomination=array("OD","ID");
        $this->_area=($dim1*$dim1*pi()/4)-($dim2*$dim2*pi()/4);
      }
      elseif ($format=="Plate")	{
        $this->_dimDenomination=array("Largeur","Epaisseur");
        $this->_area=$dim1*$dim2;
      }
      elseif ($format=="Plate Percée")	{
        $this->_dimDenomination=array("Largeur","Epaisseur","ø trou");
        $this->_area=$dim1*$dim2-$dim3*$dim2;
      }
      else	{
        $this->_dimDenomination=array("rien");
        $this->_area='0';
      }
    }

    public function niveaumaxmin($consigne_type_1, $consigne_type_2, $consigne_type_1_val, $consigne_type_2_val){

      // on evite les variable en string en les passant en float
      $consigne_type_1_val=floatval($consigne_type_1_val);
      $consigne_type_2_val=floatval($consigne_type_2_val);

      $this->_R="";
      $this->_R=($consigne_type_1=="R")?$consigne_type_1_val:$this->_R;
      $this->_R=($consigne_type_2=="R")?$consigne_type_2_val:$this->_R;
      $this->_A="";
      $this->_A=($consigne_type_1=="A")?$consigne_type_1_val:$this->_A;
      $this->_A=($consigne_type_2=="A")?$consigne_type_2_val:$this->_A;
      $this->_MAX="";
      $this->_MAX=($consigne_type_1=="Max")?$consigne_type_1_val:$this->_MAX;
      $this->_MAX=($consigne_type_2=="Max")?$consigne_type_2_val:$this->_MAX;
      $this->_MIN="";
      $this->_MIN=($consigne_type_1=="Min")?$consigne_type_1_val:$this->_MIN;
      $this->_MIN=($consigne_type_2=="Min")?$consigne_type_2_val:$this->_MIN;
      $this->_MEAN="";
      $this->_MEAN=($consigne_type_1=="Mean")?$consigne_type_1_val:$this->_MEAN;
      $this->_MEAN=($consigne_type_2=="Mean")?$consigne_type_2_val:$this->_MEAN;
      $this->_ALT="";
      $this->_ALT=($consigne_type_1=="Alt")?$consigne_type_1_val:$this->_ALT;
      $this->_ALT=($consigne_type_2=="Alt")?$consigne_type_2_val:$this->_ALT;
      $this->_ALT=($consigne_type_1=="Range")?$consigne_type_1_val/2:$this->_ALT;
      $this->_ALT=($consigne_type_2=="Range")?$consigne_type_2_val/2:$this->_ALT;


      If (is_numeric($this->_R) And !is_numeric($this->_A))	{
        If ($this->_R == -1) {
          $this->_A = "Infini";
        }
        else  {
          $this->_A = (1 - $this->_R) / (1 + $this->_R);
        }
      }
      elseif (is_numeric($this->_A) And !is_numeric($this->_R))	{
        $this->_R = (1 - $this->_A) / (1 + $this->_A);
      }


      //Si ON a $this->_R (et donc $this->_A), ON calcule les autres valeurs selon la 2eme reference

      If (is_numeric($this->_R)) {
        If ($this->_MAX != "") {
          $this->_MIN = $this->_MAX * $this->_R;
          $this->_MEAN = ($this->_MAX + $this->_MIN) / 2;
          $this->_ALT = $this->_MAX - $this->_MEAN;
        }
        ElseIf (is_numeric($this->_MEAN != "") And ($this->_R != -1)) {
          $this->_ALT = $this->_MEAN * $this->_A;
          $this->_MAX = $this->_MEAN + $this->_ALT;
          $this->_MIN = $this->_MEAN - $this->_ALT;
        }
        ElseIf (is_numeric($this->_ALT) And ($this->_R == -1)) {
          $this->_MEAN = 0;
          $this->_MAX = $this->_ALT;
          $this->_MIN = -$this->_ALT;
        }
        ElseIf (is_numeric($this->_ALT) And ($this->_R != -1)) {
          $this->_MEAN = $this->_ALT / $this->_A;
          $this->_MAX = $this->_MEAN + $this->_ALT;
          $this->_MIN = $this->_MEAN - $this->_ALT;
        }
        ElseIf (is_numeric($this->_MIN)) {
          $this->_MAX = $this->_MIN / $this->_R;
          $this->_MEAN = ($this->_MAX + $this->_MIN) / 2;
          $this->_ALT = $this->_MAX - $this->_MEAN;
        }

      }
      //Si l'on a pas $this->_R (et donc ni $this->_A), ON calcule les autres valeurs selon les 2 references
      ElseIf (!is_numeric($this->_R)) {
        If (is_numeric($this->_MAX) And is_numeric($this->_MIN)) {
          $this->_MEAN = ($this->_MAX + $this->_MIN) / 2;
          $this->_ALT = $this->_MAX - $this->_MEAN;
        }
        ElseIf (is_numeric($this->_MEAN) And is_numeric($this->_ALT)) {
          $this->_MAX = $this->_MEAN + $this->_ALT;
          $this->_MIN = $this->_MEAN - $this->_ALT;
        }
        ElseIf (is_numeric($this->_MAX) And is_numeric($this->_MEAN)) {
          $this->_ALT = $this->_MAX - $this->_MEAN;
          $this->_MIN = $this->_MEAN - $this->_ALT;
        }
        ElseIf (is_numeric($this->_MAX) And is_numeric($this->_ALT)) {
          $this->_MEAN = $this->_MAX - $this->_ALT;
          $this->_MIN = $this->_MEAN - $this->_ALT;
        }
        ElseIf (is_numeric($this->_MIN) And is_numeric($this->_MEAN)) {
          $this->_ALT = $this->_MEAN - $this->_MIN;
          $this->_MAX = $this->_MEAN + $this->_ALT;
        }
        ElseIf (is_numeric($this->_MIN) And is_numeric($this->_ALT)) {
          $this->_MEAN = $this->_ALT - $this->_MIN;
          $this->_MAX = $this->_MEAN + $this->_ALT;
        }


        if ($consigne_type_1=="-" OR $consigne_type_2=="-" OR $consigne_type_1=="" OR $consigne_type_2=="") {
          // Pas de consigne à calculer
        }
        else {
          If ($this->_MAX !="0")  {
            $this->_R=$this->_MIN/$this->_MAX;
            If ($this->_R == -1)  {
              $this->_A = "Infini";
            }
            else{
              $this->_A = (1 - $this->_R) / (1 + $this->_R);
            }
          }
          elseif ($this->_MAX == "0") {
            if ($this->_MIN=="0") {
              $this->_R = "NULL";
              $this->_A= "NULL";
            }
            else {
              $this->_R = "Infini";
              $this->_A=$this->_ALT/$this->_MEAN;
            }

          }
        }
      }

    }


    public function getEstimatedCycle(){
        //d_checked>0 pour activer le calcul du temps uniquement si les données sont validé, essai terminé ou ecrit manuellement (cycle_estime ecrit)
        $req='SELECT c_type_1_val, c_type_2_val, c_type_3_val, c_type_4_val, c_type_5_val,
          ifnull(AVG(IF(cycle_estime IS NOT NULL, cycle_estime,cycle_final)),avg(runout)) AS cycle_estime,
          ifnull(AVG((dilatation-1)*100),"") AS dilatationEstime,
          ifnull(AVG(E_RT),"") AS E_RTEstime,
          ifnull(AVG(E_ht/1000),"") AS E_htEstime,
          ifnull(AVG(c2_max_stress),"") AS c2_max_stressEstimate,
          ifnull(AVG(c2_min_stress),"") AS c2_min_stressEstimate
        FROM eprouvettes
        LEFT JOIN eprouvettes_temp ON eprouvettes_temp.id_eprouvettes_temp=eprouvettes.id_eprouvette
        WHERE id_job=(SELECT id_job FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
          AND ifnull(c_type_1_val,"*") = (SELECT ifnull(c_type_1_val,"*") FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
          AND ifnull(c_type_2_val,"*") = (SELECT ifnull(c_type_2_val,"*") FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
          AND ifnull(c_type_3_val,"*") = (SELECT ifnull(c_type_3_val,"*") FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
          AND ifnull(c_type_4_val,"*") = (SELECT ifnull(c_type_4_val,"*") FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
          AND ifnull(c_type_5_val,"*") = (SELECT ifnull(c_type_5_val,"*") FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
          AND ((cycle_final IS NOT NULL AND d_checked>0) OR (cycle_estime IS NOT NULL) OR (runout is not null))
        GROUP by id_job, c_type_1_val, c_type_2_val, c_type_3_val, c_type_4_val, c_type_5_val';

      /*
        // calcul sans ternir compte du d_checked >0
      $req='SELECT AVG(IF(cycle_estime IS NOT NULL, cycle_estime,cycle_final)) AS cycle_estime, c_type_1_val, c_type_2_val
      FROM eprouvettes
      WHERE id_job=(SELECT id_job FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
      AND c_type_1_val = (SELECT c_type_1_val FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
      AND c_type_2_val = (SELECT c_type_2_val FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
      AND cycle_final IS NOT NULL
      GROUP by id_job, c_type_1_val, c_type_2_val';
*/
      //echo $req.'<br/><br/>';
      return $this->db->getOne($req);
    }

    public function getWorkflow(){
      $reqUpdate='SELECT GROUP_CONCAT(
        IF(d_commentaire ="", "",CONCAT("(",split, ")", d_commentaire))
        ORDER BY phase ASC) as comm,
        SUM(IF(local = 1  AND d_checked = 0, 1, 0)) AS local,
        SUM(IF(ST = 1  AND d_checked = 0, 1, 0)) AS ST,
        GROUP_CONCAT(IF(test_type_abbr=".MA",refSubC,"") SEPARATOR ";") AS MArefSubC,
        GROUP_CONCAT(IF(test_type_abbr=".MA",specification,"") SEPARATOR ";") AS MAspecifs

        FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
        WHERE eprouvettes.eprouvette_actif=1
        AND id_master_eprouvette=(SELECT id_master_eprouvette FROM eprouvettes WHERE id_eprouvette=' .$this->id.')
        AND phase <(SELECT phase FROM eprouvettes LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job  WHERE id_eprouvette=' .$this->id.')
        GROUP BY id_master_eprouvette
        ;';
        //echo $reqUpdate.'<br/>';

        return $this->db->getOne($reqUpdate);
      }

      public function getTempCorrected(){

        $reqInfo = 'SELECT c_temperature, id_temperature_correction_parameter
        FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
        LEFT JOIN prestart ON enregistrementessais.id_prestart=prestart.id_prestart
        WHERE eprouvettes.id_eprouvette='.$this->id.'
        AND id_temperature_correction_parameter is not null
        ';
        $infoTemperature = $this->db->isOne($reqInfo);

        //On cherche la temperature corrigé uniquement si l'essai est enregistré
        if ($infoTemperature) {

          $reqMin = 'SELECT temperature, correction
          FROM temperature_corrections
          WHERE id_temperature_correction_parameter='.$infoTemperature['id_temperature_correction_parameter'].'
          AND temperature < "'.$infoTemperature['c_temperature'].'"
          ORDER by temperature DESC
          LIMIT 1
          ';
          $tMin = $this->db->getOne($reqMin);

          $reqMax = 'SELECT temperature, correction
          FROM temperature_corrections
          WHERE id_temperature_correction_parameter='.$infoTemperature['id_temperature_correction_parameter'].'
          AND temperature > "'.$infoTemperature['c_temperature'].'"
          ORDER by temperature ASC
          LIMIT 1
          ';
          //echo $req.'<br/><br/>';
          $tMax = $this->db->getOne($reqMax);

          if (($tMax['temperature']-$tMin['temperature'])!=0) {

            $temperatureCorrected=
            (($tMax['correction']-$tMin['correction'])/($tMax['temperature']-$tMin['temperature']))
            *($infoTemperature['c_temperature']-$tMax['temperature'])+$tMax['correction'];


            return $temperatureCorrected;
          }
          else {
            return '';
          }
        }

      }

    }
