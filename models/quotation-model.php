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
    $req='SELECT * from quotation    ';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getQuotationList($id_quotation){
    $req='SELECT *
    FROM quotation
    WHERE quotation.id_quotation='.$id_quotation.';';
    //    echo $req;
    return $this->db->getOne($req);
  }
  public function getAllQuotationList($id_quotation){
    $req='SELECT *
    FROM quotation
    LEFT JOIN quotationLists ON quotationLists.id_quotation=quotation.id_quotation
    WHERE quotation.id_quotation='.$id_quotation.';';
    //    echo $req;
    return $this->db->getAll($req);
  }


  public function updateQuotation(){
    $reqUpdate='
    INSERT INTO quotation
    (id_quotation, title, rfq, rev, id_preparer, id_checker, quotation_date, customer, id_contact, lang, currency, quotationlist)
    VALUES
    ('.$this->id.', '.$this->titre.', '.$this->RFQ.', '.$this->rev.', '.$this->id_preparer.', '.$this->id_checker.', '.$this->date.', '.$this->ref_customer.', '.$this->id_contact.', '.$this->lang.', '.$this->currency.', '.$this->quotationlist.')
    ON DUPLICATE KEY UPDATE
    title='.$this->titre.', rfq='.$this->RFQ.', rev='.$this->rev.', id_preparer='.$this->id_preparer.', id_checker='.$this->id_checker.', quotation_date='.$this->date.', customer='.$this->ref_customer.', id_contact='.$this->id_contact.', lang='.$this->lang.', currency='.$this->currency.', quotationlist='.$this->quotationlist.'
    ;';

    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_quotation' => $this->db->lastId());
    echo json_encode($maReponse);
  }

}
