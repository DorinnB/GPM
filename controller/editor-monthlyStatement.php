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
Editor::inst( $db, 'info_jobs' )
->pkey( 'info_jobs.id_info_job' )
->fields(
  Field::inst( 'info_jobs.id_info_job'),
  Field::inst( 'info_jobs.customer'),
  Field::inst( 'info_jobs.job'),
  Field::inst( 'info_jobs.datecreation'),
  Field::inst( 'info_jobs.invoice_type'),
  Field::inst( 'info_jobs.invoice_date'),

  Field::inst( 'ubr.ubrMRSAS'),
  Field::inst( 'ubr.ubrSubC'),
  Field::inst( 'ubr.date_creation'),
  Field::inst( 'ubr.date_UBR'),

  Field::inst( 'ubrold.ubrMRSAS'),
  Field::inst( 'ubrold.ubrSubC'),
  Field::inst( 'ubrold.date_creation'),
  Field::inst( 'ubrold.date_UBR')
  )


  ->leftJoin('ubr', 'ubr.job=info_jobs.job and ubr.date_UBR = "'.$_POST['dateStartMonthlyStatement'].'"','','')
  ->leftJoin('ubr as ubrold', 'ubrold.job=info_jobs.job and ubrold.date_UBR = "'.date("Y-m-t",strtotime(date("Y-m-t", strtotime($_POST['dateStartMonthlyStatement'])) . "-35 days")).'"','','')


  ->where( function ( $q ) {
    $q->where('ubr.date_UBR',null,'!=');
    $q->or_where( 'ubrold.date_UBR',null,'!=');
    $q->or_where( function($r) {
      $r->where( 'info_jobs.datecreation',$_POST['dateStartMonthlyStatement'],'<=');
      $r->where( 'info_jobs.datecreation',date("Y-m-01",strtotime($_POST['dateStartMonthlyStatement'])),'>');
    });

  } )



  ->process($_POST)
  ->json();
  ?>
