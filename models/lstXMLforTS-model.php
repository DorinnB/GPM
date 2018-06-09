<?php
class XMLforTSModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllXMLforTS($id_test_type) {
      $req='SELECT * FROM xmlforts where id_test_type = '.$this->db->quote($id_test_type).';';
        return $this->db->getAll($req);
    }

}
