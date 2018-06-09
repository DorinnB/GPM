<?php
class HistoModel
{

  protected $db;


  public function __construct($db)
  {
    $this->db = $db;
  }


  public function __set($property,$value) {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
  }

  public function getHistoEprouvette($id) {

    $req = 'SELECT *
    FROM histo_eprouvettes
    WHERE id_eprouvette='.$this->db->quote($id).'
    ORDER by date_action asc';

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getHistoAnnexeIQC($id) {

    $req = 'SELECT *
    FROM histo_annexe_IQC
    WHERE id_annexe_IQC='.$this->db->quote($id).'
    ORDER by date_action asc';

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getHistoTbljobs($id) {

    $req = 'SELECT specification, waveform, tbljob_frequence, name, entrepriseST.entreprise_abbr as entreprise_abbrST, refSubC, DyT_SubC, DyT_expected, DyT_Cust
    FROM histo_tbljobs
    LEFT JOIN rawdata ON rawdata.id_rawData=histo_tbljobs.id_rawData
    LEFT JOIN contacts contactST ON contactST.id_contact=histo_tbljobs.id_contactST
    LEFT JOIN entreprises entrepriseST ON entrepriseST.id_entreprise=contactST.ref_customer

    WHERE id_tbljob='.$this->db->quote($id).'
    ORDER by date_action asc';

    //echo $req;
    return $this->db->getAll($req);
  }

}
