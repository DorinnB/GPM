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
Editor::inst( $db, 'contacts' )
->pkey( 'contacts.id_contact' )
->fields(
  Field::inst( 'contacts.genre'),

  Field::inst( 'contacts.adresse'),

  Field::inst( 'contacts.prenom'),
  Field::inst( 'contacts.nom'),
  Field::inst( 'contacts.departement'),
  Field::inst( 'contacts.rue1'),
  Field::inst( 'contacts.rue2'),
  Field::inst( 'contacts.ville'),
  Field::inst( 'contacts.pays'),

  Field::inst( 'contacts.email'),
  Field::inst( 'contacts.telephone'),
  Field::inst( 'contacts.poste'),
  Field::inst( 'contacts.ref_customer' )
      ->options( Options::inst()
          ->table( 'entreprises' )
          ->value( 'id_entreprise' )
          ->label( 'entreprise' )
      ),
  Field::inst( 'entreprises.entreprise' ),
  Field::inst( 'entreprises.id_entreprise' ),
  Field::inst( 'contacts.contact_actif')
  )
  ->leftJoin( 'entreprises',     'entreprises.id_entreprise',          '=', 'contacts.ref_customer' )


  ->process($_POST)
  ->json();
  ?>
