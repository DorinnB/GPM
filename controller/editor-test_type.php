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
Editor::inst( $db, 'test_type' )
->pkey( 'test_type.id_test_type' )
->fields(
  Field::inst( 'test_type.test_type')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'test_type.test_type_abbr')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'test_type.test_type_cust'),

  Field::inst( 'test_type.final')
    ->validator( 'Validate::numeric' )
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'test_type.auxilaire')
    ->validator( 'Validate::numeric' )
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'test_type.ST')
    ->validator( 'Validate::numeric' )
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'test_type.test_type_actif')
    ->validator( 'Validate::numeric' )
    ->validator( 'Validate::notEmpty' )
  )

->join(
      Mjoin::inst( 'pricinglists' )
          ->link( 'test_type.id_test_type', 'test_type_pricinglists.id_test_type' )
          ->link( 'pricinglists.id_pricingList', 'test_type_pricinglists.id_pricingList' )

          ->fields(
              Field::inst( 'id_pricingList' )
                  ->validator( 'Validate::required' )
                  ->options( Options::inst()
                      ->table( 'pricinglists' )
                      ->value( 'id_pricingList' )
                      ->label( array('prodCode', 'OpnCode', 'pricingList') )
                      ->render( function ( $row ) {
                          return $row['prodCode'].'-'.$row['OpnCode'].' : '.$row['pricingList'];
                      } )
                      ->order( 'id_pricingList' )
                  ),
              Field::inst( 'prodCode' ),
              Field::inst( 'OpnCode' )
          )
)
  ->process($_POST)
  ->json();
  ?>
