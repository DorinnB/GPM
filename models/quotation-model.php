<?php
class QUOTATION
{

  protected $db;


  public function __construct($db)
  {
    $this->db = $db;
  }

  public function __set($property,$value) {
    $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
  }



  public function getAllQuotation(){
    $req='SELECT id_quotation, creation_date, title, rfq, ver, id_preparer, t1.technicien as preparer, id_checker, t2.technicien as checker, quotation_date, customer, id_contact, lang, currency, quotationlist
    FROM quotation
    LEFT JOIN techniciens t1 ON t1.id_technicien=quotation.id_preparer
    LEFT JOIN techniciens t2 ON t2.id_technicien=quotation.id_checker
    ;';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getQuotationList($id_quotation){
    $req='SELECT id_quotation, creation_date, title, rfq, ver, id_preparer, t1.technicien as preparer, id_checker, t2.technicien as checker, quotation_date, customer, id_contact, lang, currency, quotationlist,
    entreprises.entreprise, entreprises.VAT, entreprises.MRSASRef, entreprises.billing_rue1, entreprises.billing_rue2, entreprises.billing_ville, entreprises.billing_pays
    FROM quotation
    LEFT JOIN techniciens t1 ON t1.id_technicien=quotation.id_preparer
    LEFT JOIN techniciens t2 ON t2.id_technicien=quotation.id_checker
    LEFT JOIN entreprises ON entreprises.id_entreprise=quotation.customer
    WHERE quotation.id_quotation='.$id_quotation.';';
    //    echo $req;
    return $this->db->getOne($req);
  }

  public function updateQuotation(){
    $reqUpdate='INSERT INTO quotation
    (id_quotation, title, rfq, ver, id_preparer, id_checker, quotation_date, customer, id_contact, lang, currency, quotationlist)
    VALUES
    ('.$this->id.', '.$this->titre.', '.$this->RFQ.', '.$this->ver.', '.$this->id_preparer.', '.$this->id_checker.', '.$this->date.', '.$this->ref_customer.', '.$this->id_contact.', '.$this->lang.', '.$this->currency.', '.$this->quotationlist.')
    ON DUPLICATE KEY UPDATE
    title='.$this->titre.', rfq='.$this->RFQ.', ver='.$this->ver.', id_preparer='.$this->id_preparer.', id_checker='.$this->id_checker.', quotation_date='.$this->date.', customer='.$this->ref_customer.', id_contact='.$this->id_contact.', lang='.$this->lang.', currency='.$this->currency.', quotationlist='.$this->quotationlist.'
    ;';

    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_quotation' => $this->db->lastId());
    echo json_encode($maReponse);
  }

}
