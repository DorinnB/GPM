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
Editor::inst( $db, 'servovalves' )
->pkey( 'servovalves.id_servovalve' )
->fields(
  Field::inst( 'servovalves.servovalve')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'servovalves.servovalve_model')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.manufacture')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.servovalve_capacity')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.fixing_type')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.manufacture_date')
    ->validator( 'Validate::dateFormat', array(
        "format"  => Format::DATE_ISO_8601,
        "message" => "Please enter a date in the format yyyy-mm-dd"
      ) )
    ->getFormatter( 'Format::date_sql_to_format', Format::DATE_ISO_8601 )
    ->setFormatter( 'Format::date_format_to_sql', Format::DATE_ISO_8601 )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.servovalve_actif')
  )

  ->process($_POST)
  ->json();
  ?>
