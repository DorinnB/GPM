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
DataTables\Editor\Validate,
DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'quotations' )
->pkey( 'quotations.id_quotation' )
->fields(
  Field::inst( 'quotations.id_quotation'),

  Field::inst( 'quotations.id_customer' )
    ->options( Options::inst()
    ->table( 'entreprises' )
    ->value( 'id_entreprise' )
    ->label( 'id_entreprise' )
  ),
  Field::inst( 'entreprises.entreprise_abbr'),


  Field::inst( 'quotations.id_contact' )
    ->options( Options::inst()
    ->table( 'contacts' )
    ->value( 'id_contact' )
    ->label( 'id_contact' )
  ),
  Field::inst( 'contacts.prenom'),
  Field::inst( 'contacts.nom'),



  Field::inst( 'quotations.id_user' )
    ->options( Options::inst()
    ->table( 'techniciens' )
    ->value( 'id_technicien' )
    ->label( 'technicien' )
  ),
  Field::inst( 'techniciens.technicien'),

  Field::inst( 'quotations.quotation_date'),
  Field::inst( 'quotations.quotation_actif')
  )

  ->leftJoin( 'entreprises',     'entreprises.id_entreprise',          '=', 'quotations.id_customer' )
  ->leftJoin( 'contacts',     'contacts.id_contact',          '=', 'quotations.id_contact' )

  ->leftJoin( 'techniciens',     'techniciens.id_technicien',          '=', 'quotations.id_user' )


  ->process($_POST)
  ->json();
  ?>
