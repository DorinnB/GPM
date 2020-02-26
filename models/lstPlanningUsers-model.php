<?php
class PlanningUsersModel
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAllPlanningUsers($getBegin,$getEnd) {
    $req='SELECT *

    FROM `planning_users`
    LEFT JOIN planning_types ON planning_types.id_planning_type=planning_users.type
    WHERE dateplanned >= '.$this->db->quote($getBegin).' AND dateplanned < '.$this->db->quote($getEnd).';';

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllPlanningModifValidated($getBegin,$getEnd) {
    $req='SELECT * FROM planning_modif
    LEFT JOIN planning_types ON planning_types.id_planning_type=planning_modif.id_type
    WHERE id_planning_modif IN (
      SELECT max(id_planning_modif) FROM `planning_modif` WHERE id_validator >0
      GROUP BY datemodif, id_user
    )
    AND datemodif >= '.$this->db->quote($getBegin).' AND datemodif < '.$this->db->quote($getEnd).'
    ORDER BY id_planning_modif ASC;';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllPlanningModifAwaiting($getBegin,$getEnd) {
    $req='SELECT * FROM planning_modif
    LEFT JOIN planning_types ON planning_types.id_planning_type=planning_modif.id_type
    WHERE id_planning_modif IN (
      SELECT max(id_planning_modif) FROM `planning_modif` WHERE id_validator IS NULL
      GROUP BY datemodif, id_user
    )
    AND datemodif >= '.$this->db->quote($getBegin).' AND datemodif < '.$this->db->quote($getEnd).'
    ORDER BY id_planning_modif ASC;';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllPlanningDone($getBegin,$getEnd) {
    $req='SELECT date, id_user, ((HOUR(validation)*60)+ MINUTE(validation))/60 as validation, validation2
    FROM badges
    WHERE (validation IS NOT NULL OR validation2 IS NOT NULL)
    AND  date >= '.$this->db->quote($getBegin).' AND date < '.$this->db->quote($getEnd).'
    ORDER BY date ASC;';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllUsers() {
    $req='SELECT id_technicien, technicien
    FROM badge_HR
    LEFT JOIN techniciens on badge_HR.id_user=techniciens.id_technicien
    WHERE id_technicien != 1 AND technicien_actif=1
    ORDER BY id_technicien;';  // !=1 pour ignorer le compte GPM

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllUsersManaged() {
    $req='SELECT id_technicien, technicien
    FROM badge_access
    LEFT JOIN techniciens on badge_access.id_managed=techniciens.id_technicien
    WHERE id_manager = '.$this->db->quote($_COOKIE['id_user']).'
    ORDER BY id_technicien ASC;';

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllPlanningTypes() {
    $req='SELECT *
    FROM `planning_types`
    WHERE planning_type_actif=1;';

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllPlanningSummary($getBegin,$getEnd) {
    /*
    $req='SELECT
    planning_users.id_user,
    ifnull(id_type, type) AS id_type,
    COUNT(ifnull(id_type, type)) AS count,
    SUM(ifnull(planning_modif.quantity, planning_users.quantity)) AS sum,
    SUM(if(ifnull(planning_modif.quantity, planning_users.quantity)=0.5,0.5,0)) AS sum2

    FROM planning_users
    LEFT JOIN planning_modif ON (planning_modif.datemodif=planning_users.dateplanned AND
      id_planning_modif IN (
        SELECT max(pm.id_planning_modif) from planning_modif AS pm WHERE pm.id_validator>0 GROUP BY pm.datemodif
      )
    )
    WHERE dateplanned>= '.$this->db->quote($getBegin).' AND dateplanned<= '.$this->db->quote($getEnd).'

    GROUP BY planning_users.id_user, IFNULL(id_type, type);';
*/
    $req='SELECT
    planning_users.id_user,
    SUM(if(ifnull(id_type, type)=1 OR ifnull(id_type, type)=6, 1, 0)) AS C1,
    SUM(if(ifnull(id_type, type)=1 OR ifnull(id_type, type)=6, ifnull((TIME_TO_SEC(badges.validation)/3600),ifnull(badges.validation2,(ifnull(planning_modif.quantity, planning_users.quantity)))), 0)) AS Q1,
    SUM(if(ifnull(id_type, type)=2,1,0)) AS C2,
    SUM(if(ifnull(id_type, type)=2,ifnull(planning_modif.quantity, planning_users.quantity),0)) AS Q2,
    SUM(if(ifnull(id_type, type)=3,1,0)) AS C3,
    SUM(if(ifnull(id_type, type)=3,ifnull(planning_modif.quantity, planning_users.quantity),0)) AS Q3,
    SUM(if(ifnull(id_type, type)=4,1,0)) AS C4,
    SUM(if(ifnull(id_type, type)=4,ifnull(planning_modif.quantity, planning_users.quantity),0)) AS Q4,
    SUM(if(ifnull(id_type, type)=5,1,0)) AS C5,
    SUM(if(ifnull(id_type, type)=5,ifnull(planning_modif.quantity, planning_users.quantity),0)) AS Q5,
    SUM(if(((ifnull(id_type, type)=1 OR ifnull(id_type, type)=6) AND DAYOFWEEK(dateplanned)=7) ,1,0)) AS QSaturdayON, SUM(if(((ifnull(id_type, type)=1 OR ifnull(id_type, type)=6) AND DAYOFWEEK(dateplanned)=7),ifnull(planning_modif.quantity, planning_users.quantity),0)) AS CSaturdayON

    FROM planning_users
    LEFT JOIN planning_modif ON (planning_modif.datemodif=planning_users.dateplanned AND
      id_planning_modif IN (
        SELECT max(pm.id_planning_modif)
        FROM planning_modif AS pm
        WHERE pm.id_validator>0 AND pm.id_user=planning_users.id_user
        GROUP BY pm.datemodif
      )
    )
    LEFT JOIN badges ON badges.id_user=planning_users.id_user AND badges.date=planning_users.dateplanned AND badges.id_validator>0

    WHERE dateplanned >= '.$this->db->quote($getBegin).' AND dateplanned < '.$this->db->quote($getEnd).'
    GROUP BY planning_users.id_user;';

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getAllPlanningModifSummary($getBegin,$getEnd) {
    $req='SELECT
    planning_users.id_user,
    SUM(if(id_type=1,1,0))- SUM(if(type=1,1,0)) AS C1,
    SUM(if(id_type=1,planning_modif.quantity,0)) - SUM(if(type=1 ,planning_users.quantity,0)) AS Q1,
    SUM(if(id_type=2,1,0))- SUM(if(type=2,1,0)) AS C2,
    SUM(if(id_type=2,planning_modif.quantity,0)) - SUM(if(type=2,planning_users.quantity,0)) AS Q2,
    SUM(if(id_type=3,1,0))- SUM(if(type=3,1,0)) AS C3,
    SUM(if(id_type=3,planning_modif.quantity,0)) - SUM(if(type=3,planning_users.quantity,0)) AS Q3,
    SUM(if(id_type=5,1,0))- SUM(if(type=5,1,0)) AS C5,
    SUM(if(id_type=5,planning_modif.quantity,0)) - SUM(if(type=5,planning_users.quantity,0)) AS Q5,
    SUM(if(id_type=6,1,0))- SUM(if(type=6,1,0)) AS C6,
    SUM(if(id_type=6,planning_modif.quantity,0)) - SUM(if(type=6,planning_users.quantity,0)) AS Q6,
    SUM(if(id_type=7,1,0))- SUM(if(type=7,1,0)) AS C7,
    SUM(if(id_type=7,planning_modif.quantity,0)) - SUM(if(type=7,planning_users.quantity,0)) AS Q7

    FROM planning_modif
    LEFT JOIN planning_users ON planning_modif.datemodif=planning_users.dateplanned AND planning_modif.id_user=planning_users.id_user
    WHERE planning_modif.id_validator IS NULL AND dateplanned >= '.$this->db->quote($getBegin).' AND dateplanned < '.$this->db->quote($getEnd).'
    GROUP BY planning_users.id_user;';
    //echo $req;
    return $this->db->getAll($req);
  }



}
