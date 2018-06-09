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
Editor::inst( $db, 'ind_temps' )
->pkey( 'ind_temps.id_ind_temp' )
->fields(
  Field::inst( 'ind_temps.ind_temp')
    ->validator( 'Validate::notEmpty' ),

  Field::inst( 'ind_temps.ind_temp_model')
    ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'ind_temps.ind_temp_actif')
  )


  ->process($_POST)
  ->json();
  ?>
