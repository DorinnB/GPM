<?php
class ContactModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllContact() {
      $req='SELECT * FROM contacts where contact_actif=1 ORDER BY nom;';
        return $this->db->getAll($req);
    }

    public function getAllref_customer() {
      $req='SELECT id_entreprise, entreprise, entreprise_abbr FROM entreprises where entreprise_actif=1 ORDER BY id_entreprise;';
        return $this->db->getAll($req);
    }

    public function getContact($ref_customer) {
      $req='SELECT * FROM contacts where ref_customer = '.$ref_customer.' AND contact_actif=1 ORDER BY nom;';
        return $this->db->getAll($req);
    }

    public function getClient($ref_customer) {
      $req='SELECT * FROM entreprises
      where id_entreprise = '.$ref_customer;

        return $this->db->getOne($req);
    }

}
