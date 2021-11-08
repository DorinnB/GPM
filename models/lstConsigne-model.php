<?php
class ConsigneModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllConsigne() {
        return $this->db->getAll('SELECT * FROM consigne_types where consigne_type_actif=1 ORDER BY consigne_type ASC;');
    }
}
