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
Editor::inst( $db, 'TDRs' )
->pkey( 'TDRs.id_TDR' )
->fields(
  Field::inst( 'TDRs.id_eprouvette'),
  Field::inst( 'TDRs.cyclenumber')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'TDRs.TDR_text')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'TDRs.TDR_actif'),

  Field::inst( 'TDRs.id_TDR_type' )
      ->options( Options::inst()
          ->table( 'TDR_types' )
          ->value( 'id_TDR_type' )
          ->label( 'TDR_type' )
          ->order( 'id_TDR_type' )
      ),
  Field::inst( 'TDR_types.TDR_type' ),

  Field::inst( 'TDRs.TDR_user' ),
  Field::inst( 'eprouvettes_temp.cycle_final_temp' )
  )
  ->leftJoin( 'TDR_types', 'TDR_types.id_TDR_type', '=', 'TDRs.id_TDR_type' )
  ->leftJoin ('eprouvettes_temp', 'eprouvettes_temp.id_eprouvettes_temp','=','TDRs.id_eprouvette')
  ->where('TDRs.id_eprouvette',(isset($_POST['idEp'])?$_POST['idEp']:0))
    ->where('TDR_actif',1)


//enregistrement du user effectuant l'update
    ->on( 'preEdit', function ( $editor, $values ) {
        $editor
            ->field( 'TDRs.TDR_user' )
            ->setValue( $_COOKIE['id_user'] );
    } )

    ->on( 'preCreate', function ( $editor, $values ) {
      $editor
      ->field( 'TDRs.TDR_user' )
      ->setValue( $_COOKIE['id_user'] );
    } )
    ->on( 'preCreate', function ( $editor, $values ) {
      $editor
      ->field( 'TDRs.id_eprouvette' )
      ->setValue( $_POST['idEp'] );
    } )

  ->process($_POST)
  ->json();
  ?>
