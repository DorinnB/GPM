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
Editor::inst( $db, 'specifications' )
->pkey( 'specifications.id_specification' )
->fields(
  Field::inst( 'specifications.specification')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'specifications.version')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'specifications.id_test_type' )
      ->options( Options::inst()
          ->table( 'test_type' )
          ->value( 'id_test_type' )
          ->label( 'test_type_abbr' )
      ),
  Field::inst( 'test_type.test_type_abbr' ),
Field::inst( 'specifications.specification_actif' )
  )
  ->leftJoin( 'test_type',     'test_type.id_test_type',          '=', 'specifications.id_test_type' )


  ->process($_POST)
  ->json();
  ?>
