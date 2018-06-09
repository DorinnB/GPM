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
Editor::inst( $db, 'xmlforts' )
->pkey( 'xmlforts.id_xmlforts' )
->fields(
  Field::inst( 'xmlforts.xml'),
  Field::inst( 'xmlforts.ts'),
  Field::inst( 'xmlforts.unit'),
  Field::inst( 'xmlforts.id_test_type' )
      ->options( Options::inst()
          ->table( 'test_type' )
          ->value( 'id_test_type' )
          ->label( 'test_type_abbr' )
      ),
  Field::inst( 'test_type.test_type_abbr' )
  )
  ->leftJoin( 'test_type',     'test_type.id_test_type',          '=', 'xmlforts.id_test_type' )


  ->process($_POST)
  ->json();
  ?>
