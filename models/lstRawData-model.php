<?php
class RawDataModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllRawData() {
      $req='SELECT * FROM rawData WHERE rawData_actif=1 ORDER BY id_rawData=0 DESC, Name ASC;';
        return $this->db->getAll($req);
    }


}
