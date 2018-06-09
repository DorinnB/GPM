<?php
// DataTables PHP library
include( "../DataTables/Editor-1.6.1/php/DataTables.php" );



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
Editor::inst( $db, 'file_paths' )
->pkey( 'file_paths.id_file_path' )
->fields(
  Field::inst( 'file_paths.file_path')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'file_paths.file_type')
    ->validator( 'Validate::notEmpty' )
  )

  ->process($_POST)
  ->json();
  ?>
