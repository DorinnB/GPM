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
  ->pkey( 'badges.id_badge' )
  ->fields(
    Field::inst( 'badges.date'),
    Field::inst( 'badges.id_user' )
      ->options( Options::inst()
      ->table( 'techniciens' )
      ->value( 'id_technicien' )
      ->label( 'id_technicien' )
    ),
    Field::inst( 'techniciens.id_technicien'),
    Field::inst( 'techniciens.technicien'),
    Field::inst( 'badges.in1'),
    Field::inst( 'badges.out1'),
    Field::inst( 'badges.in2'),
    Field::inst( 'badges.out2'),
    Field::inst( 'badges.validation')
      ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'badges.validation2')
      ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'badges.comments')
      ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'badges.id_validator')
      ->options( Options::inst()
      ->table( 'techniciens' )
      ->value( 'id_technicien' )
      ->label( 'id_technicien' )
    ),
    Field::inst( 't2.technicien'),
    Field::inst( 'badgeplanning.quantity'),

    Field::inst( 'planning_modif.quantity'),
    Field::inst( 'planning_users.quantity')
  )

  ->leftJoin( 'techniciens',     'techniciens.id_technicien',          '=', 'badges.id_user' )
  ->leftJoin( 'techniciens as t2',     't2.id_technicien',          '=', 'badges.id_validator' )
  ->leftJoin( 'badgeplanning',     'badgeplanning.id_badge',          '=', 'badges.id_badge' )

  ->leftJoin( 'planning_users',     'planning_users.id_user=badges.id_user and planning_users.dateplanned=badges.date','','' )
  ->leftJoin( 'planning_modif',     'planning_modif.id_user=planning_users.id_user and planning_modif.datemodif=planning_users.dateplanned and planning_modif.id_planning_modif in (select max(pm.id_planning_modif) from planning_modif pm where pm.id_validator>0 group by pm.id_user, pm.datemodif)','','' )


  ->where( function ( $q ) {
    //$q->where('ba.id_manager',(isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0));
    $q->where('badges.id_user',(isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0));
  })


  ->process($_POST)
  ->json();
?>
