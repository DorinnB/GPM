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
Editor::inst( $db, 'purchases' )
->pkey( 'purchases.id_purchase' )
->fields(
  Field::inst( 'purchases.id_purchase'),

  Field::inst( 'purchases.purchase_date'),
  Field::inst( 'purchases.description')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'purchases.id_user')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'techniciens.technicien'),
  Field::inst( 'purchases.supplier')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'purchases.usd')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'purchases.euro')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'purchases.id_validator'),
  Field::inst( 't2.technicien'),
  Field::inst( 'purchases.purchase_number')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'purchases.id_receipt'),
  Field::inst( 't3.technicien'),
  Field::inst( 'purchases.job')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'purchases.comments')
  ->setFormatter( 'Format::ifEmpty', null )

  )
  ->leftJoin( 'techniciens',     'techniciens.id_technicien',          '=', 'purchases.id_user' )
  ->leftJoin( 'techniciens as t2',     't2.id_technicien',          '=', 'abs(purchases.id_validator)' )
  ->leftJoin( 'techniciens as t3',     't3.id_technicien',          '=', 'abs(purchases.id_receipt)' )

  ->where('purchases.purchase_date',$_POST['dateStartPurchase'],'>=')

  //enregistrement du user effectuant l'update
  ->on( 'preCreate', function ( $editor, $values ) {
    $editor
    ->field( 'purchases.id_user' )
    ->setValue( $_COOKIE['id_user'] );
  } )



  ->process($_POST)
  ->json();
  ?>
