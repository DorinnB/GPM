<?php
class PricingModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllPricing() {
        return $this->db->getAll('SELECT * FROM pricing where pricing_actif=1 ORDER BY ref_pricing="Std" DESC , ref_pricing ASC;');
    }
}
