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
Editor::inst( $db, 'lab_forecasts' )
->pkey( 'lab_forecasts.id_lab_forecast' )
->fields(
  Field::inst( 'lab_forecasts.texte_lab_forecast'),
  Field::inst( 'lab_forecasts.prio_lab_forecast'),
  Field::inst( 'lab_forecasts.id_icone_lab_forecast' )
      ->options( Options::inst()
          ->table( 'icones' )
          ->value( 'id_icone' )
          ->label( 'icone_name' )
      ),
  Field::inst( 'icones.icone_name' ),
  Field::inst( 'icones.icone_file' )

  )
  ->leftJoin( 'icones',     'icones.id_icone',          '=', 'lab_forecasts.id_icone_lab_forecast' )



  ->process($_POST)
  ->json();
  ?>
