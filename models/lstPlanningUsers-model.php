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
    $req='SELECT id_technicien, technicien, badge_type
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
    WHERE id_manager = '.$this->db->quote((isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0)).'
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

  public function getAllManagedAwaiting() {
    $req='SELECT technicien, count(*) as nb
    FROM badge_access
    LEFT JOIN planning_modif ON planning_modif.id_user=badge_access.id_managed
    LEFT JOIN techniciens ON techniciens.id_technicien=planning_modif.id_user
    WHERE planning_modif.id_validator IS NULL AND id_manager = '.$this->db->quote((isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0)).'
    GROUP BY technicien;';

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
    SUM(if(planning_modif.id_type=1,1,0))- SUM(if(ifnull(p1.id_type, type)=1,1,0)) AS C1,
    SUM(if(planning_modif.id_type=1,planning_modif.quantity,0)) - SUM(if(ifnull(p1.id_type, type)=1 ,ifnull(p2.quantity,planning_users.quantity),0)) AS Q1,
    SUM(if(planning_modif.id_type=2,1,0))- SUM(if(ifnull(p1.id_type, type)=2,1,0)) AS C2,
    SUM(if(planning_modif.id_type=2,planning_modif.quantity,0)) - SUM(if(ifnull(p1.id_type, type)=2,ifnull(p2.quantity,planning_users.quantity),0)) AS Q2,
    SUM(if(planning_modif.id_type=3,1,0))- SUM(if(ifnull(p1.id_type, type)=3,1,0)) AS C3,
    SUM(if(planning_modif.id_type=3,planning_modif.quantity,0)) - SUM(if(ifnull(p1.id_type, type)=3,ifnull(p2.quantity,planning_users.quantity),0)) AS Q3,
    SUM(if(planning_modif.id_type=5,1,0))- SUM(if(ifnull(p1.id_type, type)=5,1,0)) AS C5,
    SUM(if(planning_modif.id_type=5,planning_modif.quantity,0)) - SUM(if(ifnull(p1.id_type, type)=5,ifnull(p2.quantity,planning_users.quantity),0)) AS Q5,
    SUM(if(planning_modif.id_type=6,1,0))- SUM(if(ifnull(p1.id_type, type)=6,1,0)) AS C6,
    SUM(if(planning_modif.id_type=6,planning_modif.quantity,0)) - SUM(if(ifnull(p1.id_type, type)=6,ifnull(p2.quantity,planning_users.quantity),0)) AS Q6,
    SUM(if(planning_modif.id_type=7,1,0))- SUM(if(ifnull(p1.id_type, type)=7,1,0)) AS C7,
    SUM(if(planning_modif.id_type=7,planning_modif.quantity,0)) - SUM(if(ifnull(p1.id_type, type)=7,ifnull(p2.quantity,planning_users.quantity),0)) AS Q7

    FROM planning_modif
    LEFT JOIN planning_users ON planning_modif.datemodif=planning_users.dateplanned AND planning_modif.id_user=planning_users.id_user
    LEFT JOIN planning_modif p1 ON (p1.datemodif=planning_users.dateplanned AND p1.id_planning_modif IN (
      SELECT max(pm.id_planning_modif)
      FROM planning_modif AS pm
      WHERE pm.id_validator>0 AND pm.id_user=planning_users.id_user
      GROUP BY pm.datemodif )
    )
      LEFT JOIN planning_modif p2 ON (p2.datemodif=planning_users.dateplanned AND p2.id_planning_modif IN (
      SELECT max(pm.id_planning_modif)
      FROM planning_modif AS pm
      WHERE pm.id_validator=0 AND pm.id_user=planning_users.id_user
      GROUP BY pm.datemodif )
    )
    WHERE planning_modif.id_validator IS NULL AND dateplanned >= '.$this->db->quote($getBegin).' AND dateplanned < '.$this->db->quote($getEnd).'
    GROUP BY planning_users.id_user;';
    //echo $req;
    return $this->db->getAll($req);
  }



  public function getAllPlanningUpdated($getBegin,$getEnd) {
    $req='SELECT

    planning_users.id_user, dateplanned, ifnull(planning_modif.quantity, planning_users.quantity) as quantity, ifnull(planning_modif.id_type, planning_users.type) as id_type,
    validation, resthours, workable,
    hour(validation)+minute(validation)/60+second(validation)/3600 as val,

    if(
      (TIMESTAMPDIFF(second,in1,out1)+TIMESTAMPDIFF(second,in2,out2))<ifnull(planning_modif.quantity, planning_users.quantity)*3600,
      if(
        (TIMESTAMPDIFF(second,in1,out1)+TIMESTAMPDIFF(second,in2,out2)-resthours*3600)>0,
        (TIMESTAMPDIFF(second,in1,out1)+TIMESTAMPDIFF(second,in2,out2)-resthours*3600),
        0
      ),
      if(
        ((TIMESTAMPDIFF(second,in1,out1)+TIMESTAMPDIFF(second,in2,out2))>=ifnull(planning_modif.quantity, planning_users.quantity)*3600) AND ((TIMESTAMPDIFF(second,in1,out1)+TIMESTAMPDIFF(second,in2,out2))<=ifnull(planning_modif.quantity, planning_users.quantity)*3600+resthours*3600),
        ifnull(planning_modif.quantity, planning_users.quantity)*3600,
        TIMESTAMPDIFF(second,in1,out1)+TIMESTAMPDIFF(second,in2,out2)-resthours*3600
      )
    )/3600
    as calculGPM

    FROM planning_users

    LEFT JOIN planning_types ON planning_types.id_planning_type=planning_users.type
    LEFT JOIN planning_modif on planning_modif.id_user=planning_users.id_user and planning_modif.datemodif=planning_users.dateplanned and planning_modif.id_planning_modif in (select max(pm.id_planning_modif) from planning_modif pm where pm.id_validator>0 group by pm.id_user, pm.datemodif)
    LEFT JOIN badges on badges.id_user=planning_users.id_user and badges.date=planning_users.dateplanned
    LEFT JOIN badge_hr on badge_hr.id_user=planning_users.id_user


    where
    dateplanned >= '.$this->db->quote($getBegin).' AND dateplanned < '.$this->db->quote($getEnd).'

    order by planning_users.dateplanned;';

    //echo $req;
    return $this->db->getAll($req);
  }


}
