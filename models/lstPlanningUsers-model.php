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
      WHERE dateplanned >= '.$this->db->quote($getBegin).' AND dateplanned <= '.$this->db->quote($getEnd).';';

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
        AND datemodif >= '.$this->db->quote($getBegin).' AND datemodif <= '.$this->db->quote($getEnd).'
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
        AND datemodif >= '.$this->db->quote($getBegin).' AND datemodif <= '.$this->db->quote($getEnd).'
        ORDER BY id_planning_modif ASC;';
      //echo $req;
        return $this->db->getAll($req);
    }

    public function getAllUsers() {
      $req='SELECT id_technicien, technicien
			FROM `techniciens`
      WHERE id_technicien != 1 AND technicien_actif=1;';

//echo $req;
        return $this->db->getAll($req);
    }

}