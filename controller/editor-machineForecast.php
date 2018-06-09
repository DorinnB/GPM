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
Editor::inst( $db, 'machine_forecasts' )
->pkey( 'machine_forecasts.id_machine_forecast' )
->fields(
  Field::inst( 'machine_forecasts.id_machine_forecast')
  ->options( Options::inst()
      ->table( 'machines' )
      ->value( 'id_machine' )
      ->label( 'machine' )
  ),
    Field::inst( 'machines.machine' ),
  Field::inst( 'machine_forecasts.texte_machine_forecast'),
  Field::inst( 'machine_forecasts.prio_machine_forecast'),
  Field::inst( 'machine_forecasts.id_icone_machine_forecast' )
      ->options( Options::inst()
          ->table( 'icones' )
          ->value( 'id_icone' )
          ->label( 'icone_name' )
      ),
  Field::inst( 'icones.icone_name' ),
  Field::inst( 'icones.icone_file' )  

  )
  ->leftJoin( 'machines',     'machines.id_machine',          '=', 'machine_forecasts.id_machine_forecast' )
  ->leftJoin( 'icones',     'icones.id_icone',          '=', 'machine_forecasts.id_icone_machine_forecast' )



  ->process($_POST)
  ->json();
  ?>
