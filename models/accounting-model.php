<?php
class AccountingModel
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function __set($property,$value) {
    if (is_numeric($value)){
      $this->$property = $value;
    }
    else {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
    }
  }



  public function getAllAccounting($limit=1000) {

    $filtreLimit=(is_numeric($limit))?$limit:$this->db->quote($limit);

    $req='SELECT info_jobs.customer, info_jobs.job, info_jobs.order_val, info_jobs.order_est, info_jobs.invoice_currency,
    CASE
    WHEN SUM(invoices.inv_subc) + SUM(invoices.inv_mrsas) > 0 THEN "UBR"
    WHEN info_jobs.invoice_type = 1 THEN "PART."
    WHEN info_jobs.invoice_type = 2 THEN "INV."
    ELSE "Not"
    END AS invoice_type,
    CAST((SUM(payables.HT)/count(distinct invoices.id_invoice) - MAX(invoices.savePayables)) AS DECIMAL(10,2))  AS UBRSubC,
    MAX(ubr.UBR) AS UBR,
    SUM(payables.HT)/count(distinct invoices.id_invoice) + MAX(ubr.UBR) as totalUBR,
    SUM(invoices.inv_mrsas) AS invMRSAS,
    SUM(invoices.inv_subc) AS invSubC,
    GROUP_CONCAT(DISTINCT invoices.inv_number, " ") AS inv_number,
    MAX(invoices.inv_date) AS inv_date,
    MAX(invoices.inv_date) + INTERVAL 30 DAY as dueDate,

    GROUP_CONCAT(DISTINCT USDRate, " ") AS USDRate,

    IF(info_jobs.invoice_currency=1,SUM(invoices.inv_subc), NULL) as invSubCUSD,
    IF(info_jobs.invoice_currency=1,SUM(invoices.inv_mrsas), NULL) as invMRSASUSD,
    IF(info_jobs.invoice_currency=1,SUM(invoices.inv_subc) + SUM(invoices.inv_mrsas), NULL) as invHTUSD,
    IF(info_jobs.invoice_currency=1,SUM(invoices.inv_tva), NULL) as invTVAUSD,
    IF(info_jobs.invoice_currency=1,SUM(invoices.inv_subc) + SUM(invoices.inv_mrsas) + SUM(invoices.inv_tva), NULL) as invTTCUSD,

    IF(info_jobs.invoice_currency=0,SUM(invoices.inv_subc), NULL) as invSubCEUR,
    IF(info_jobs.invoice_currency=0,SUM(invoices.inv_mrsas), NULL) as invMRSASEUR,
    IF(info_jobs.invoice_currency=0,SUM(invoices.inv_subc) + SUM(invoices.inv_mrsas), NULL) as invHTEUR,
    IF(info_jobs.invoice_currency=0,SUM(invoices.inv_tva), NULL) as invTVAEUR,
    IF(info_jobs.invoice_currency=0,SUM(invoices.inv_subc) + SUM(invoices.inv_mrsas) + SUM(invoices.inv_tva), NULL) as invTTCEUR

    FROM info_jobs
    LEFT JOIN payables ON payables.job=info_jobs.job
    LEFT JOIN payable_lists ON payable_lists.id_payable_list=payables.id_payable_list
    LEFT JOIN ubr ON ubr.id_info_job=info_jobs.id_info_job AND ubr.id_ubr=(SELECT MAX(ubrmax.id_ubr) FROM ubr AS ubrmax WHERE ubrmax.id_info_job=ubr.id_info_job)
    LEFT JOIN invoices ON invoices.inv_job=info_jobs.job


    WHERE payable_lists.ubrable=1

    GROUP BY  info_jobs.id_info_job

    ORDER BY info_jobs.job DESC
    LIMIT '.$filtreLimit.'
    ;';

    //echo $req;
    return $this->db->getAll($req);
  }



}
