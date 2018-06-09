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
Editor::inst( $db, 'eprouvettes' )
->pkey( 'eprouvettes.id_eprouvette' )
->fields(
  Field::inst( 'enregistrementessais.n_fichier'),
  Field::inst( 'eprouvettes.n_essai'),
  Field::inst( 'info_jobs.customer'),
  Field::inst( 'info_jobs.job'),
  Field::inst( 'tbljobs.split'),
  Field::inst( 'master_eprouvettes.prefixe'),
  Field::inst( 'master_eprouvettes.nom_eprouvette'),
  Field::inst( 'tbljobs.id_tbljob'),
  Field::inst( 'machines.machine'),
  Field::inst( 'eprouvettes.d_commentaire'),
  Field::inst( 'eprouvettes.q_commentaire'),
  Field::inst( 'eprouvettes.valid'),
  Field::inst( 'eprouvettes.flag_qualite')
//  Field::inst( 'techniciens.technicien')


  )
  ->leftJoin( 'enregistrementessais',     'enregistrementessais.id_eprouvette',          '=', 'eprouvettes.id_eprouvette' )
  ->leftJoin( 'master_eprouvettes',     'master_eprouvettes.id_master_eprouvette',          '=', 'eprouvettes.id_master_eprouvette' )
  ->leftJoin( 'tbljobs',     'tbljobs.id_tbljob',          '=', 'eprouvettes.id_job' )
  ->leftJoin( 'info_jobs',     'info_jobs.id_info_job',          '=', 'tbljobs.id_info_job' )
  ->leftJoin( 'prestart',     'prestart.id_prestart',          '=', 'enregistrementessais.id_prestart' )
  ->leftJoin( 'postes',     'postes.id_poste',          '=', 'prestart.id_poste' )
  ->leftJoin( 'machines',     'machines.id_machine',          '=', 'postes.id_machine' )
  //->leftJoin( 'techniciens',     'techniciens.id_technicien',          '=', 'eprouvettes.flag_qualite' )


  ->join(
          Mjoin::inst( 'incident_causes' )
              ->link( 'eprouvettes.id_eprouvette', 'flagQualite_incidentCauses.id_eprouvette' )
              ->link( 'incident_causes.id_incident_cause', 'flagQualite_incidentCauses.id_incident_cause' )
              ->order( 'incident_cause asc' )
              ->fields(
                  Field::inst( 'id_incident_cause' )
                      ->validator( 'Validate::required' )
                      ->options( Options::inst()
                          ->table( 'incident_causes' )
                          ->value( 'id_incident_cause' )
                          ->label( 'incident_cause' )
                      ),
                  Field::inst( 'incident_cause' )
              )
)


  ->where('flag_qualite',0,(isset($_POST['filtre']))?$_POST['filtre']:"!=")


  ->process($_POST)
  ->json();
  ?>
