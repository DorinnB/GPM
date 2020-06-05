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
DataTables\Editor\Validate,
DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'invoices' )
->pkey( 'invoices.id_invoice' )
->fields(
  Field::inst( 'info_jobs.customer'),
  Field::inst( 'info_jobs.job'),
  Field::inst( 'info_jobs.order_val'),
  Field::inst( 'info_jobs.order_est'),
  Field::inst( 'info_jobs.invoice_currency'),
  Field::inst( 'invoices.USDRate'),
  Field::inst( 'invoices.inv_mrsas'),
  Field::inst( 'invoices.inv_subc'),
  Field::inst( 'invoices.inv_number'),
  Field::inst( 'invoices.inv_date'),
  Field::inst( 'invoices.USDRate'),
  Field::inst( 'invoices.inv_tva'),
  Field::inst( 'invoices.datepayement')
  )

  ->leftJoin( 'info_jobs', 'info_jobs.job', '=', 'invoices.inv_job' )

  ->where('invoices.inv_date',$_POST['dateStartInvoice'],'>=')


  ->process($_POST)
  ->json();
  ?>
