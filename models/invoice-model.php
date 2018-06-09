<?php
class InvoiceModel
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function __set($property,$value) {
    $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
  }

  public function getAllInvoiceList($id_tbljob="null") {

    if ($id_tbljob=="null") {
/*
      $req='SELECT pricinglists.id_pricingList, pricingList, pricingListFR, pricingListUS, prodCode, OpnCode, USD, EURO, pricingList_actif, id_test_type
      FROM `pricinglists`
      INNER JOIN test_type_pricinglists ON test_type_pricinglists.id_pricinglist=pricinglists.id_pricinglist
      WHERE id_test_type=0
      ORDER BY pricinglists.id_pricingList';
  */
      $req='SELECT pricinglists.id_pricingList, pricingList, pricingListFR, pricingListUS, prodCode, OpnCode, USD, EURO, pricingList_actif
      FROM `pricinglists`

      ORDER BY prodCode, OpnCode';
    }
    else {
      $req='SELECT pricinglists.id_pricingList, pricingList, pricingListFR, pricingListUS, prodCode, OpnCode, USD, EURO, pricingList_actif
      FROM `tbljobs`
      INNER JOIN test_type_pricinglists ON test_type_pricinglists.id_test_type=tbljobs.id_type_essai
      INNER JOIN pricinglists ON pricinglists.id_pricingList=test_type_pricinglists.id_pricingList
      WHERE id_tbljob='.$id_tbljob.'
      ORDER BY prodCode, OpnCode';
    }


    return $this->db->getAll($req);
  }

  public function getInvoiceListSplit($id_tbljob) {

    //calcul du nombre d'essai ou du temps d'heures sup (arrondi superieur)
    $req='SELECT
    pricinglists.id_pricingList,
    id_invoiceline,
    id_info_job,
    id_tbljob,
    prodCode,
    OpnCode,
    invoicelines.pricingList,
    qteUser,
    if(type=1,
      SUM(IF((d_checked > 0) OR (n_fichier is not null),1,0)),
      if(type=2,
        sum(
          ceil(
            if(
              if(temps_essais is null,
                if(IF(Cycle_final is null,Cycle_final_temp, cycle_final) >0 AND c_frequence is not null and c_frequence !=0,
                  if(Cycle_STL is null and c_cycle_STL is null,
                    IF(Cycle_final is null,Cycle_final_temp, cycle_final)/eprouvettes.c_frequence/3600,
                    if(Cycle_STL is null,
                      if(IF(Cycle_final is null,Cycle_final_temp, cycle_final)>c_cycle_STL,(c_cycle_STL/c_frequence+(IF(Cycle_final is null,Cycle_final_temp, cycle_final)-c_cycle_STL)/c_frequence_STL)/3600,
                      (IF(Cycle_final is null,Cycle_final_temp, cycle_final)/c_frequence)/3600
                    )
                    ,if(IF(Cycle_final is null,Cycle_final_temp, cycle_final)>cycle_STL,
                      (cycle_STL/c_frequence+(IF(Cycle_final is null,Cycle_final_temp, cycle_final)-cycle_STL)/c_frequence_STL)/3600,
                      (IF(Cycle_final is null,Cycle_final_temp, cycle_final)/c_frequence)/3600
                    )
                  )
                )
                ,
                ""
              )
              ,temps_essais
            )>24,
            if(temps_essais is null,
              if(IF(Cycle_final is null,Cycle_final_temp, cycle_final) >0 AND c_frequence is not null and c_frequence !=0,
                if(Cycle_STL is null and c_cycle_STL is null,
                  IF(Cycle_final is null,Cycle_final_temp, cycle_final)/eprouvettes.c_frequence/3600,
                  if(Cycle_STL is null,
                    if(IF(Cycle_final is null,Cycle_final_temp, cycle_final)>c_cycle_STL,
                      (c_cycle_STL/c_frequence+(IF(Cycle_final is null,Cycle_final_temp, cycle_final)-c_cycle_STL)/c_frequence_STL)/3600,
                      (IF(Cycle_final is null,Cycle_final_temp, cycle_final)/c_frequence)/3600
                    )
                    ,if(IF(Cycle_final is null,Cycle_final_temp, cycle_final)>cycle_STL,
                      (cycle_STL/c_frequence+(IF(Cycle_final is null,Cycle_final_temp, cycle_final)-cycle_STL)/c_frequence_STL)/3600,
                      (IF(Cycle_final is null,Cycle_final_temp, cycle_final)/c_frequence)/3600
                    )
                  )
                ),
                "")
                ,temps_essais
              )-24,
              0
            )
          )
        )
        ,""
      )
    ) as qteGPM,
    priceUnit,
    totalUser


    FROM `invoicelines`
    LEFT JOIN pricinglists ON pricinglists.id_pricingList=invoicelines.id_pricinglist
    LEFT JOIN eprouvettes on eprouvettes.id_job=invoicelines.id_tbljob
LEFT JOIN eprouvettes_temp ON eprouvettes_temp.id_eprouvettes_temp=eprouvettes.id_eprouvette
    LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
    WHERE id_tbljob='.$id_tbljob.'
    AND eprouvette_actif=1
    AND valid=1
    GROUP BY id_invoiceline
    ORDER BY pricinglists.id_pricingList
    ';


    //echo $req;
    return $this->db->getAll($req);
  }

  public function getInvoiceListJob($id_tbljob) {

    $req='SELECT
    pricinglists.id_pricingList,
    id_invoiceline,
    id_info_job,
    id_tbljob,
    prodCode,
    OpnCode,
    invoicelines.pricingList,
    qteUser,

    priceUnit,
    totalUser


    FROM `invoicelines`
    LEFT JOIN pricinglists ON pricinglists.id_pricingList=invoicelines.id_pricinglist
    WHERE id_info_job=(SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->db->quote($id_tbljob).')
    AND id_tbljob IS NULL
    GROUP BY id_invoiceline
    ORDER BY pricinglists.id_pricingList
    ';


    //echo $req;
    return $this->db->getAll($req);
  }

  public function deleteInvoiceLine() {

    $reqDelete = 'DELETE FROM invoicelines
    WHERE id_invoiceline='.$this->id_invoiceLine.';';

    //echo $reqDelete;
    $this->db->execute($reqDelete);
  }

  public function updateInvoiceLine() {

    $reqUpdate = '
    UPDATE invoicelines
    SET  pricingList='.$this->pricingList.',
    qteUser='.$this->qteUser.',
    priceUnit='.$this->priceUnit.'
    WHERE id_invoiceline='.$this->id_invoiceLine.';';

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }

  public function addNewEntry() {

    $req = 'INSERT INTO invoicelines
    (id_pricinglist, pricingList, qteUser, priceUnit,  id_info_job, id_tbljob)
    VALUES ('.$this->id_pricingList.', '.$this->pricingList.', '.$this->qteUser.', '.$this->priceUnit.', '.$this->id_info_job.', '.$this->id_tbljob.');';

    //echo $req;
    $this->db->execute($req);
  }

  public function updateInvoiceComments($invoice_lang,$invoice_currency,$comments, $id_tbljob) {

    $reqUpdate = 'UPDATE info_jobs
    SET
    invoice_lang='.$this->db->quote($invoice_lang).',
    invoice_currency='.$this->db->quote($invoice_currency).',
    invoice_commentaire='.$this->db->quote($comments).'
    WHERE id_info_job=(SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->db->quote($id_tbljob).');';

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }

  public function getAllPayablesJob($id_tbljob) {

    $req='SELECT *
    FROM payables
    WHERE job=(SELECT job FROM tbljobs LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job WHERE id_tbljob='.$this->db->quote($id_tbljob).');';

    return $this->db->getAll($req);
  }

  public function getAllInvoiceJob() {

    $req='SELECT max(tbljobs.id_tbljob) as id_tbljob
    FROM `tbljobs`
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    LEFT JOIN invoicelines ON invoicelines.id_tbljob=tbljobs.id_tbljob
    WHERE etape < 95
    AND tbljob_actif=1
    GROUP BY tbljobs.id_info_job';

    return $this->db->getAll($req);
  }
}
