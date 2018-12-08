<?php
class OutillageModel
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

  public function getAllOutillage() {
    $req='SELECT * FROM outillages where outillage_actif=1 ORDER BY outillage;';
    return $this->db->getAll($req);
  }

  public function getLastSeen($id) {
    $req='SELECT machine
    FROM postes
    LEFT JOIN machines ON machines.id_machine=postes.id_machine
    WHERE id_outillage_top='.$this->db->quote($id).' OR id_outillage_bot='.$this->db->quote($id).'
    ORDER BY id_poste DESC
    LIMIT 1;';
    return $this->db->getOne($req);
  }


      public function getAllOutillageLocation() {
        $req='SELECT DISTINCT(outillage), outillages.id_outillage, machine, outillage_type, comments, matiere
        FROM outillages
        LEFT JOIN outillage_types ON outillage_types.id_outillage_type=outillages.id_outillage_type
        LEFT JOIN postes ON postes.id_outillage_top=outillages.id_outillage OR postes.id_outillage_bot=outillages.id_outillage
        LEFT JOIN machines ON machines.id_machine=postes.id_machine

        WHERE id_poste=(
          SELECT MAX( p1.id_poste)
          FROM postes p1
          WHERE p1.id_outillage_top = outillages.id_outillage OR p1.id_outillage_bot = outillages.id_outillage
        )
        AND outillage_actif=1
        ORDER BY outillage;';

        return $this->db->getAll($req);
      }

      public function updateOutillage(){

        $reqUpdate='UPDATE outillages
        SET comments = '.$this->comments.'
        WHERE id_outillage = '.$this->id;

        echo $reqUpdate;
        $this->db->query($reqUpdate);
      }
}
