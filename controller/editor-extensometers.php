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
Editor::inst( $db, 'extensometres' )
->pkey( 'extensometres.id_extensometre' )
->fields(
  Field::inst( 'extensometres.id_extensometre'),
  Field::inst( 'extensometres.extensometre')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'extensometres.extensometre_model')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'extensometres.extensometre_sn')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'extensometres.type_extensometre')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'extensometres.Lo')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'extensometres.extensometre_comment')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'extensometres.extensometre_actif'),
  Field::inst( 'machines.machine' )
  )

  ->leftJoin('postes', 'postes.id_extensometre','=','extensometres.id_extensometre')
  ->leftJoin( 'machines', 'machines.id_machine', '=', 'postes.id_machine' )

  ->where( function($q) {
    $q->where ('id_poste', '(SELECT max(p1.id_poste) FROM postes p1 WHERE p1.id_extensometre = extensometres.id_extensometre)', 'IN', false);
  })
  ->on( 'postCreate', function ( $editor, $id, $values ) {    //On crée un poste avec cet element

    include_once('../models/db.class.php'); // call db.class.php
    $db = new db(); // create a new object, class db()
    // Rendre votre modèle accessible
    include '../models/poste-model.php';
    // Création d'une instance
    $oPoste = new PosteModel($db, 0);

    $oPoste->itemValue=$id;
    $oPoste->id_machine=100;
    $oPoste->id_operateur=$_COOKIE['id_user'];

    $oPoste->newPosteOther("id_extensometre");

  } )

  ->process($_POST)
  ->json();
  ?>
