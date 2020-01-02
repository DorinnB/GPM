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
Editor::inst( $db, 'planning_modif' )
->pkey( 'planning_modif.id_planning_modif' )
->fields(

  Field::inst( 'planning_modif.id_planning_modif' ),
  Field::inst( 'planning_modif.id_user')
      ->options( Options::inst()
          ->table( 'techniciens' )
          ->value( 'id_technicien' )
          ->label( 'technicien' )
          ->where( function($q)  {
                    $q ->where('technicien_actif','1');
             } )
      ),

Field::inst( 'user.technicien' ),
  Field::inst( 'planning_modif.datemodif'),

  Field::inst( 'planning_modif.id_type' )
      ->options( Options::inst()
          ->table( 'planning_types' )
          ->value( 'id_planning_type' )
          ->label( 'planning_type' )
      ),
  Field::inst( 'planning_types.planning_type' ),


  Field::inst( 'planning_modif.quantity')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),


  Field::inst( 'planning_modif.id_modifier')
      ->set( $_COOKIE['id_user'] )
      ->options( Options::inst()
          ->table( 'techniciens' )
          ->value( 'id_technicien' )
          ->label( 'technicien' )
      ),
  Field::inst( 'modifier.technicien' ),

  Field::inst( 'planning_modif.id_validator')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
Field::inst( 'validator.technicien' ),

  Field::inst( 'planning_modif.comments')
    ->setFormatter( 'Format::ifEmpty', null )
  )

  ->leftJoin( 'planning_types',     'planning_types.id_planning_type',          '=', 'planning_modif.id_type' )
  ->leftJoin( 'techniciens as modifier',     'modifier.id_technicien',          '=', 'planning_modif.id_modifier' )
  ->leftJoin( 'techniciens as validator',     'validator.id_technicien',          '=', 'abs(planning_modif.id_validator)' )
  ->leftJoin( 'techniciens as user',     'user.id_technicien',          '=', 'planning_modif.id_user' )



  //enregistrement du user effectuant l'update
  ->on( 'preCreate', function ( $editor, $values ) {
    $editor
    ->field( 'planning_modif.id_modifier' )
    ->setValue( $_COOKIE['id_user'] );
  } )
  //enregistrement du user effectuant l'update
  ->on( 'preEdit', function ( $editor, $values ) {
    $editor
    ->field( 'planning_modif.id_modifier' )
    ->setValue( $_COOKIE['id_user'] );
  } )

  ->process($_POST)
  ->json();
  ?>
