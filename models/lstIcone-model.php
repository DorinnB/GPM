<?php
class IconeModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllIcone() {
        return $this->db->getAll('SELECT * FROM icones ORDER BY icone_name;');
    }

    public function updateIcone($idMachine, $idIcone) {
      $reqUpdate='UPDATE machine_forecasts SET id_icone_machine_forecast='.$idIcone.' WHERE id_machine_forecast='.$idMachine;
      //echo $reqUpdate;

      $result = $this->db->execute($reqUpdate);

      $newStatut = $this->db->getOne('SELECT * FROM machine_forecasts LEFT JOIN icones ON icones.id_icone=machine_forecasts.id_icone_machine_forecast WHERE id_machine_forecast='.$idMachine);
      $maReponse = array('req'=> $reqUpdate, 'id_machine' => $newStatut['id_machine_forecast'], 'icone_file' => $newStatut['icone_file']);
      echo json_encode($maReponse);
    }

    public function updatePriorite($idMachine, $idIcone) {
      $reqUpdate='UPDATE machine_forecasts SET prio_machine_forecast='.$idIcone.' WHERE id_machine_forecast='.$idMachine;
      //echo $reqUpdate;

      $result = $this->db->execute($reqUpdate);

      $newStatut = $this->db->getOne('SELECT * FROM machine_forecasts WHERE id_machine_forecast='.$idMachine);
      $maReponse = array('req'=> $reqUpdate, 'id_machine' => $newStatut['id_machine_forecast'], 'id_icone' => $newStatut['prio_machine_forecast']);
      echo json_encode($maReponse);

    }

    public function updateCommentaire($idMachine, $commentaire) {
      $reqUpdate='UPDATE machine_forecasts SET texte_machine_forecast='.$this->db->quote($commentaire).' WHERE id_machine_forecast='.$idMachine;
      //echo $reqUpdate;

      $result = $this->db->query($reqUpdate);

      $maReponse = array('req'=> $reqUpdate);
      echo json_encode($maReponse);

    }
}
