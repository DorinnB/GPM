<?php
// DataTables PHP library
include( "../DataTables/Editor-PHP-1.9.2/lib/DataTables.php" );



// Alias Editor classes so they are easy to use
use
DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Mjoin,
DataTables\Editor\Options,
DataTables\Editor\Upload,
DataTables\Editor\Validate;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'master_eprouvettes' )
->pkey( 'master_eprouvettes.id_master_eprouvette' )
->fields(
  Field::inst( 'master_eprouvettes.id_master_eprouvette'),
  Field::inst( 'master_eprouvettes.prefixe'),
  Field::inst( 'master_eprouvettes.nom_eprouvette'),
  Field::inst( 'master_eprouvettes.master_eprouvette_inOut_A'),
  Field::inst( 'master_eprouvettes.master_eprouvette_inOut_B'),
  Field::inst( 'info_jobs.customer'),
  Field::inst( 'info_jobs.job'),
  Field::inst( 'info_jobs.instruction'),
  Field::inst( 'info_jobs.id_info_job')
  )

->leftJoin( 'info_jobs',     'info_jobs.id_info_job',          '=', 'master_eprouvettes.id_info_job' )

->where( function ( $q ) {

    $q->where ('info_jobs.id_info_job', '(
        SELECT MAX(info_jobs.id_info_job) as id_info_job
        FROM info_jobs
        LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
        LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
        LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_info_job=info_jobs.id_info_job
        LEFT JOIN eprouvettes ON eprouvettes.id_master_eprouvette=master_eprouvettes.id_master_eprouvette
        WHERE info_job_actif=1
        AND master_eprouvette_actif=1 AND eprouvette_actif=1 AND tbljob_actif=1
        AND etape<95
        GROUP BY job
      )', 'IN', false);

  $q->where ('master_eprouvettes.id_master_eprouvette', '(SELECT m.id_master_eprouvette FROM master_eprouvettes m LEFT JOIN eprouvettes ON eprouvettes.id_master_eprouvette=m.id_master_eprouvette WHERE eprouvettes.eprouvette_actif=1 AND master_eprouvettes.master_eprouvette_actif=1)', 'IN', false);
})



->process($_POST)
->json();
?>
