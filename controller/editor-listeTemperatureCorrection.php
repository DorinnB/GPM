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
Editor::inst( $db, 'temperature_corrections' )
->pkey( 'temperature_corrections.id_temperature_correction' )
->fields(
  Field::inst( 'temperature_corrections.temperature')->validator( 'Validate::notEmpty' ),
  Field::inst( 'temperature_corrections.correction')->validator( 'Validate::notEmpty' ),
  Field::inst( 'temperature_corrections.id_temperature_correction_parameter')->validator( 'Validate::notEmpty' )
  )
  ->where('id_temperature_correction_parameter',(isset($_POST['id_temperature_correction_parameter']))?$_POST['id_temperature_correction_parameter']:"0","=")

  ->on( 'preCreate', function ( $editor, $values ) {
      $editor
          ->field( 'temperature_corrections.id_temperature_correction_parameter' )
          ->setValue( $_POST['id_temperature_correction_parameter'] );
  } )

  ->process($_POST)
  ->json();
  ?>
