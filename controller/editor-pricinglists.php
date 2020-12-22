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
Editor::inst( $db, 'pricinglists' )
->pkey( 'pricinglists.id_pricingList' )
->fields(
  Field::inst( 'pricinglists.pricingList')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'pricinglists.pricingListFR')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'pricinglists.pricingListUS')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'pricinglists.prodCode')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'pricinglists.OpnCode')
  ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'pricinglists.USD')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'pricinglists.EURO')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'pricinglists.type')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'pricinglists.pricingList_actif')
  )

  //récupère les test type ayant ces pricinglist
  ->join(
    Mjoin::inst( 'test_type' )
    ->link( 'pricinglists.id_pricingList', 'test_type_pricinglists.id_pricingList' )
    ->link( 'test_type.id_test_type', 'test_type_pricinglists.id_test_type' )
    ->order( 'test_type_abbr asc' )
    ->fields(
      Field::inst( 'test_type' )
      ->validator( Validate::required() )
      ->options( Options::inst()
      ->table( 'test_type' )
      ->value( 'id_test_type' )
      ->label( 'test_type' )
    ),
    Field::inst( 'test_type_abbr' )
    )
    )



    ->process($_POST)
    ->json();
    ?>
