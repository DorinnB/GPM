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
Editor::inst( $db, 'servovalves' )
->pkey( 'servovalves.id_servovalve' )
->fields(
  Field::inst( 'servovalves.id_servovalve'),
  Field::inst( 'servovalves.servovalve')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'servovalves.servovalve_model')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.manufacture')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.servovalve_capacity')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.fixing_type')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.manufacture_date')
    ->validator( 'Validate::dateFormat', array(
        "format"  => Format::DATE_ISO_8601,
        "message" => "Please enter a date in the format yyyy-mm-dd"
      ) )
    ->getFormatter( 'Format::date_sql_to_format', Format::DATE_ISO_8601 )
    ->setFormatter( 'Format::date_format_to_sql', Format::DATE_ISO_8601 )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.servovalve_actif'),
  Field::inst( 'machines.machine' )
  )

  ->leftJoin('postes', 'postes.id_servovalve1','=','servovalves.id_servovalve OR postes.id_servovalve2 = servovalves.id_servovalve')
  ->leftJoin( 'machines', 'machines.id_machine', '=', 'postes.id_machine' )

  ->where( function($q) {
    $q->where ('id_poste', '(SELECT max(p1.id_poste) FROM postes p1 WHERE p1.id_servovalve1 = servovalves.id_servovalve OR p1.id_servovalve2 = servovalves.id_servovalve)', 'IN', false);
  })
  ->on( 'postCreate', function ( $editor, $id, $values ) {    //On crée un poste avec cet element

    include_once('../models/db.class.php'); // call db.class.php
    $db = new db(); // create a new object, class db()
    // Rendre votre modèle accessible
    include '../models/poste-model.php';
    // Création d'une instance
    $oPoste = new PosteModel($db, 0);

    $oPoste->itemValue=$id;
    $oPoste->id_machine=29;
    $oPoste->id_operateur=$_COOKIE['id_user'];

    $oPoste->newPosteOther("id_servovalve1");

  } )

  ->process($_POST)
  ->json();
  ?>
