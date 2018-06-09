<?php
class TestTypeModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllTestType() {
        return $this->db->getAll('SELECT *
          FROM test_type where test_type_actif=1
          ORDER BY CASE
            WHEN test_type_abbr="Loa" THEN 0
            WHEN test_type_abbr="Str" THEN 1
            WHEN LEFT(test_type_abbr,1) = "." THEN 4 ELSE 3 
            END,
        test_type_abbr
          ;');
    }
}
