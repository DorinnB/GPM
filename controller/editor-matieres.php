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
Editor::inst( $db, 'matieres' )
->pkey( 'matieres.id_matiere' )
->fields(
  Field::inst( 'matieres.matiere')
    ->validator( 'Validate::notEmpty' ),

  Field::inst( 'matieres.type_matiere')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'matieres.young')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'matieres.matiere_actif')
  )


  ->process($_POST)
  ->json();
  ?>
