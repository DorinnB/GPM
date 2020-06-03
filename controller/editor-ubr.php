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
  Field::inst( 'ubr.ubrMRSAS'),
  Field::inst( 'ubr.ubrSubC'),
  Field::inst( 'ubr.date_creation'),
  Field::inst( 'ubr.date_UBR'),

  Field::inst( 'info_jobs.job'),

  Field::inst( 'ubrold.ubrMRSAS'),
  Field::inst( 'ubrold.ubrSubC'),
  Field::inst( 'ubrold.date_creation'),
  Field::inst( 'ubrold.date_UBR')
)


  ->leftJoin( 'info_jobs',     'info_jobs.id_info_job',          '=', 'ubr.id_info_job' )

  ->leftJoin('ubr as ubrold', 'ubrold.id_info_job=ubr.id_info_job and ubrold.id_UBR = (select max(u.id_ubr) from ubr u where u.id_info_job=ubrold.id_info_job and u.date_creation<ubr.date_creation group by u.id_info_job)','','')

  ->process($_POST)
  ->json();
  ?>
