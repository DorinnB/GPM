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
Editor::inst( $db, 'ubr' )
->pkey( 'ubr.id_ubr' )
->fields(
  Field::inst( 'ubr.id_ubr'),

  Field::inst( 'ubr.id_info_job'),
  Field::inst( 'ubr.UBR_GPM'),
  Field::inst( 'ubr.UBR'),
  Field::inst( 'ubr.date_creation'),
  Field::inst( 'ubr.date_UBR'),

  Field::inst( 'info_jobs.job'),


  Field::inst( 'payables_job.type2')
)


  ->leftJoin( 'info_jobs',     'info_jobs.id_info_job',          '=', 'ubr.id_info_job' )
  ->leftJoin( 'payables_job',     'payables_job.job',          '=', 'info_jobs.job' )


  ->process($_POST)
  ->json();
  ?>
