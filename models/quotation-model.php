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
    $req='SELECT id_quotation, creation_date, title, rfq, ver, id_preparer, t1.technicien as preparer, id_checker, t2.technicien as checker, quotation_date, customer, id_contact, lang, currency, quotationlist, endComments, mrsasComments
    FROM quotation
    LEFT JOIN techniciens t1 ON t1.id_technicien=abs(quotation.id_preparer)
    LEFT JOIN techniciens t2 ON t2.id_technicien=abs(quotation.id_checker)
    ;';
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getQuotationList($id_quotation){
    $req='SELECT id_quotation, creation_date, title, rfq, ver, id_preparer, t1.technicien as preparer, id_checker, t2.technicien as checker, quotation_date, customer, quotation.id_contact, lang, currency, quotationlist, endComments, mrsasComments,
    contacts.prenom, contacts.nom, contacts.rue1, contacts.rue2, contacts.ville, contacts.pays,
    entreprises.entreprise, entreprises.VAT, entreprises.MRSASRef, entreprises.billing_rue1, entreprises.billing_rue2, entreprises.billing_ville, entreprises.billing_pays
    FROM quotation
    LEFT JOIN techniciens t1 ON t1.id_technicien=abs(quotation.id_preparer)
    LEFT JOIN techniciens t2 ON t2.id_technicien=abs(quotation.id_checker)
    LEFT JOIN entreprises ON entreprises.id_entreprise=quotation.customer
    LEFT JOIN contacts ON contacts.id_contact=quotation.id_contact
    WHERE quotation.id_quotation='.$id_quotation.';';
    //    echo $req;
    return $this->db->getOne($req);
  }

  public function updateQuotation(){
    $reqUpdate='INSERT INTO quotation
    (id_quotation, title, rfq, ver, id_preparer, id_checker, quotation_date, customer, id_contact, lang, currency, quotationlist, endComments, mrsasComments)
    VALUES
    ('.$this->id.', '.$this->titre.', '.$this->RFQ.', '.$this->ver.', '.$this->id_preparer.', '.$this->id_checker.', '.$this->quotation_date.', '.$this->ref_customer.', '.$this->id_contact.', '.$this->lang.', '.$this->currency.', '.$this->quotationlist.', '.$this->endComments.', '.$this->mrsasComments.')
    ON DUPLICATE KEY UPDATE
    title='.$this->titre.', rfq='.$this->RFQ.', ver='.$this->ver.', id_preparer='.$this->id_preparer.', id_checker='.$this->id_checker.', quotation_date='.$this->quotation_date.', customer='.$this->ref_customer.', id_contact='.$this->id_contact.', lang='.$this->lang.', currency='.$this->currency.', quotationlist='.$this->quotationlist.', endComments='.$this->endComments.', mrsasComments='.$this->mrsasComments.'
    ;';

    $result = $this->db->query($reqUpdate);

    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_quotation' => $this->db->lastId());
    echo json_encode($maReponse);
  }

}
