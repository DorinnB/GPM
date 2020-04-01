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
Editor::inst( $db, 'badges' )
    ->readTable('badgeplanning') // The VIEW to read data from
  ->pkey( 'badgeplanning.id_badge' )
  ->fields(
    Field::inst( 'badgeplanning.date'),
    Field::inst( 'badgeplanning.id_user' )
    ->options( Options::inst()
    ->table( 'techniciens' )
    ->value( 'id_technicien' )
    ->label( 'id_technicien' )
  ),
  Field::inst( 'techniciens.id_technicien'),
  Field::inst( 'techniciens.technicien'),
  Field::inst( 'badgeplanning.in1'),
  Field::inst( 'badgeplanning.out1'),
  Field::inst( 'badgeplanning.in2'),
  Field::inst( 'badgeplanning.out2'),
  Field::inst( 'badgeplanning.validation')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'badgeplanning.validation2')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'badgeplanning.comments')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'badgeplanning.id_validator')
  ->options( Options::inst()
  ->table( 'techniciens' )
  ->value( 'id_technicien' )
  ->label( 'id_technicien' )
  ),
  Field::inst( 't2.technicien'),
  Field::inst( 'ba.id_manager'),
  Field::inst( 'badgeplanning.quantity')
  )

  ->leftJoin( 'techniciens',     'techniciens.id_technicien',          '=', 'badgeplanning.id_user' )
  ->leftJoin( 'techniciens as t2',     't2.id_technicien',          '=', 'badgeplanning.id_validator' )
  ->leftJoin( 'badge_access as ba',     'ba.id_managed',          '=', 'badgeplanning.id_user' )



  ->where( function ( $q ) {
    $q->where('ba.id_manager',(isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0));
    $q->where('techniciens.badge','1');
    //$q->or_where('id_user',(isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0));
  })

  //enregistrement du user effectuant l'update
  ->on( 'preEdit', function ( $editor, $values ) {
    $editor
    ->field( 'badgeplanning.id_validator' )
    ->setValue( $_COOKIE['id_user'] );
  } )

  ->process($_POST)
  ->json();
?>
