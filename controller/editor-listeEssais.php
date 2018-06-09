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
Editor::inst( $db, 'enregistrementessais' )
->pkey( 'enregistrementessais.n_fichier' )
->fields(
  Field::inst( 'enregistrementessais.n_fichier'),
  Field::inst( 'eprouvettes.n_essai'),
  Field::inst( 'enregistrementessais.date'),
  Field::inst( 'test_type.test_type_abbr'),
  Field::inst( 'eprouvettes.c_temperature'),
  Field::inst( 'info_jobs.customer'),
  Field::inst( 'info_jobs.job'),
  Field::inst( 'tbljobs.split'),
  Field::inst( 'master_eprouvettes.prefixe'),
  Field::inst( 'master_eprouvettes.nom_eprouvette'),
  Field::inst( 'machines.machine'),
  Field::inst( 'op.technicien'),
  Field::inst( 'chk.technicien'),
  Field::inst( 'tbljobs.id_tbljob'),
  Field::inst( 'extensometres.extensometre'),
  Field::inst( 'eprouvettes.flag_qualite'),
  Field::inst( 'eprouvettes.temps_essais')
  )
->leftJoin( 'eprouvettes',     'eprouvettes.id_eprouvette',          '=', 'enregistrementessais.id_eprouvette' )
->leftJoin( 'master_eprouvettes',     'master_eprouvettes.id_master_eprouvette',          '=', 'eprouvettes.id_master_eprouvette' )
->leftJoin( 'tbljobs',     'tbljobs.id_tbljob',          '=', 'eprouvettes.id_job' )
->leftJoin( 'info_jobs',     'info_jobs.id_info_job',          '=', 'tbljobs.id_info_job' )
->leftJoin( 'test_type',     'test_type.id_test_type',          '=', 'tbljobs.id_type_essai' )
->leftJoin( 'prestart',     'prestart.id_prestart',          '=', 'enregistrementessais.id_prestart' )
->leftJoin( 'postes',     'postes.id_poste',          '=', 'prestart.id_poste' )
->leftJoin( 'machines',     'machines.id_machine',          '=', 'postes.id_machine' )
->leftJoin( 'extensometres',     'extensometres.id_extensometre',          '=', 'postes.id_extensometre' )
->leftJoin( 'chauffages',     'chauffages.id_chauffage',          '=', 'postes.id_chauffage' )
->leftJoin( 'techniciens as op',     'op.id_technicien',          '=', 'enregistrementessais.id_operateur' )
->leftJoin( 'techniciens as chk',     'chk.id_technicien',          '=', 'enregistrementessais.id_controleur' )

->where('n_fichier',47000,'>')
->where('enregistrementessais.id_eprouvette', null, '!=' )

->process($_POST)
->json();
?>
