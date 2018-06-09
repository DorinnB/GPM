<?php
class StatutModel
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




  public function getJobFromEp($idEp){
    $req='SELECT id_job FROM eprouvettes WHERE id_eprouvette= '. $this->db->quote($idEp).';';

    return $this->db->getOne($req);
  }

  public function getJobFromInfoJob($idInfoJob){
    $req='SELECT id_tbljob FROM tbljobs WHERE id_info_job= '. $this->db->quote($idInfoJob).';';

    return $this->db->getAll($req);
  }

  public function updateStatut($id_statut){
    $reqUpdate='
    INSERT INTO tbljobs_temp
    (tbljobs_temp.id_tbljobs_temp, tbljobs_temp.id_statut_temp)
    SELECT id_tbljob, '.$this->db->quote($id_statut).'
    FROM tbljobs
    LEFT JOIN tbljobs_temp on tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE
    tbljobs.id_tbljob = '.$this->id_tbljob.' AND (statuts.statut_lock !=1 OR statuts.statut_lock IS NULL)
    ON DUPLICATE KEY UPDATE tbljobs_temp.id_statut_temp=values(tbljobs_temp.id_statut_temp), date_modif_temp = current_timestamp
    ;';
    $result = $this->db->query($reqUpdate);
  }

  public function updateStatut2($id_statut){
    $reqUpdate='
    INSERT INTO tbljobs_temp
    (tbljobs_temp.id_tbljobs_temp, tbljobs_temp.id_statut_temp)
    SELECT id_tbljob, '.$this->db->quote($id_statut).'
    FROM tbljobs
    LEFT JOIN tbljobs_temp on tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    WHERE
    tbljobs.id_tbljob = '.$this->id_tbljob.'
    ON DUPLICATE KEY UPDATE tbljobs_temp.id_statut_temp=values(tbljobs_temp.id_statut_temp), date_modif_temp = current_timestamp
    ;';
    $result = $this->db->query($reqUpdate);
  }

  public function findStatut(){
    $req='SELECT
    if(report_Q>0,1,0) as report_Q,
    if(report_TM>0,1,0) as report_TM,
    if(report_send>0,1,0) as report_send,
    if(checked>0,1,0) as OT_checked,
    if(staircase>0 OR specific_protocol>0,1,0) as specific_protocol,
    sum(if(master_eprouvette_inOut_A is null,1,0)) as nb_awaiting_specimen,

    sum(if((eprouvette_inOut_A is not null or enregistrementessais.date is not null) and
    (eprouvette_inOut_B is null and report_creation_date is null and d_checked <=0)
    ,1,0)) as nbrunning,

    sum(if((eprouvette_inOut_B is not null or report_creation_date is not null or d_checked >0),0,1)) as nb_before_end,
    sum(if((eprouvette_inOut_A is null AND enregistrementessais.date is null),1,0)) as nb_untested,

    sum(if(d_checked<=0,1,0)) as nb_unDchecked,

    sum( IFNULL(
      (SELECT if((ep.eprouvette_inOut_B is not null or ep.report_creation_date is not null or ep.d_checked >0),0,1)
      FROM eprouvettes ep
      LEFT JOIN tbljobs tbl ON tbl.id_tbljob=ep.id_job
      WHERE ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
      AND tbl.phase<tbljobs.phase
      AND ep.eprouvette_actif=1
      ORDER BY phase asc
      LIMIT 1
    ),0)
  ) as nb_awaiting_previous_split,

  sum(
    if((master_eprouvette_inOut_A is null OR
      IFNULL(
        (SELECT if((ep.eprouvette_inOut_B is not null or ep.report_creation_date is not null or ep.d_checked >0),0,1)
        FROM eprouvettes ep
        LEFT JOIN tbljobs tbl ON tbl.id_tbljob=ep.id_job
        WHERE ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
        AND tbl.phase<tbljobs.phase
        AND ep.eprouvette_actif=1
        ORDER BY phase asc
        LIMIT 1
      ),0) =1 )  OR (eprouvette_inOut_A is not null or enregistrementessais.date is not null)
      ,0,1)
    ) as nb_ep_dispo,

    sum(
      if(((master_eprouvette_inOut_A is null OR
        IFNULL(
          (SELECT if((ep.eprouvette_inOut_B is not null or ep.report_creation_date is not null or ep.d_checked >0),0,1)
          FROM eprouvettes ep
          LEFT JOIN tbljobs tbl ON tbl.id_tbljob=ep.id_job
          WHERE ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
          AND tbl.phase<tbljobs.phase
          AND ep.eprouvette_actif=1
          ORDER BY phase asc
          LIMIT 1
        ),0) =1 )  OR (eprouvette_inOut_A is not null or enregistrementessais.date is not null)) OR c_checked<=0
        ,0,1)
      ) as nb_consigne_dispo  ,

      sum(
        if((master_eprouvette_inOut_A is null OR
          IFNULL(
            (SELECT if((ep.eprouvette_inOut_B is not null or ep.report_creation_date is not null or ep.d_checked >0),0,1)
            FROM eprouvettes ep
            LEFT JOIN tbljobs tbl ON tbl.id_tbljob=ep.id_job
            WHERE ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
            AND tbl.phase<tbljobs.phase
            AND ep.eprouvette_actif=1
            ORDER BY phase asc
            LIMIT 1
          ),0) =1 )  OR c_checked>0
          ,0,1)
        ) as nb_ep_dispo_sans_consigne


        FROM eprouvettes
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job

        WHERE tbljobs.id_tbljob= '. $this->id_tbljob.'
        AND master_eprouvette_actif=1
        AND eprouvette_actif=1
        GROUP BY tbljobs.id_tbljob
        ';

        //echo $req;

        $state = $this->db->getOne($req);


        $statut='';
        $id_statut=0;

        if ($state['report_send']==1) {
          $id_statut=90;
          $statut='Completed';
        }
        elseif ($state['OT_checked']==0) {
          $id_statut=10;
          $statut='Check OT';
        }
        else {
          if ($state['nbrunning']>0) {  //si une ep en test
            if ($state['nb_untested']==0) { //plus d'ep restant
              $id_statut=52;
              $statut='Last Tests Running';
            }
            else {
              if ($state['nb_consigne_dispo']==0) {  //derniere consigne
                if ($state['specific_protocol']>0) {  //si on sait quoi faire apres
                  $id_statut=50;
                  $statut='Running';
                }
                else {  //si l'on a pas de consigne (ni protocole ni client)
                  $id_statut=51;
                  $statut='Last Condition';
                }
              }
              else {  //running
                $id_statut=50;
                $statut='Running';
              }
            }
          }
          else {  //non running
            if ($state['nb_before_end']==0 and $state['nb_unDchecked']>0) { //manque de check technicien
              $id_statut=59;
              $statut='Waiting data check by technician';
            }
            elseif ($state['nb_before_end']==0) { //plus d'essai a faire
              $id_statut=70;
              $statut='FQC';


              if ($state['report_Q']>0) {
                $id_statut=71;
                $statut='FQC Q done';
              }
              if (($state['report_Q']>0 AND $state['report_TM']>0)) {
                $id_statut=80;
                $statut='Report Ready';
              }

            }
            else {  //s'il reste des essais a faire
              if ($state['nb_consigne_dispo']>0) { //ready to start
                $id_statut=40;
                $statut='Ready to Start';
              }
              else {
                if ($state['nb_ep_dispo_sans_consigne']>0) {  //plus de consigne

                  if ($state['specific_protocol']>0) {  //si on sait quoi faire apres
                    $id_statut=40;
                    $statut='Ready to Start';
                  }
                  else {  //aucune consigne (job ou client) pour continuer
                    $id_statut=30;
                    $statut='Awaiting Consigne';
                  }

                }
                elseif ($state['nb_awaiting_specimen']>0) { //attente specimen
                  $id_statut=20;
                  $statut='Awaiting Raw Specimen (initial)';
                }
                elseif ($state['nb_awaiting_previous_split']>0) { //attente specimen split
                  $id_statut=21;
                  $statut='Awaiting Specimen (from previous split)';
                }
                else {
                  $id_statut=0;
                  $statut='ERREUR contact PGO with job number please';
                }
              }
            }
          }
        }



        $maReponse = array('req'=> $req, 'id_statut' => $id_statut, 'statut' => $statut);
        //echo json_encode($maReponse);
        $this->updateStatut($id_statut);

      }

    }
