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
      $req='SELECT badge
      FROM techniciens
      WHERE id_technicien='.$_COOKIE['id_user'].';';

      $return = $this->db->getOne($req);

      return $return['badge'];
    }
    else {
      return 0;
    }
  }

  public function getClockState() {

    $req='SELECT `id_badge`, `id_user`, `in1`, `out1`, `in2`, `out2`, `validation`, `comments`, `id_validator`,
    IF(in1 IS NULL OR (in2 IS NULL AND out1 IS NOT NULL),1,0) AS unclocked,
    IF(in1 IS NULL OR ((DATE_ADD(NOW(), INTERVAL -5 HOUR) > in1) AND out2 IS NOT NULL),1,0) AS unclocked2
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


}
