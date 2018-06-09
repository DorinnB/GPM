<?php
class LabModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


//TEMPORAIRE :
    public function getTest() {
      $req='SELECT info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier,

 if(currentBlock is null, currentBlock_temp,currentBlock) as currentBlock_temp,
 if(cycle_final is null, Cycle_final_temp,cycle_final) as Cycle_final_temp,

       split, machine, postes.id_machine, poste, id_job, n_essai,
        c_frequence, c_frequence_STL, c_cycle_STL, d_frequence, d_frequence_STL, Cycle_STL, runout, c_temperature,
        statut, etape,
        texte_machine_forecast, icone_file, icone_name, prio_machine_forecast, etape
        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        LEFT JOIN machines ON machines.id_machine=postes.id_machine
        LEFT JOIN machine_forecasts ON machine_forecasts.id_machine_forecast=machines.id_machine
        LEFT JOIN icones ON icones.id_icone=machine_forecasts.id_icone_machine_forecast
        LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
        LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp

        LEFT JOIN eprouvettes_temp ON eprouvettes_temp.id_eprouvettes_temp=eprouvettes.id_eprouvette

        WHERE n_fichier IN (SELECT max(n_fichier)
        FROM `enregistrementessais`
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        WHERE id_eprouvette IS NOT NULL
        GROUP BY postes.id_machine)
        AND machine IS NOT NULL
        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getTestToStart() {
      $req='SELECT max(statut) as statut,max(statut_color) as statut_color, max(etape) as etape, count(etape) as nb
        FROM tbljobs
        LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
        LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai

        WHERE tbljob_actif=1 AND ST=0 AND auxilaire=0 AND (
        etape=55 OR etape=30 OR etape=40 Or etape=53 or etape=48)
        group by etape
        order by etape asc';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getCheckList() {
      $req='SELECT info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, split, machine, poste, id_job,
        machine, t1.technicien as operateur, t2.technicien as controleur, IF(currentBlock IS NULL,currentBlock_temp, currentBlock) as currentBlock, tbljobs.id_tbljob

        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN eprouvettes_temp ON eprouvettes_temp.id_eprouvettes_temp=eprouvettes.id_eprouvette
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        LEFT JOIN machines ON machines.id_machine=postes.id_machine
        LEFT JOIN techniciens t1 ON t1.id_technicien=enregistrementessais.id_operateur
        LEFT JOIN techniciens t2 ON t2.id_technicien=enregistrementessais.id_controleur

        WHERE IF(currentBlock is null,currentBlock_temp, currentBlock)="Check" OR (Cycle_final>0 AND id_controleur=0)
        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getCheckRuptureList() {
      $req='SELECT eprouvettes.id_eprouvette, info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, split, machine, poste, id_job, n_essai
        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
        LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        LEFT JOIN machines ON machines.id_machine=postes.id_machine

        WHERE check_rupture<=0
          AND currentBlock="Send"
          AND etape<80

        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getCheckDataValueList() {
      $req='SELECT eprouvettes.id_eprouvette, info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, split, machine, poste, id_job, n_essai
        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
        LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        LEFT JOIN machines ON machines.id_machine=postes.id_machine

        WHERE d_checked<=0
          AND currentBlock="Send"
          AND n_fichier>48150
          AND n_essai !=1
          AND etape<80

        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getTodoLab() {
      $req='SELECT texte_lab_forecast, prio_lab_forecast, icone_file, icone_name
        FROM lab_forecasts
        LEFT JOIN icones ON icones.id_icone=lab_forecasts.id_icone_lab_forecast

        order by
        if(prio_lab_forecast = 0,1,0), prio_lab_forecast asc, texte_lab_forecast asc';
        //echo $req;
        return $this->db->getAll($req);
    }
}
