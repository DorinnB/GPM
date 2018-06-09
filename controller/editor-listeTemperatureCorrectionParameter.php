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
Editor::inst( $db, 'temperature_correction_parameters' )
->pkey( 'temperature_correction_parameters.id_temperature_correction_parameter' )
->fields(
  Field::inst( 'temperature_correction_parameters.id_temperature_correction_parameter')->set( 'false' ),
  Field::inst( 'temperature_correction_parameters.supplier')->validator( 'Validate::notEmpty' ),
  Field::inst( 'temperature_correction_parameters.spool')->validator( 'Validate::notEmpty' ),
  Field::inst( 'temperature_correction_parameters.tc_type')->validator( 'Validate::notEmpty' ),
  Field::inst( 'temperature_correction_parameters.id_technicien')->validator( 'Validate::notEmpty' ),
  Field::inst( 'temperature_correction_parameters.date_temperature_correction_parameter')->validator( 'Validate::notEmpty' )
  )
  ->on( 'preCreate', function ( $editor, $values ) {
    $editor
    ->field( 'temperature_correction_parameters.date_temperature_correction_parameter' )->setValue( date("Y-m-d H:i:s") );
  } )
  ->on( 'preCreate', function ( $editor, $values ) {
    $editor
    ->field( 'temperature_correction_parameters.id_technicien' )->setValue( $_COOKIE['id_user'] );
  } )
  ->process($_POST)
  ->json();
  ?>
