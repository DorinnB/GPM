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
Editor::inst( $db, 'planning_modif' )
->pkey( 'planning_modif.id_planning_modif' )
->fields(

  Field::inst( 'planning_modif.id_planning_modif' ),
  Field::inst( 'planning_modif.id_modifier')
  ->set( $_COOKIE['id_user'] )
  ->options( Options::inst()
  ->table( 'techniciens' )
  ->value( 'id_technicien' )
  ->label( 'technicien' )
),
Field::inst( 'modifier.technicien' ),
Field::inst( 'planning_modif.dateinitiale'),
Field::inst( 'planning_modif.id_user')
->options( Options::inst()
->table( 'techniciens' )
->value( 'id_technicien' )
->label( 'technicien' )
->where( function($q)  {
  $q->where( 'id_technicien', '(SELECT id_managed FROM badge_access WHERE id_manager = '.(isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0).')', 'IN', false );
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
Field::inst( 'planning_modif.comments')
->setFormatter( 'Format::ifEmpty', null ),
Field::inst( 'planning_modif.id_validator')
->validator( 'Validate::numeric' )
->setFormatter( 'Format::ifEmpty', null ),
Field::inst( 'validator.technicien' ),
Field::inst( 'planning_modif.datevalidation')
->setFormatter( 'Format::ifEmpty', null )
)



->leftJoin( 'planning_types',     'planning_types.id_planning_type',          '=', 'planning_modif.id_type' )
->leftJoin( 'techniciens as modifier',     'modifier.id_technicien',          '=', 'planning_modif.id_modifier' )
->leftJoin( 'techniciens as validator',     'validator.id_technicien',          '=', 'abs(planning_modif.id_validator)' )
->leftJoin( 'techniciens as user',     'user.id_technicien',          '=', 'planning_modif.id_user' )
->leftJoin( 'badge_access as ba',     'ba.id_managed',          '=', 'planning_modif.id_user' )

->where( function ( $q ) {
  $q->where('ba.id_manager',(isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0));
  //    $q->or_where('planning_modif.id_modifier',(isset($_COOKIE['id_user'])?$_COOKIE['id_user']:0));
})

//enregistrement du user effectuant l'update
->on( 'preCreate', function ( $editor, $values ) {
  $editor
  ->field( 'planning_modif.id_modifier' )
  ->setValue( $_COOKIE['id_user'] );
} )

//send notification
->on( 'postCreate', function ( $editor, $id, $values ) {

  $id_user= $values['planning_modif']['id_user'];

  $comments='Type: '.$values['planning_modif']['id_type'].'

  Comments:
  '.$values['planning_modif']['comments'];


  $_POST["type"] = 'sendNotification';
  $_POST["id_receiver_user"] = array ($id_user);
  $_POST["subject"] = 'Planning change: '.$values['planning_modif']['datemodif'];
  $_POST["text"] = $comments;
  include("updateNotification.php");

})
//send notification
->on( 'postEdit', function ( $editor, $id, $values ) {


  $id_user= $values['planning_modif']['id_user'];

  if ($values['planning_modif']['id_validator']>0) {
    $statut="Validated";
  }
  elseif ($values['planning_modif']['id_validator']<0) {
    $statut="Refused";
  }
  else {
    $statut="Not decided";
  }

  $comments='comments:
  '.$values['planning_modif']['comments'].'

  '.'Statut:
  '.$statut;

  $_POST["type"] = 'sendNotification';
  $_POST["id_receiver_user"] = array ($id_user);
  $_POST["subject"] = 'Planning change: '.$values['planning_modif']['datemodif'];
  $_POST["text"] = $comments;
  include_once("updateNotification.php");

})

->process($_POST)
->json();
?>
