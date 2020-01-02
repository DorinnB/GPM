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
Editor::inst( $db, 'outillages' )
->pkey( 'outillages.id_outillage' )
->fields(
  Field::inst( 'outillages.id_outillage'),
  Field::inst( 'outillages.outillage')    ->validator( 'Validate::notEmpty' ),
    Field::inst( 'outillages.dimA')     ->validator( 'Validate::numeric' )     ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.dimB')     ->validator( 'Validate::numeric' )     ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.dimC')     ->validator( 'Validate::numeric' )     ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.dimD')     ->validator( 'Validate::numeric' )     ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.mors')          ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.ref')          ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.matiere')          ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.cooling')     ->validator( 'Validate::numeric' )     ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.diam_percage')     ->validator( 'Validate::numeric' )     ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.cuivre')     ->validator( 'Validate::numeric' )     ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.po')          ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.jobnumber')        ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.dateService')
      ->validator( 'Validate::dateFormat', array(
          "format"  => Format::DATE_ISO_8601,
          "message" => "Please enter a date in the format yyyy-mm-dd"
        ) )
      ->getFormatter( 'Format::date_sql_to_format', Format::DATE_ISO_8601 )
      ->setFormatter( 'Format::date_format_to_sql', Format::DATE_ISO_8601 ),
    Field::inst( 'outillages.dateHS')
      ->validator( 'Validate::dateFormat', array(
        "format"  => Format::DATE_ISO_8601,
        "message" => "Please enter a date in the format yyyy-mm-dd"
      ) )
      ->getFormatter( 'Format::date_sql_to_format', Format::DATE_ISO_8601 )
      ->setFormatter( 'Format::date_format_to_sql', Format::DATE_ISO_8601 ),
    Field::inst( 'outillages.comments')         ->setFormatter( 'Format::ifEmpty', null ),
    Field::inst( 'outillages.outillage_actif')     ->validator( 'Validate::numeric' )     ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'outillages.id_outillage_type' )
      ->options( Options::inst()
          ->table( 'outillage_types' )
          ->value( 'id_outillage_type' )
          ->label( 'outillage_type' )
      ),
  Field::inst( 'outillage_types.outillage_type' ),
  Field::inst( 'outillage_types.id_outillage_type' ),
  Field::inst( 'machines.machine' )

  )
  ->leftJoin( 'outillage_types',     'outillage_types.id_outillage_type',          '=', 'outillages.id_outillage_type' )
  ->leftJoin('postes', 'postes.id_outillage_top','=','outillages.id_outillage OR postes.id_outillage_bot = outillages.id_outillage')
  ->leftJoin( 'machines', 'machines.id_machine', '=', 'postes.id_machine' )

  ->where( function($q) {
    $q->where ('id_poste', '(SELECT max(p1.id_poste) FROM postes p1 WHERE p1.id_outillage_top = outillages.id_outillage OR p1.id_outillage_bot = outillages.id_outillage)', 'IN', false);
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

    $oPoste->newPosteOther("id_outillage_top");

  } )

  ->process($_POST)
  ->json();

  /*
  select id_machine, id_outillage from outillages LEFT JOIN postes ON postes.id_outillage_top=outillages.id_outillage OR postes.id_outillage_bot=outillages.id_outillage where id_poste in (select max(id_poste) from postes group by id_machine) ORDER BY `postes`.`id_machine` ASC
  insert into postes (id_outillage_top, id_machine) select id_outillage, 29 from outillages LEFT JOIN postes ON postes.id_outillage_top=outillages.id_outillage OR postes.id_outillage_bot=outillages.id_outillage where id_poste not in (select max(id_poste) from postes group by id_machine)
  */
  ?>
