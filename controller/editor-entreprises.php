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
Editor::inst( $db, 'entreprises' )
->pkey( 'entreprises.id_entreprise' )
->fields(
  Field::inst( 'entreprises.id_entreprise'),
  Field::inst( 'entreprises.entreprise'),
  Field::inst( 'entreprises.entreprise_abbr'),
  Field::inst( 'entreprises.activity_area' )
  ->options( function () {
    //get activity_area_list on config.ini then convert to select list
    $ini = parse_ini_file('../var/config.ini');
    foreach ($ini['activity_area_list'] as $key => $value) {
      $b[]=array('value' => $value, 'label' => $value );
    }
    return $b;
  } ),
  Field::inst( 'entreprises.VAT'),
  Field::inst( 'entreprises.MRSASRef'),
  Field::inst( 'entreprises.billing_rue1'),
  Field::inst( 'entreprises.billing_rue2'),
  Field::inst( 'entreprises.billing_ville'),
  Field::inst( 'entreprises.billing_pays'),
  Field::inst( 'entreprises.entreprise_actif')
  )


  ->process($_POST)
  ->json();
  ?>
