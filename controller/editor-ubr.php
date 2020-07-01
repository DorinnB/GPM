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
  Field::inst( 'info_jobs.customer'),
  Field::inst( 'ubr.job'),

  Field::inst( 'ubr.ubrMRSAS'),
  Field::inst( 'ubr.ubrSubC'),
  Field::inst( 'ubr.date_creation'),
  Field::inst( 'ubr.date_UBR'),

  Field::inst( 'ubrold.ubrMRSAS'),
  Field::inst( 'ubrold.ubrSubC'),
  Field::inst( 'ubrold.date_creation'),
  Field::inst( 'ubrold.date_UBR')
)

  ->leftJoin('ubr as ubrold', 'ubrold.id_info_job=ubr.id_info_job and ubrold.id_UBR = (select u.id_ubr from ubr u where u.id_info_job=ubrold.id_info_job and u.date_UBR<ubr.date_UBR order by date_ubr desc limit 1)','','')
  ->leftJoin('info_jobs', 'info_jobs.job', '=', 'ubr.job')

  ->where('ubr.date_UBR',$_POST['dateStartUBR'],'>=')

  ->process($_POST)
  ->json();
  ?>
