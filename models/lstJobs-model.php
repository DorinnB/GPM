<?php
class LstJobsModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllJobs($filtre="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
					count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep,
          if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier)) as nbtest,
          CONVERT((if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier))/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent,
					IF(tbljobs.DyT_Cust>NOW(),0,1) as delay
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1
        '.$filtre.'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        LIMIT 1000';
        return $this->db->getAll($req);
    }

    public function getAllFollowup($filtreFollowup="final") {

      if ($filtreFollowup=='ALL') {
        $reqfiltre='AND etape <=90';
        $DyT=', IF(tbljobs.DyT_Cust>NOW(),0,1) as delay,
        IF((SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1) is null,
          available_expected,
          (SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1)
          ) AS available';
        $limit='LIMIT 1000';
      }
      elseif ($filtreFollowup=='SubC') {
        $reqfiltre='AND ST=1 AND etape <=90';
        $DyT=', IF(tbljobs.DyT_Cust>NOW(),0,1) as delay,
        IF((SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1) is null,
          available_expected,
          (SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1)
          ) AS available';
        $limit='LIMIT 1000';
      }
      elseif ($filtreFollowup=='ALLNoTime') {
        $reqfiltre='';
        $DyT=', IF(tbljobs.DyT_Cust>NOW(),0,1) as delay, "" as available';
        $limit='';
      }
      else {
        $reqfiltre='AND final=1 AND ST=0 AND etape <90';
        $DyT=', IF(tbljobs.DyT_Cust>NOW(),0,1) as delay,
        IF((SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase < tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1) is null,
          available_expected,
          (SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase < tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1)
          ) AS available';
        $limit='LIMIT 1000';
      }



		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color,
          statut, max(entreprises.entreprise_abbr) as entreprise_abbr, max(entreprises.entreprise) as entreprise, entrepriseST.entreprise_abbr as entreprise_abbrST,
          po_number, instruction,
          customer, job, split,
					test_type_abbr, final,
          etape, matiere, ref_matiere,
          GROUP_CONCAT(DISTINCT(dessin) SEPARATOR " ") as dessin,
          GROUP_CONCAT(DISTINCT(c_temperature) SEPARATOR " ") as temperature,
          DyT_expected, DyT_Cust, DyT_SubC, refSubC,
					count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep,
          if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier)) as nbstart,
          SUM(IF(d_checked > 0 OR n_fichier is not null , 1, 0)) as  nbtest,
          SUM(IF(eprouvette_InOut_A IS NOT NULL, 1, 0)) as nbsent,
          count(eprouvettes.id_master_eprouvette)-count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbRetest,
          CONVERT((count(DISTINCT(n_fichier))/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent
          '.$DyT.'

				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
          LEFT JOIN entreprises ON info_jobs.customer=entreprises.id_entreprise

          LEFT JOIN contacts contactST ON contactST.id_contact=tbljobs.id_contactST
          LEFT JOIN entreprises entrepriseST ON entrepriseST.id_entreprise=contactST.ref_customer

          LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std
          LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
          LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg

				WHERE tbljob_actif=1 AND eprouvette_actif=1
        '.$reqfiltre.'
        GROUP BY tbljobs.id_tbljob
				ORDER BY id_statut ASC, job DESC, split ASC
        '.$limit;
        //echo $req;
        return $this->db->getAll($req);
    }


    public function getAllFollowupJob($filtreFollowup="final") {

      if (is_numeric ($filtreFollowup)) {
        $limit=$filtreFollowup;
      }
      else {
        $limit='100';
      }


    $req = 'SELECT
          min(statuts.id_statut) as id_statut, (select st.statut_color from statuts st where st.id_statut= min(statuts.id_statut)) as statut_color, (select st.statut from statuts st where st.id_statut= min(statuts.id_statut)) as statut,
          max(entreprises.entreprise_abbr) as entreprise_abbr, max(entreprises.entreprise) as entreprise,
          po_number, instruction, commentaire, devis, max(ref_pricing) as ref_pricing ,
          customer, job, min(tbljobs.id_tbljob) as id_tbljob,
          GROUP_CONCAT(DISTINCT(test_type_abbr) SEPARATOR " ") as test_type_abbr,
          matiere, ref_matiere,available_expected,
          GROUP_CONCAT(DISTINCT(dessin) SEPARATOR " - ") as dessin,
          max(DyT_expected), max(DyT_Cust) as DyT_Cust, max(DyT_SubC),
          count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep,
          if(sum(if(st=1,1,0))>0,1,0) as subc,
          if(sum(if(st=0,1,0))>0,1,0) as mrsas,
          contacts.id_contact, contacts.prenom, contacts.nom,
          contacts2.id_contact as id_contact2, contacts2.prenom as prenom2, contacts2.nom as nom2,
          contacts3.id_contact as id_contact3, contacts3.prenom as prenom3, contacts3.nom as nom3,
          contacts4.id_contact as id_contact4, contacts4.prenom as prenom4, contacts4.nom as nom4

        FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
          LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
          LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
          LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
          LEFT JOIN entreprises ON info_jobs.customer=entreprises.id_entreprise
          LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact
          LEFT JOIN contacts  contacts2 ON contacts2.id_contact=info_jobs.id_contact2
          LEFT JOIN contacts  contacts3 ON contacts3.id_contact=info_jobs.id_contact3
          LEFT JOIN contacts  contacts4 ON contacts4.id_contact=info_jobs.id_contact4
          LEFT JOIN pricing ON pricing.id_pricing=info_jobs.pricing
          LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std
          LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
          LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg

        WHERE tbljob_actif=1 AND eprouvette_actif=1 and info_job_actif=1

        GROUP BY tbljobs.id_info_job
        order by job desc
        LIMIT '.$limit.'
      ';
      //echo $req;
      return $this->db->getAll($req);
    }


    public function searchJob($searchInfo="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
          po_number, instruction, specification
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1 AND info_job_actif=1
        AND job LIKE '.$this->db->quote('%'.$searchInfo.'%').'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        ';
        return $this->db->getAll($req);
    }

    public function searchSpecification($searchInfo="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
          po_number, instruction, specification
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1
        AND specification LIKE '.$this->db->quote('%'.$searchInfo.'%').'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        ';
        return $this->db->getAll($req);
    }
    public function searchPO($searchInfo="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
          po_number, instruction, specification
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1
        AND po_number LIKE '.$this->db->quote('%'.$searchInfo.'%').'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        ';
        return $this->db->getAll($req);
    }

    public function searchInst($searchInfo="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
          po_number, instruction, specification
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1
        AND instruction LIKE '.$this->db->quote('%'.$searchInfo.'%').'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        ';
        return $this->db->getAll($req);
    }

    public function searchEp($searchInfo="") {

      $req = 'SELECT eprouvettes.id_eprouvette,
      master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette,
      n_essai, n_fichier, id_tbljob,
      info_jobs.job, info_jobs.customer, split, test_type, eprouvettes.id_master_eprouvette, id_job,
      po_number, instruction,test_type_abbr

      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai

      WHERE master_eprouvettes.nom_eprouvette LIKE '.$this->db->quote('%'.$searchInfo.'%').'
       AND tbljob_actif=1 AND eprouvette_actif=1 AND info_job_actif=1';
      //echo $req;
      return $this->db->getALL($req);
    }

    public function searchPrefixe($searchInfo="") {

      $req = 'SELECT eprouvettes.id_eprouvette,
      master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette,
      n_essai, n_fichier, id_tbljob,
      info_jobs.job, info_jobs.customer, split, test_type, eprouvettes.id_master_eprouvette, id_job,
      po_number, instruction,test_type_abbr

      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai

      WHERE master_eprouvettes.prefixe LIKE '.$this->db->quote('%'.$searchInfo.'%').'
       AND tbljob_actif=1 AND eprouvette_actif=1 AND info_job_actif=1';
      //echo $req;
      return $this->db->getALL($req);
    }

    public function searchFile($searchInfo="") {

      $req = 'SELECT eprouvettes.id_eprouvette,
      master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette,
      n_essai, n_fichier, id_tbljob,
      info_jobs.job, info_jobs.customer, split, test_type, eprouvettes.id_master_eprouvette, id_job,
      po_number, instruction,test_type_abbr

      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai

      WHERE n_fichier = '.$this->db->quote($searchInfo).'
       AND tbljob_actif=1 AND eprouvette_actif=1 AND info_job_actif=1';
      //echo $req;
      return $this->db->getALL($req);
    }

    public function getWeeklyReportCust($customer) {
  	    $req = 'SELECT MAX(info_jobs.id_info_job) as id_info_job, MAX(customer) as customer, job, MAX(ref_matiere) as ref_matiere, MAX(po_number) as po_number, max(weeklyComment) as weeklyComment, max(instruction) as instruction,
            count(DISTINCT case when master_eprouvette_inOut_A is not null then master_eprouvettes.id_master_eprouvette end) AS nbreceived, count(DISTINCT master_eprouvettes.id_master_eprouvette) as nbep, min(master_eprouvette_inOut_A) as firstReceived, max(available_expected) as available_expected,
            GROUP_CONCAT(DISTINCT
             if(contacts.prenom is not null, concat(LEFT(contacts.prenom , 1), "&nbsp;",contacts.nom),""),
             if(contacts2.prenom is not null, concat("<br/>", LEFT(contacts2.prenom , 1), "&nbsp;",contacts2.nom),""),
             if(contacts3.prenom is not null, concat("<br/>", LEFT(contacts3.prenom , 1), "&nbsp;",contacts3.nom),""),
             if(contacts4.prenom is not null, concat("<br/>", LEFT(contacts4.prenom , 1), "&nbsp;",contacts4.nom),"")
           )as contacts,
           GROUP_CONCAT(DISTINCT
            if(contacts.prenom is not null, concat(LEFT(contacts.prenom , 1), " ",contacts.nom),""),
            if(contacts2.prenom is not null, concat("\r", LEFT(contacts2.prenom , 1), " ",contacts2.nom),""),
            if(contacts3.prenom is not null, concat("\r", LEFT(contacts3.prenom , 1), " ",contacts3.nom),""),
            if(contacts4.prenom is not null, concat("\r", LEFT(contacts4.prenom , 1), " ",contacts4.nom),"")
          )as contactsXLS

  				FROM info_jobs
          LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact
          LEFT JOIN contacts  contacts2 ON contacts2.id_contact=info_jobs.id_contact2
          LEFT JOIN contacts  contacts3 ON contacts3.id_contact=info_jobs.id_contact3
          LEFT JOIN contacts  contacts4 ON contacts4.id_contact=info_jobs.id_contact4
          LEFT JOIN master_eprouvettes ON master_eprouvettes.id_info_job=info_jobs.id_info_job
          LEFT JOIN eprouvettes ON eprouvettes.id_master_eprouvette=master_eprouvettes.id_master_eprouvette
  				WHERE info_job_actif=1 and job>13333 and customer='.$customer.'
          AND master_eprouvette_actif=1 AND eprouvette_actif=1
          AND (info_jobs.invoice_date>now()-interval 10 day OR info_jobs.invoice_type!=2)
          GROUP BY job
  				ORDER BY job DESC
          ';
        return $this->db->getAll($req);
    }

    public function getWeeklyReportInfoJob($id_infojob) {
        $req = 'SELECT MAX(info_jobs.id_info_job) as id_info_job, MAX(customer) as customer, job, MAX(ref_matiere) as ref_matiere, MAX(po_number) as po_number, max(weeklyComment) as weeklyComment,
            count(DISTINCT case when master_eprouvette_inOut_A is not null then master_eprouvettes.id_master_eprouvette end) AS nbreceived, count(DISTINCT master_eprouvettes.id_master_eprouvette) as nbep, min(master_eprouvette_inOut_A) as firstReceived, max(available_expected) as available_expected,
            GROUP_CONCAT(DISTINCT
             if(contacts.prenom is not null, concat(LEFT(contacts.prenom , 1), "&nbsp;",contacts.nom),""),
             if(contacts2.prenom is not null, concat("<br/>", LEFT(contacts2.prenom , 1), "&nbsp;",contacts2.nom),""),
             if(contacts3.prenom is not null, concat("<br/>", LEFT(contacts3.prenom , 1), "&nbsp;",contacts3.nom),""),
             if(contacts4.prenom is not null, concat("<br/>", LEFT(contacts4.prenom , 1), "&nbsp;",contacts4.nom),"")
           )as contacts,
           GROUP_CONCAT(DISTINCT
            if(contacts.prenom is not null, concat(LEFT(contacts.prenom , 1), " ",contacts.nom),""),
            if(contacts2.prenom is not null, concat("\r", LEFT(contacts2.prenom , 1), " ",contacts2.nom),""),
            if(contacts3.prenom is not null, concat("\r", LEFT(contacts3.prenom , 1), " ",contacts3.nom),""),
            if(contacts4.prenom is not null, concat("\r", LEFT(contacts4.prenom , 1), " ",contacts4.nom),"")
          )as contactsXLS

          FROM info_jobs
          LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact
          LEFT JOIN contacts  contacts2 ON contacts2.id_contact=info_jobs.id_contact2
          LEFT JOIN contacts  contacts3 ON contacts3.id_contact=info_jobs.id_contact3
          LEFT JOIN contacts  contacts4 ON contacts4.id_contact=info_jobs.id_contact4
          LEFT JOIN master_eprouvettes ON master_eprouvettes.id_info_job=info_jobs.id_info_job
          LEFT JOIN eprouvettes ON eprouvettes.id_master_eprouvette=master_eprouvettes.id_master_eprouvette
          WHERE info_jobs.id_info_job='.$id_infojob.'
          AND master_eprouvette_actif=1 AND eprouvette_actif=1
          AND (info_jobs.invoice_date>now()-interval 10 day OR info_jobs.invoice_date is null)
          GROUP BY job
          ORDER BY job DESC
          ';
        return $this->db->getOne($req);
    }

    public function getWeeklyReportJob($id_infojob) {
        $req = 'SELECT id_tbljob,
            tbljobs.id_statut, statut_color, statut_client,customer, statuts.etape, statuts.statut,
            job, split, test_type_abbr, DyT_Cust,
            count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep,
            count((eprouvettes.id_eprouvette)) as nbtestplanned,
            if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier)) as nbtest,
            CONVERT((if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier))/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent,
            IF(tbljobs.DyT_Cust>NOW(),0,1) as delay
          FROM eprouvettes
          LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
            LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
            LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
            LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
            LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
            LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
          LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
          WHERE tbljob_actif=1 AND eprouvette_actif=1 AND master_eprouvette_actif=1 AND auxilaire=0 AND info_jobs.id_info_job='.$id_infojob.'
          GROUP BY tbljobs.id_tbljob
          ORDER BY split ASC';
        return $this->db->getAll($req);
    }

    public function updateWeeklyReport($id, $comment) {

      $reqUpdateWeeklyReport='UPDATE info_jobs
      SET weeklyComment = '.(($comment=="")? "NULL" : $this->db->quote($comment)).'
      WHERE id_info_job = '.$this->db->quote($id);

      //echo $reqUpdateWeeklyReport;
      $this->db->query($reqUpdateWeeklyReport);

    }

}
