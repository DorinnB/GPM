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
Editor::inst( $db, 'dessins' )
->pkey( 'dessins.id_dessin' )
->fields(
  Field::inst( 'dessins.dessin')
    ->validator( 'Validate::notEmpty' ),

    Field::inst( 'dessins.gripType')
    ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'dessins.gripDimension')
    ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'dessins.nominal_1')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'dessins.tolerance_plus_1')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'dessins.tolerance_moins_1')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'dessins.nominal_2')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'dessins.tolerance_plus_2')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'dessins.tolerance_moins_2')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'dessins.nominal_3')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'dessins.tolerance_plus_3')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'dessins.tolerance_moins_3')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'dessins.id_dessin_type' )
      ->options( Options::inst()
          ->table( 'dessin_types' )
          ->value( 'id_dessin_type' )
          ->label( 'dessin_type' )
      ),
  Field::inst( 'dessin_types.dessin_type' ),
  Field::inst( 'dessin_types.id_dessin_type' ),
  Field::inst( 'dessins.dessin_actif')
  )
  ->leftJoin( 'dessin_types',     'dessin_types.id_dessin_type',          '=', 'dessins.id_dessin_type' )


  ->process($_POST)
  ->json();
  ?>
