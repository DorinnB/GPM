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
Editor::inst( $db, 'quotation' )
->pkey( 'quotation.id_quotation' )
->fields(
  Field::inst( 'quotation.id_quotation'),

  Field::inst( 'quotation.customer' )
    ->options( Options::inst()
    ->table( 'entreprises' )
    ->value( 'id_entreprise' )
    ->label( 'id_entreprise' )
  ),
  Field::inst( 'entreprises.entreprise_abbr'),


  Field::inst( 'quotation.id_contact' )
    ->options( Options::inst()
    ->table( 'contacts' )
    ->value( 'id_contact' )
    ->label( 'id_contact' )
  ),
  Field::inst( 'contacts.prenom'),
  Field::inst( 'contacts.nom'),



  Field::inst( 'quotation.id_preparer' )
    ->options( Options::inst()
    ->table( 'techniciens' )
    ->value( 'id_technicien' )
    ->label( 'technicien' )
  ),
  Field::inst( 'techniciens.technicien'),
  Field::inst( 'quotation.id_checker' ),
  Field::inst( 'quotation.rfq'),
  Field::inst( 'quotation.mrsasComments'),


  Field::inst( 'quotation.ver'),
  Field::inst( 'quotation.currency'),
  Field::inst( 'quotation.creation_date'),
  Field::inst( 'quotation.quotation_date'),
  Field::inst( 'quotation.quotationlist'),
  Field::inst( 'info_jobs.id_info_job'),
  Field::inst( 'info_jobs.job'),
  Field::inst( 'quotation.quotation_actif')
  )

  ->leftJoin( 'entreprises',     'entreprises.id_entreprise',          '=', 'quotation.customer' )
  ->leftJoin( 'contacts',     'contacts.id_contact',          '=', 'quotation.id_contact' )
  ->leftJoin( 'techniciens',     'techniciens.id_technicien',          '=', 'quotation.id_preparer' )
  ->leftJoin( 'info_jobs',     'info_jobs.devis=quotation.id_quotation OR (SUBSTR(info_jobs.devis, 2, 2)=DATE_FORMAT(quotation.creation_date, "%y") AND SUBSTR(info_jobs.devis, 5, 5)=quotation.id_quotation) OR (SUBSTR(info_jobs.devis, 2, 2)=DATE_FORMAT(quotation.creation_date, "%y") AND SUBSTR(info_jobs.devis, 4, 5)=quotation.id_quotation)','','' )

  ->where('quotation.creation_date',$_POST['dateStartQuotation'],'>=')

  ->process($_POST)
  ->json();
  ?>
