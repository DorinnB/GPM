<?php
class BadgeModel
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

  public function isBadge() {

    if (isset($_COOKIE['id_user'])) {
      $req='SELECT badge_type
      FROM badge_HR
      WHERE id_user='.$_COOKIE['id_user'].';';

      $return = $this->db->getOne($req);

      return $return['badge_type'];
    }
    else {
      return 0;
    }
  }

  public function getClockState() {

    $req='SELECT `id_badge`, `id_user`, `in1`, `out1`, `in2`, `out2`, `validation`, `comments`, `id_validator`,
    IF(in1 IS NULL OR (in2 IS NULL AND out1 IS NOT NULL),1,0) AS unclocked,
    IF(in1 IS NULL OR ((DATE_ADD(NOW(), INTERVAL -5 HOUR) > in1) AND in2 IS NULL),1,0) AS unclocked2
    FROM badges
    WHERE id_user='.$_COOKIE['id_user'].'
    AND TO_DAYS(NOW()) =  TO_DAYS(date)
    ORDER BY id_badge DESC
    LIMIT 1;';

    return $this->db->getOne($req);

  }

  public function getClockCount() {

    $req='SELECT SEC_TO_TIME( IFNULL(TIMESTAMPDIFF(SECOND, in1,IFNULL(out1,CURRENT_TIMESTAMP)),0) + IFNULL(TIMESTAMPDIFF(SECOND, IFNULL(in2,CURRENT_TIMESTAMP),IFNULL(out2,CURRENT_TIMESTAMP)),0)) as clockCount
    FROM badges
    WHERE id_user='.$_COOKIE['id_user'].'
    AND TO_DAYS(NOW()) = TO_DAYS(date)
    ORDER BY id_badge DESC;';

    return $this->db->getOne($req);

  }

  public function insertClock() {

    $reqInsert = '
    INSERT INTO badges
    (`id_user`, `in1`, `date`)
    VALUES
    ('.$this->db->quote($_COOKIE['id_user']).', CURRENT_TIMESTAMP, CURDATE());';

    echo $reqInsert;
    $this->db->query($reqInsert);
  }

  public function updateClock($clock) {

    $reqUpdate = 'UPDATE `badges` SET '.$clock.' = CURRENT_TIMESTAMP WHERE id_user='.$_COOKIE['id_user'].' AND TO_DAYS(NOW()) = TO_DAYS(date);';

    echo $reqUpdate;
    $this->db->query($reqUpdate);
  }

  public function getAllManagedAwaiting() {
    $req='SELECT
    technicien, count(*) AS nb
    FROM badges
    LEFT JOIN badge_hr on badge_hr.id_user=badges.id_user
    LEFT JOIN badgeplanning ON badgeplanning.id_badge=badges.id_badge
    LEFT JOIN techniciens on techniciens.id_technicien=badges.id_user
    LEFT JOIN planning_users on planning_users.id_user=badges.id_user and planning_users.dateplanned=badges.date
    LEFT JOIN planning_modif ON planning_modif.id_user=planning_users.id_user and planning_modif.datemodif=planning_users.dateplanned and planning_modif.id_planning_modif in (select max(pm.id_planning_modif) from planning_modif pm where pm.id_validator>0 group by pm.id_user, pm.datemodif)
    WHERE badge_type=1 AND badges.validation IS NULL
    AND if(ifnull(TIMESTAMPDIFF(SECOND, badges.in2, badges.out2),0)+ifnull(TIMESTAMPDIFF(SECOND, badges.in1, badges.out1),0) < ifnull(planning_modif.quantity, planning_users.quantity)*3600,
    GREATEST(ifnull(TIMESTAMPDIFF(SECOND, badges.in2, badges.out2),0)+ifnull(TIMESTAMPDIFF(SECOND, badges.in1, badges.out1),0)-resthours*3600,0),
    IF(ifnull(TIMESTAMPDIFF(SECOND, badges.in2, badges.out2),0)+ifnull(TIMESTAMPDIFF(SECOND, badges.in1, badges.out1),0)>=ifnull(planning_modif.quantity, planning_users.quantity) AND ifnull(TIMESTAMPDIFF(SECOND, badges.in2, badges.out2),0)+ifnull(TIMESTAMPDIFF(SECOND, badges.in1, badges.out1),0)<= (ifnull(planning_modif.quantity, planning_users.quantity)*3600+resthours*3600),
    ifnull(planning_modif.quantity, planning_users.quantity)*3600,
    ifnull(planning_modif.quantity, ifnull(TIMESTAMPDIFF(SECOND, badges.in2, badges.out2),0)+ifnull(TIMESTAMPDIFF(SECOND, badges.in1, badges.out1),0)-planning_users.quantity)*3600))/3600
    -  ifnull(planning_modif.quantity, planning_users.quantity)  <> 0
    AND TO_DAYS(badges.date) < TO_DAYS(NOW())
    GROUP BY technicien;';

    //echo $req;
    return $this->db->getAll($req);
  }

}
