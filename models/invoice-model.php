<?php
class InvoiceModel
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

  public function getAllInvoiceList($id_tbljob="null") {

    if ($id_tbljob=="null") {

      $req='SELECT pricinglists.id_pricingList, pricingList, pricingListFR, pricingListUS, pricinglists.prodCode, pricinglists.OpnCode, type, USD, EURO, pricingList_actif
      FROM `pricinglists`

      ORDER BY pricinglists.prodCode, pricinglists.OpnCode';
    }
    else {
      $req='SELECT pricinglists.id_pricingList, pricingList, pricingListFR, pricingListUS, pricinglists.prodCode, pricinglists.OpnCode, type, USD, EURO, pricingList_actif
      FROM `tbljobs`
      INNER JOIN test_type_pricinglists ON test_type_pricinglists.id_test_type=tbljobs.id_type_essai
      INNER JOIN pricinglists ON pricinglists.id_pricingList=test_type_pricinglists.id_pricingList
      WHERE id_tbljob='.$id_tbljob.'
      ORDER BY pricinglists.prodCode, pricinglists.OpnCode';
    }


    return $this->db->getAll($req);
  }

  public function getInvoiceListSplit($id_tbljob) {

    //calcul du nombre d'essai ou du temps d'heures sup (arrondi superieur)
    $req='SELECT
    id_pricingList,
    id_invoiceline,
    id_info_job,
    id_tbljob,
    prodCode,
    OpnCode,
    type,
    pricingList,
    qteUser,
    if(
      type=1,
      SUM(IF((d_checked > 0) OR (n_fichier is not null),1,0)),
      if(
        type=2,
        sum(
          ceil(
            if(
              if(
                temps_essais is null,
                if(
                  IF(Cycle_final is null,Cycle_final_temp, cycle_final) >0 AND c_frequence is not null and c_frequence !=0,
                  if(
                    Cycle_STL is null and c_cycle_STL is null,
                    IF(Cycle_final is null,Cycle_final_temp, cycle_final)/eprouvettes.c_frequence/3600,
                    if(
                      Cycle_STL is null,
                      if(IF(Cycle_final is null,Cycle_final_temp, cycle_final)>c_cycle_STL,(c_cycle_STL/c_frequence+(IF(Cycle_final is null,Cycle_final_temp, cycle_final)-c_cycle_STL)/c_frequence_STL)/3600,
                      (IF(Cycle_final is null,Cycle_final_temp, cycle_final)/c_frequence)/3600
                    )
                    ,if(
                      IF(Cycle_final is null,Cycle_final_temp, cycle_final)>cycle_STL,
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
            if(
              temps_essais is null,
              if(
                IF(Cycle_final is null,Cycle_final_temp, cycle_final) >0 AND c_frequence is not null and c_frequence !=0,
                if(
                  Cycle_STL is null and c_cycle_STL is null,
                  IF(Cycle_final is null,Cycle_final_temp, cycle_final)/eprouvettes.c_frequence/3600,
                  if(
                    Cycle_STL is null,
                    if(
                      IF(Cycle_final is null,Cycle_final_temp, cycle_final)>c_cycle_STL,
                      (c_cycle_STL/c_frequence+(IF(Cycle_final is null,Cycle_final_temp, cycle_final)-c_cycle_STL)/c_frequence_STL)/3600,
                      (IF(Cycle_final is null,Cycle_final_temp, cycle_final)/c_frequence)/3600
                    ),
                    if(
                      IF(Cycle_final is null,Cycle_final_temp, cycle_final)>cycle_STL,
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
    LEFT JOIN eprouvettes on eprouvettes.id_job=invoicelines.id_tbljob
    LEFT JOIN eprouvettes_temp ON eprouvettes_temp.id_eprouvettes_temp=eprouvettes.id_eprouvette
    LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
    WHERE id_tbljob='.$id_tbljob.'
    AND eprouvette_actif=1
    AND valid!=0
    GROUP BY id_invoiceline
    ORDER BY id_pricingList
    ';


    //echo $req;
    return $this->db->getAll($req);
  }

  public function getInvoiceListJob($id_tbljob) {

    $req='SELECT
    id_pricingList,
    id_invoiceline,
    id_info_job,
    id_tbljob,
    prodCode,
    OpnCode,
    type,
    pricingList,
    qteUser,

    priceUnit,
    totalUser


    FROM `invoicelines`
    WHERE id_info_job=(SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->db->quote($id_tbljob).')
    AND id_tbljob IS NULL
    GROUP BY id_invoiceline
    ORDER BY id_pricingList
    ';

    return $this->db->getAll($req);
  }


  public function getInvoiceTotal($customer) {

    $req='SELECT
    id_info_job, SUM(IF(st=1,prix,0)) AS invSubC, SUM(IF(st=0,prix,0)) AS invMetcut

    FROM     (
      SELECT
      ST, info_jobs.id_info_job, job,
      IFNULL(qteUser,IF(
        type=1,
        SUM(IF((d_checked > 0) OR (n_fichier IS NOT NULL),1,0)),
        IF(
          type=2,
          SUM(
            ceil(
              IF(
                IF(
                  temps_essais IS NULL,
                  IF(
                    IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final) >0 AND c_frequence IS NOT NULL AND c_frequence !=0,
                    IF(
                      Cycle_STL IS NULL AND c_cycle_STL IS NULL,
                      IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)/eprouvettes.c_frequence/3600,
                      IF(
                        Cycle_STL IS NULL,
                        IF(IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)>c_cycle_STL,(c_cycle_STL/c_frequence+(IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)-c_cycle_STL)/c_frequence_STL)/3600,
                        (IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)/c_frequence)/3600
                      )
                      ,IF(
                        IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)>cycle_STL,
                        (cycle_STL/c_frequence+(IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)-cycle_STL)/c_frequence_STL)/3600,
                        (IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)/c_frequence)/3600
                      )
                    )
                  )
                  ,
                  ""
                )
                ,temps_essais
              )>24,
              IF(
                temps_essais IS NULL,
                IF(
                  IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final) >0 AND c_frequence IS NOT NULL AND c_frequence !=0,
                  IF(
                    Cycle_STL IS NULL AND c_cycle_STL IS NULL,
                    IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)/eprouvettes.c_frequence/3600,
                    IF(
                      Cycle_STL IS NULL,
                      IF(
                        IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)>c_cycle_STL,
                        (c_cycle_STL/c_frequence+(IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)-c_cycle_STL)/c_frequence_STL)/3600,
                        (IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)/c_frequence)/3600
                      ),
                      IF(
                        IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)>cycle_STL,
                        (cycle_STL/c_frequence+(IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)-cycle_STL)/c_frequence_STL)/3600,
                        (IF(Cycle_final IS NULL,Cycle_final_temp, cycle_final)/c_frequence)/3600
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
      ))*priceUnit AS prix

      FROM info_jobs
      LEFT JOIN tbljobs on tbljobs.id_info_job=info_jobs.id_info_job
      LEFT JOIN invoicelines invS on invs.id_tbljob=tbljobs.id_tbljob
      LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
      LEFT JOIN eprouvettes on eprouvettes.id_job=invS.id_tbljob
      LEFT JOIN eprouvettes_temp ON eprouvettes_temp.id_eprouvettes_temp=eprouvettes.id_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette

      WHERE eprouvettes.eprouvette_actif=1

      AND tbljobs.tbljob_actif=1
      AND invoice_type!=2
      AND customer='.$this->db->quote($customer).'
      GROUP BY info_jobs.id_info_job, ST, id_invoiceline
    ) AS test
    GROUP BY id_info_job;';

    //echo $req;
    $invSplit = $this->db->getAll($req);


    $req='SELECT info_jobs.id_info_job, SUM(qteUser * priceUnit) AS invSubC
    FROM invoicelines
    LEFT JOIN info_jobs ON (info_jobs.id_info_job=invoicelines.id_info_job AND invoicelines.id_tbljob IS NULL)
    WHERE invoice_type!=2
    AND id_tbljob IS NULL
    AND customer='.$this->db->quote($customer).'
    GROUP BY info_jobs.id_info_job;
    ';

    $invJob = $this->db->getAll($req);

    $invPO = array();
    foreach ($invSplit as $key => $value) {
      $invPO[$value['id_info_job']]['invSubC']=isset($value['invSubC'])?$value['invSubC']:0;
      $invPO[$value['id_info_job']]['invMetcut']=isset($value['invMetcut'])?$value['invMetcut']:0;
    }
    unset($invSplit);

    foreach ($invJob as $key => $value) {
      if (isset($invPO[$value['id_info_job']]['invSubC'])) {
        $invPO[$value['id_info_job']]['invSubC']+=$value['invSubC'];
      }
      else {
        $invPO[$value['id_info_job']]['invSubC']=$value['invSubC'];
      }
    }
    unset($invJob);

    return $invPO;

  }

  public function getAllInvoiceRecorded($id_tbljob) {

    $req='SELECT inv_number, inv_mrsas, inv_subc, inv_date, invoice_final
    FROM invoices
    WHERE inv_job=(
      SELECT job
      FROM tbljobs
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      WHERE id_tbljob='.$this->db->quote($id_tbljob).'
    )
    ORDER BY inv_number ASC;';


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
    (id_pricinglist, pricingList, qteUser, priceUnit, prodCode, OpnCode, type,  id_info_job, id_tbljob)
    VALUES ('.$this->id_pricingList.', '.$this->pricingList.', '.$this->qteUser.', '.$this->priceUnit.', '.$this->prodCode.', '.$this->OpnCode.', '.$this->type.', '.$this->id_info_job.', '.$this->id_tbljob.');';

    //echo $req;
    $this->db->execute($req);
  }

  public function updateInvoiceComments() {

    $reqUpdate = 'UPDATE info_jobs
    SET
    order_val='.$this->order_val.',
    order_est='.$this->order_est.',
    order_est_subc='.$this->order_est_subc.',
    invoice_lang='.$this->invoice_lang.',
    invoice_currency='.$this->invoice_currency.',
    invoice_commentaire='.$this->invoice_commentaire.'
    WHERE id_info_job=(SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->id_tbljob.');';

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }

  public function getAllPayablesJob($id_tbljob) {

    $req='SELECT *
    FROM payables
    LEFT JOIN payable_lists ON payable_lists.id_payable_list=payables.id_payable_list
    WHERE payable_lists.ubrable=1
    AND job=(
      SELECT job
      FROM tbljobs
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      WHERE id_tbljob='.$this->db->quote($id_tbljob).'
    )
    ;';

    return $this->db->getAll($req);
  }

  public function getAllInvoiceJob() {

    $req='SELECT max(tbljobs.id_tbljob) as id_tbljob
    FROM `tbljobs`
    LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
    LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
    LEFT JOIN invoicelines ON invoicelines.id_tbljob=tbljobs.id_tbljob
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
    WHERE etape < 95 AND info_jobs.invoice_type < 2
    AND tbljob_actif=1
    GROUP BY tbljobs.id_info_job';

    return $this->db->getAll($req);
  }

  public function updateApplied() {

    $reqUpdate = '
    UPDATE payables
    SET  applied='.$this->applied.'
    WHERE id_payable='.$this->id_payable.';';

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }

  public function getAllInvoice($limit=1000) {

    $filtreLimit=(is_numeric($limit))?$limit:$this->db->quote($limit);

    $req='SELECT info_jobs.customer, info_jobs.job, info_jobs.order_val, info_jobs.order_est, info_jobs.invoice_currency,
    CASE
    WHEN (invoices.inv_subc) + (invoices.inv_mrsas) > 0 THEN "UBR"
    WHEN info_jobs.invoice_type = 1 THEN "PART."
    WHEN info_jobs.invoice_type = 2 THEN "INV."
    ELSE "Not"
    END AS invoice_type,

    invoices.inv_mrsas AS invMRSAS,
    invoices.inv_subc AS invSubC,
    invoices.inv_number AS inv_number,
    invoices.inv_date AS inv_date,
    invoices.inv_date + INTERVAL 30 DAY as dueDate,

    USDRate AS USDRate,

    IF(info_jobs.invoice_currency=1,invoices.inv_subc, NULL) as invSubCUSD,
    IF(info_jobs.invoice_currency=1,invoices.inv_mrsas, NULL) as invMRSASUSD,
    IF(info_jobs.invoice_currency=1,invoices.inv_subc + invoices.inv_mrsas, NULL) as invHTUSD,
    IF(info_jobs.invoice_currency=1,invoices.inv_tva, NULL) as invTVAUSD,
    IF(info_jobs.invoice_currency=1,invoices.inv_subc + invoices.inv_mrsas + invoices.inv_tva, NULL) as invTTCUSD,

    IF(info_jobs.invoice_currency=0,invoices.inv_subc, NULL) as invSubCEUR,
    IF(info_jobs.invoice_currency=0,invoices.inv_mrsas, NULL) as invMRSASEUR,
    IF(info_jobs.invoice_currency=0,invoices.inv_subc + invoices.inv_mrsas, NULL) as invHTEUR,
    IF(info_jobs.invoice_currency=0,invoices.inv_tva, NULL) as invTVAEUR,
    IF(info_jobs.invoice_currency=0,invoices.inv_subc + invoices.inv_mrsas + invoices.inv_tva, NULL) as invTTCEUR

    FROM invoices

    LEFT JOIN info_jobs ON invoices.inv_job=info_jobs.job


    ORDER BY info_jobs.job DESC
    LIMIT '.$filtreLimit.'
    ;';

    //echo $req;
    return $this->db->getAll($req);
  }


  public function getAllPayables($dateStartPayable,$dateEndPayable) {

    $req='SELECT *
    FROM payables
    LEFT JOIN payable_lists ON payable_lists.id_payable_list=payables.id_payable_list
    LEFT JOIN info_jobs ON info_jobs.job=payables.job
    WHERE postedDate>='.$this->db->quote($dateStartPayable).'
    AND postedDate<='.$this->db->quote($dateEndPayable).'
    ORDER BY date_invoice ASC
    ;';

    return $this->db->getAll($req);
  }

  public function getAllUBR($dateStartPayable,$dateEndPayable) {

    $req='SELECT entreprise, customer, info_jobs.job, invoice_currency, ubr.ubrSubC as ubrSubC, ubr.ubrMRSAS as ubrMRSAS, ubrold.ubrSubC as ubroldSubC, ubrold.ubrMRSAS as ubroldMRSAS, ubr.date_ubr as date_ubr
    FROM ubr
    LEFT JOIN info_jobs ON ubr.job=info_jobs.job
    LEFT JOIN entreprises ON entreprises.id_entreprise=info_jobs.customer
    LEFT JOIN ubr ubrold ON ubrold.job=ubr.job AND ubrold.id_ubr=(
      SELECT u.id_ubr
      FROM ubr u
      WHERE u.job=ubrold.job
      AND u.date_UBR<ubr.date_UBR
      ORDER BY date_ubr DESC
      LIMIT 1
    )
    WHERE ubr.date_UBR>='.$this->db->quote($dateStartPayable).'
    AND ubr.date_UBR<='.$this->db->quote($dateEndPayable).'
    ORDER BY date_ubr ASC, info_jobs.job ASC
    ;';

    return $this->db->getAll($req);
  }

  public function getAllInvoices($dateStartPayable,$dateEndPayable) {

    $req='SELECT *
    FROM invoices
    LEFT JOIN info_jobs ON info_jobs.job=invoices.inv_job
    LEFT JOIN entreprises ON entreprises.id_entreprise=info_jobs.customer
    WHERE inv_date>='.$this->db->quote($dateStartPayable).'
    AND inv_date<='.$this->db->quote($dateEndPayable).'
    ORDER BY inv_date ASC, inv_number ASC
    ;';

    return $this->db->getAll($req);
  }

  public function addNewUBR() {

    $req = 'INSERT INTO ubr
    (job,	ubrMRSAS,	ubrSubC, date_creation,	date_ubr)
    VALUES ('.$this->job.', '.$this->ubrMRSAS.', '.$this->ubrSubC.', '.$this->date_creation.', '.$this->date_ubr.');';

    //echo $req;
    $this->db->execute($req);
  }

  public function getAllProdIndicator($dateStart, $dateEnd) {

    $req='SELECT *

    FROM kpi


    LEFT JOIN (
      SELECT DATE_FORMAT(inv_date, "%Y-%m") as inv_date,
      SUM(IF(USDRate>0, invoices.inv_mrsas*USDRate, inv_mrsas)) AS inv_mrsas,
      SUM(IF(USDRate>0, invoices.inv_subc*USDRate, inv_subc)) AS inv_subc
      FROM invoices
      WHERE inv_date>='.$this->db->quote($dateStart).' AND inv_date<'.$this->db->quote($dateEnd).'
      GROUP BY DATE_FORMAT(inv_date, "%Y-%m")
    ) as i ON  i.inv_date=kpi.date_kpi


    LEFT JOIN (
      SELECT DATE_FORMAT(date_ubr, "%Y-%m") as date_ubr, SUM(ubrMRSAS) AS ubrMRSAS, SUM(ubrSubC) AS ubrSubC
      FROM ubr
      WHERE date_ubr>='.$this->db->quote($dateStart).' AND date_ubr<'.$this->db->quote($dateEnd).'
      GROUP BY DATE_FORMAT(date_ubr, "%Y-%m")
    ) as u on u.date_ubr=kpi.date_kpi


    LEFT JOIN (
      SELECT DATE_FORMAT(date_payable, "%Y-%m") as date_payable,
      SUM(IF(taux>0, payables.USD*taux, payables.HT)) AS payable_HT,
      SUM(IF(payables.id_payable_list=0 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_0,
      SUM(IF(payables.id_payable_list=1 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_1,
      SUM(IF(payables.id_payable_list=2 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_2,
      SUM(IF(payables.id_payable_list=3 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_3,
      SUM(IF(payables.id_payable_list=4 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_4,
      SUM(IF(payables.id_payable_list=5 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_5,
      SUM(IF(payables.id_payable_list=6 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_6,
      SUM(IF(payables.id_payable_list=7 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_7,
      SUM(IF(payables.id_payable_list=8 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_8,
      SUM(IF(payables.id_payable_list=9 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_9,
      SUM(IF(payables.id_payable_list=10 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_10,
      SUM(IF(payables.id_payable_list=11 AND capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_11,
      SUM(IF(capitalize IS NULL, IF(taux>0, payables.USD*taux, payables.HT),0)) AS payable_capitalized

      FROM payables
      WHERE date_payable>='.$this->db->quote($dateStart).' AND date_payable<'.$this->db->quote($dateEnd).'
      GROUP BY DATE_FORMAT(date_payable, "%Y-%m")
    ) as p on p.date_payable=kpi.date_kpi


    LEFT JOIN (
      SELECT DATE_FORMAT(dateplanned, "%Y-%m") as dateplanned,
      SUM(if(ifnull(id_type, type)=1 OR ifnull(id_type, type)=6, 1, 0)) AS C1,
      SUM(if(ifnull(id_type, type)=1 OR ifnull(id_type, type)=6, ifnull((TIME_TO_SEC(badges.validation)/3600),ifnull(badges.validation2,(ifnull(planning_modif.quantity, planning_users.quantity))))*if(badge_hr.badge_type=0,8,1), 0)) AS Q1,
      SUM(if(ifnull(id_type, type)=2,1,0)) AS C2,
      SUM(if(ifnull(id_type, type)=2,ifnull(planning_modif.quantity, planning_users.quantity)*if(badge_hr.badge_type=0,8,1),0)) AS Q2,
      SUM(if(ifnull(id_type, type)=3,1,0)) AS C3,
      SUM(if(ifnull(id_type, type)=3,ifnull(planning_modif.quantity, planning_users.quantity)*if(badge_hr.badge_type=0,8,1),0)) AS Q3,
      SUM(if(ifnull(id_type, type)=4,1,0)) AS C4,
      SUM(if(ifnull(id_type, type)=4,ifnull(planning_modif.quantity, planning_users.quantity)*if(badge_hr.badge_type=0,8,1),0)) AS Q4,
      SUM(if(ifnull(id_type, type)=5,1,0)) AS C5,
      SUM(if(ifnull(id_type, type)=5,ifnull(planning_modif.quantity, planning_users.quantity)*if(badge_hr.badge_type=0,8,1),0)) AS Q5,
      SUM(if(((ifnull(id_type, type)=1 OR ifnull(id_type, type)=6) AND DAYOFWEEK(dateplanned)=7),1,0)) AS QSaturdayON,
      SUM(if(((ifnull(id_type, type)=1 OR ifnull(id_type, type)=6) AND DAYOFWEEK(dateplanned)=7),ifnull(planning_modif.quantity, planning_users.quantity)*if(badge_hr.badge_type=0,8,1),0)) AS CSaturdayON

      FROM planning_users
      LEFT JOIN badge_hr ON badge_hr.id_user=planning_users.id_user
      LEFT JOIN planning_modif ON (planning_modif.datemodif=planning_users.dateplanned AND
        id_planning_modif IN (
          SELECT max(pm.id_planning_modif)
          FROM planning_modif AS pm
          WHERE pm.id_validator>0 AND pm.id_user=planning_users.id_user
          AND datemodif>='.$this->db->quote($dateStart).' AND datemodif<'.$this->db->quote($dateEnd).'
          GROUP BY pm.datemodif
        )
      )
      LEFT JOIN badges ON badges.id_user=planning_users.id_user AND badges.date=planning_users.dateplanned AND badges.id_validator>0
      LEFT JOIN techniciens ON techniciens.id_technicien=planning_users.id_user
      WHERE techniciens.production=1
      AND dateplanned>='.$this->db->quote($dateStart).' AND dateplanned<'.$this->db->quote($dateEnd).'
      GROUP BY DATE_FORMAT(dateplanned, "%Y-%m")
    ) as pl on pl.dateplanned=kpi.date_kpi

    LEFT JOIN (
      SELECT DATE_FORMAT(date, "%Y-%m") as date_test, COUNT(*) AS nbTest,
      SUM(if(test_type_cat="Strain" AND c_temperature>25, 1, 0)) AS test_type_cat_Strain_ET,
      SUM(if(test_type_cat="Strain" AND (c_temperature<=25 OR c_temperature IS NULL), 1, 0)) AS test_type_cat_Strain_RT,
      SUM(if(test_type_cat="Load" AND c_temperature>25, 1, 0)) AS test_type_cat_Load_ET,
      SUM(if(test_type_cat="Load" AND (c_temperature<=25 OR c_temperature IS NULL), 1, 0)) AS test_type_cat_Load_RT,
      SUM(if(test_type_cat="Other" AND c_temperature>25, 1, 0)) AS test_type_cat_Other_ET,
      SUM(if(test_type_cat="Other" AND (c_temperature<=25 OR c_temperature IS NULL), 1, 0)) AS test_type_cat_Other_RT
      FROM enregistrementessais
      LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
      WHERE date>='.$this->db->quote($dateStart).' AND date<'.$this->db->quote($dateEnd).'
      GROUP BY DATE_FORMAT(date, "%Y-%m")
    ) as t on t.date_test=kpi.date_kpi

    LEFT JOIN (
      SELECT DATE_FORMAT(periode, "%Y-%m") as date_etat, COUNT(*) ,
      sum(cumul)/60 as cumul,
      SUM(if(etatmachine in ("Load","Strain","Dwell","Not","Fluage","Switchable","Relaxation"),cumul,0))/60 as cycling,
      SUM(if(etatmachine in ("Ramp","RampTemp","RampTemp1h","RampTemp3h"),cumul,0))/60 as rampToTemp,
      SUM(if(etatmachine is null or etatmachine in ( "Init","Menu","Parameters","Adv.","Check","Amb.","ET","STL","Stop","Straightening","Report","Analysis","Restart"),cumul,0))/60 as noncycling
      FROM etatmachine_machines
      WHERE periode>='.$this->db->quote($dateStart).' AND periode<'.$this->db->quote($dateEnd).'
      GROUP BY DATE_FORMAT(periode, "%Y-%m")
    ) as etat on etat.date_etat=kpi.date_kpi


    WHERE kpi.date_kpi>='.$this->db->quote($dateStart).' AND kpi.date_kpi<'.$this->db->quote($dateEnd).'

    ORDER BY kpi.date_kpi ASC
    ;';

    //echo $req;
    return $this->db->getAll($req);




  }

}
