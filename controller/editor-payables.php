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
Editor::inst( $db, 'payables' )
->pkey( 'payables.id_payable' )
->fields(
  Field::inst( 'payables.id_payable'),

  Field::inst( 'payables.payable')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'payables.capitalize')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'payables.date_due')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'payables.date_invoice')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'payables.invoice')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'payables.description')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'payables.job')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'payables.USD')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'payables.taux')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'payables.HT')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'payables.TVA')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'payables.TTC')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'payables.date_payable')
    ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'payables.id_payable_list' )
      ->options( Options::inst()
          ->table( 'payable_lists' )
          ->value( 'id_payable_list' )
          ->label( 'payable_list' )
      ),
        Field::inst( 'payable_lists.payable_list')
  )
  ->leftJoin( 'payable_lists',     'payable_lists.id_payable_list',          '=', 'payables.id_payable_list' )


  ->process($_POST)
  ->json();
  ?>
