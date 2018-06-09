<?php
class AnnexeIQCModel
{

  protected $db;
  private $id;
  private $_dim1;
  private $_dim2;
  private $_dim3;
  private $_marquage;
  private $_surface;
  private $_grenaillage;
  private $_revetement;
  private $_protection;
  private $_autre;
  private $_observation;
  private $_id_tech;
  private $_comments;
  private $_tbljob_commentaire;
  private $_tbljob_commentaire_qualite;


  public function __construct($db)
  {
    $this->db = $db;
  }





  public function __set($property,$value) {
    $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
  }


  public function getIQC($idEp) {

    $req = 'SELECT eprouvettes.id_eprouvette,
    master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette,
    dim1, dim2, dim3, marquage, surface, grenaillage, revetement, protection, autre,
    nominal_1, tolerance_plus_1, tolerance_moins_1,
    nominal_2, tolerance_plus_2, tolerance_moins_2,
    nominal_3, tolerance_plus_3, tolerance_moins_3,
    c_checked, d_checked, flag_qualite, type,
    c_commentaire, d_commentaire, q_commentaire, date_IQC, id_user


    FROM eprouvettes
    LEFT JOIN annexe_IQC ON annexe_IQC.id_annexe_iqc=eprouvettes.id_eprouvette
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg

    WHERE eprouvettes.id_eprouvette='.$idEp;
    //echo $req;
    return $this->db->getOne($req);
  }

  public function getAllIQC($idjob) {

    $req = 'SELECT eprouvettes.id_eprouvette, master_eprouvettes.id_master_eprouvette,
    master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette,
    dim1, dim2, dim3, marquage, surface, grenaillage, revetement, protection, autre,
    nominal_1, tolerance_plus_1, tolerance_moins_1,
    nominal_2, tolerance_plus_2, tolerance_moins_2,
    nominal_3, tolerance_plus_3, tolerance_moins_3,
    c_checked, d_checked, flag_qualite, type, dessin_types.id_dessin_type,
    c_commentaire, d_commentaire, q_commentaire, date_IQC, id_tech, technicien,
    master_eprouvette_inOut_A
    FROM eprouvettes
    LEFT JOIN annexe_IQC ON annexe_IQC.id_annexe_iqc=eprouvettes.id_eprouvette
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg
LEFT JOIN dessin_types ON dessin_types.id_dessin_type=dessins.id_dessin_type
    LEFT JOIN techniciens ON techniciens.id_technicien=annexe_IQC.id_tech

    WHERE eprouvettes.id_job='.$idjob.'
    AND eprouvettes.eprouvette_actif=1
    AND master_eprouvettes.master_eprouvette_actif=1
    ORDER by master_eprouvettes.id_master_eprouvette ASC';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getGlobalIQC($idDessin) {

    $req = 'SELECT
    nominal_1, tolerance_plus_1, tolerance_moins_1,
    nominal_2, tolerance_plus_2, tolerance_moins_2,
    nominal_3, tolerance_plus_3, tolerance_moins_3,
    type
    FROM dessins

    WHERE id_dessin='.$idDessin;
    //echo $req;
    return $this->db->getOne($req);
  }




  public function getIQCid($idjob, $prefixe, $id) {

    $wherePrefixe=($prefixe=="")?' IS NULL':'='.$this->db->quote($prefixe);

    $req='SELECT id_eprouvette
    FROM eprouvettes
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    WHERE master_eprouvettes.prefixe'.$wherePrefixe.'
    AND master_eprouvettes.nom_eprouvette='.$this->db->quote($id).'
    AND id_job='.$this->db->quote($idjob).'
    AND eprouvette_actif=1
    ';

    return $this->db->isOne($req);
  }



  public function inserupdateIQC($idIQC){


    $reqSelect='SELECT master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, dim1, dim2, dim3
    FROM annexe_IQC
    LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=annexe_IQC.id_annexe_IQC
    LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
    WHERE id_annexe_IQC='.$idIQC;

    $isOneIQC = $this->db->isOne($reqSelect);


    if ($isOneIQC) {

      echo
      (($isOneIQC['prefixe']=="")?$isOneIQC['nom_eprouvette']:$isOneIQC['prefixe']."-".$isOneIQC['nom_eprouvette'])
      .' updated : '.$isOneIQC['dim1'].'->'.$this->dim1.' '.$isOneIQC['dim2'].'->'.$this->dim2.' '.$isOneIQC['dim3'].'->'.$this->dim3.'
      ';

      $reqUpdate='UPDATE annexe_IQC
      LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=annexe_IQC.id_annexe_iqc
      SET
      dim1='.$this->dim1.',
      dim2='.$this->dim2.',
      dim3='.$this->dim3.',
      marquage='.$this->marquage.',
      surface='.$this->surface.',
      grenaillage='.$this->grenaillage.',
      revetement='.$this->revetement.',
      protection='.$this->protection.',
      autre='.$this->autre.',
      d_commentaire='.$this->observation.',
      date_IQC='.$this->date_IQC.',
      eprouvette_inOut_A='.$this->date_IQC.',
      eprouvette_inOut_B='.$this->date_IQC.',
      id_tech = '.$this->id_tech.',
      d_checked=-'.$this->id_tech.'

      WHERE id_annexe_iqc = '.$idIQC;

      //  echo $reqUpdate.'<br/><br/>';;

      $result = $this->db->query($reqUpdate);
    }
    else{
      $reqInsert='INSERT INTO annexe_IQC (
        id_annexe_iqc,
        dim1,
        dim2,
        dim3,
        marquage,
        surface,
        grenaillage,
        revetement,
        protection,
        autre,
        date_IQC,
        id_tech
      )
      VALUES (
        '.$idIQC.',
        '.$this->dim1.',
        '.$this->dim2.',
        '.$this->dim3.',
        '.$this->marquage.',
        '.$this->surface.',
        '.$this->grenaillage.',
        '.$this->revetement.',
        '.$this->protection.',
        '.$this->autre.',
        '.$this->date_IQC.',
        '.$this->id_tech.'
        ) ';
        //echo $reqInsert.'<br/><br/>';
        $result = $this->db->execute($reqInsert);

        $reqUpdate='UPDATE eprouvettes
        SET
        d_commentaire='.$this->observation.',
        eprouvette_inOut_A = '.$this->date_IQC.',
        eprouvette_inOut_B = '.$this->date_IQC.'
        WHERE id_eprouvette = '.$idIQC;

        //  echo $reqUpdate.'<br/><br/>';;

        $result = $this->db->query($reqUpdate);

      }
    }





    public function updateComments($idtbljob) { //comments est un champ libre de la table eprouvettes qui, dans ce cas, sert aux equipements
      $req='UPDATE tbljobs
      SET
      comments='.$this->comments.',
      tbljob_commentaire='.$this->tbljob_commentaire.',
      tbljob_commentaire_qualite='.$this->tbljob_commentaire_qualite.'
      WHERE id_tbljob='.$idtbljob;
      //echo $req;
      return $this->db->query($req);
    }


  }
