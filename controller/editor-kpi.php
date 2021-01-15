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
Editor::inst( $db, 'kpi' )
->pkey( 'kpi.date_kpi' )
->fields(
  Field::inst( 'kpi.backlogMRSAS')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'kpi.backlogTOTAL')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'kpi.cdeMRSAS')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),

  Field::inst( 'kpi.obj_prodMRSAS')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),

    Field::inst( 'kpi.obj_invMRSAS')
    ->validator( 'Validate::numeric' )
    ->setFormatter( 'Format::ifEmpty', null )
  )

  ->leftJoin( 'ubr', 'DATE_FORMAT(ubr.date_ubr, "%Y-%m")', '=', 'DATE_FORMAT(kpi.date_kpi, "%Y-%m")' )


  ->process($_POST)
  ->json();
  ?>
