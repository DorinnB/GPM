<?php
class LstEtatMachines
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAllEtatMachines() {
    $req='SELECT * FROM etatmachine_machines
    LEFT JOIN machines on machines.id_machine=etatmachine_machines.id_machine
    LEFT JOIN tbljobs on tbljobs.id_tbljob=etatmachine_machines.id_tbljob
    LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
    LEFT JOIN statuts on statuts.id_statut=etatmachine_machines.id_statut_temp

    ORDER BY id_etatmachine_machine;';
    return $this->db->getAll($req);
  }

  public function getAllEtatMachines_machine($filtre="Frame") {

    if ($filtre=="Year") {
      $filtered='YEAR(periode)';
    }
    if ($filtre=="Month") {
      $filtered='MONTH(periode)';
    }
    else {
      $filtered='machine';
    }


    $req="SELECT
    max(machine) as machine,
    sum(cumul)/60 as cumul,
    SUM(if(etatmachine in ('Load','Strain','Dwell','Not','Fluage','Switchable'),cumul,0))/60 as cycling,
    SUM(if(etatmachine in ('Ramp'),cumul,0))/60 as rampToTemp,
    SUM(if(etatmachine is null or etatmachine in ( 'Init','Menu','Parameters','Adv.','Check','Amb.','ET','STL','Stop','Straightening','Report','Analysis','Restart'),cumul,0))/60 as noncycling,
    SUM(if(etatmachine in ('Send'),cumul,0))/60 as send,
    SUM(if(etatmachine='Send' AND (etape>=59 OR etape=52),cumul,0))/60 as noTest,
    SUM(if(etatmachine='Send' AND etape in (30,47),cumul,0))/60 as waitingCustomer,
    SUM(if(etatmachine='Send' AND etape in (40,46,48,50,51),cumul,0))/60 as waitingLab,

    max(periode) as periode

    FROM etatmachine_machines
    LEFT JOIN machines on machines.id_machine=etatmachine_machines.id_machine
    LEFT JOIN tbljobs on tbljobs.id_tbljob=etatmachine_machines.id_tbljob
    LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
    LEFT JOIN statuts on statuts.id_statut=etatmachine_machines.id_statut_temp

    group by ".$filtered." ;";
    return $this->db->getAll($req);
  }

  public function getAllEtatMachines_split($id_tbljob) {
    $req="SELECT
    machine,
    sum(cumul)/60 as cumul,
    SUM(if(etatmachine in ('Load','Strain','Dwell','Not','Fluage'),cumul,0))/60 as cycling,
    SUM(if(etatmachine in ('Ramp'),cumul,0))/60 as rampToTemp,
    SUM(if(etatmachine is null or etatmachine in ( 'Init','Menu','Parameters','Adv.','Check','Amb.','ET','Switchable','STL','Stop','Straightening','Report','Analysis'),cumul,0))/60 as noncycling,
    SUM(if(etatmachine in ('Send'),cumul,0))/60 as send,
    SUM(if(etatmachine='Send' AND (etape>=59 OR etape=52),cumul,0))/60 as noTest,
    SUM(if(etatmachine='Send' AND etape in (30,47),cumul,0))/60 as waitingCustomer,
    SUM(if(etatmachine='Send' AND etape in (40,46,48,50,51),cumul,0))/60 as waitingLab,

    max(periode) as periode

    FROM etatmachine_machines
    LEFT JOIN machines on machines.id_machine=etatmachine_machines.id_machine
    LEFT JOIN tbljobs on tbljobs.id_tbljob=etatmachine_machines.id_tbljob
    LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
    LEFT JOIN statuts on statuts.id_statut=etatmachine_machines.id_statut_temp
    WHERE id_job='.$this->bd->quote($id_tbljob).'
    group by id_tbljob;";
    return $this->db->getAll($req);
  }

  public function getAllFrameUtilization($group="Day", $filtre="Lab") {

    if ($group=="Day") {  //Jours
      if ($filtre=="Lab") { //jours Lab
        $groupBy='periode';
        $filterBy='';
      }
      else {  //jours uniquement machine
        $groupBy='periode, machine';
        $filterBy='WHERE machines.id_machine='.$filtre;
      }

      $req="SELECT
      max(machine) as machine,
      sum(cumul)/60 as cumul,
      SUM(if(etatmachine in ('Load','Strain','Dwell','Not','Fluage','Switchable'),cumul,0))/60 as cycling,
      SUM(if(etatmachine in ('Ramp'),cumul,0))/60 as rampToTemp,
      SUM(if(etatmachine is null or etatmachine in ( 'Init','Menu','Parameters','Adv.','Check','Amb.','ET','STL','Stop','Straightening','Report','Analysis','Restart'),cumul,0))/60 as noncycling,
      SUM(if(etatmachine in ('Send'),cumul,0))/60 as send,
      SUM(if(etatmachine='Send' AND (etape>=59 OR etape=52 OR etape=20),cumul,0))/60 as noTest,
      SUM(if(etatmachine='Send' AND etape in (30,47),cumul,0))/60 as waitingCustomer,
      SUM(if(etatmachine='Send' AND etape in (40,46,48,50,51),cumul,0))/60 as waitingLab,

      max(periode) as periode

      FROM etatmachine_jours
      LEFT JOIN machines on machines.id_machine=etatmachine_jours.id_machine
      LEFT JOIN tbljobs on tbljobs.id_tbljob=etatmachine_jours.id_tbljob
      LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
      LEFT JOIN statuts on statuts.id_statut=etatmachine_jours.id_statut_temp

      ".$filterBy."
      GROUP BY ".$groupBy." ;";
      //echo $req;
      return $this->db->getAll($req);

    }
    else {  //on prend l'autre requete (autre table)

      if ($filtre=="Lab") { //jours Lab
        $filterBy='';
        $groupBy='';
      }
      elseif ($filtre=="Frame") {
        $groupBy='machine,';
        if ($group=="Month") {
          $filterBy.='WHERE MONTH(periode)>=MONTH(now())';
        }
        elseif ($group=="Year") {
          $filterBy.='WHERE YEAR(periode)>=YEAR(now())';
        }
      }
      else {  //jours uniquement machine
        $filterBy='WHERE machines.id_machine='.$filtre;
      }

      if ($group=="Month") {
        $groupBy.='MONTH(periode)';
      }
      elseif ($group=="Year") {
        $groupBy.='YEAR(periode)';
      }
      else {
        $groupBy.='periode';
      }





      $req="SELECT
      max(machine) as machine,
      sum(cumul)/60 as cumul,
      SUM(if(etatmachine in ('Load','Strain','Dwell','Not','Fluage','Switchable'),cumul,0))/60 as cycling,
      SUM(if(etatmachine in ('Ramp'),cumul,0))/60 as rampToTemp,
      SUM(if(etatmachine is null or etatmachine in ( 'Init','Menu','Parameters','Adv.','Check','Amb.','ET','STL','Stop','Straightening','Report','Analysis','Restart'),cumul,0))/60 as noncycling,
      SUM(if(etatmachine in ('Send'),cumul,0))/60 as send,
      SUM(if(etatmachine='Send' AND (etape>=59 OR etape=52 OR etape=20),cumul,0))/60 as noTest,
      SUM(if(etatmachine='Send' AND etape in (30,47),cumul,0))/60 as waitingCustomer,
      SUM(if(etatmachine='Send' AND etape in (40,46,48,50,51),cumul,0))/60 as waitingLab,

      max(periode) as periode

      FROM etatmachine_machines
      LEFT JOIN machines on machines.id_machine=etatmachine_machines.id_machine
      LEFT JOIN tbljobs on tbljobs.id_tbljob=etatmachine_machines.id_tbljob
      LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
      LEFT JOIN statuts on statuts.id_statut=etatmachine_machines.id_statut_temp
      ".$filterBy."
      GROUP BY ".$groupBy." ;";

      echo $req;

      return $this->db->getAll($req);







    }
  }

}

/*SELECT date, count(n_fichier), test_type

FROM enregistrementessais
left join eprouvettes on eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job
left join test_type on test_type.id_test_type=tbljobs.id_type_essai

where date in (select periode from etatmachine_jours)
and enregistrementessais.id_eprouvette is not null
group by date, test_type
*/
