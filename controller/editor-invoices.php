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
->pkey( 'id_invoice' )
->fields(
  Field::inst( 'inv_number')
  ->validator( Validate::notEmpty( ValidateOptions::inst()
  ->message( 'An invoice number is required' )
  ) ),
  Field::inst( 'inv_job'),
  Field::inst( 'inv_mrsas'),
  Field::inst( 'inv_subc'),
  Field::inst( 'inv_TVA'),
  Field::inst( 'USDRate'),
  Field::inst( 'inv_date')
  )


  ->process($_POST)
  ->json();
  ?>
