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
Editor::inst( $db, 'pricinglists' )
->pkey( 'pricinglists.id_pricingList' )
->fields(
  Field::inst( 'pricinglists.pricingList')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'pricinglists.pricingListFR')
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



  ->process($_POST)
  ->json();
  ?>
