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
  Field::inst( 'eprouvettes.id_eprouvette'),
  Field::inst( 'master_eprouvettes.id_master_eprouvette'),
  Field::inst( 'master_eprouvettes.prefixe'),
  Field::inst( 'master_eprouvettes.nom_eprouvette'),
  Field::inst( 'eprouvettes.n_essai'),
  Field::inst( 'eprouvettes.c_temperature')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_frequence')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_type_1_val')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_type_2_val')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_type_3_val')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_type_4_val')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.runout')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.cycle_estime')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_commentaire')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'enregistrementessais.n_fichier'),
  Field::inst( 'machines.machine'),
  Field::inst( 'enregistrementessais.date'),
  Field::inst( 'eprouvettes.Cycle_final'),
  Field::inst( 'eprouvettes.Rupture'),
  Field::inst( 'eprouvettes.Fracture'),
  Field::inst( 'eprouvettes.c_modif' )->set( Field::SET_EDIT ),
  Field::inst( 'eprouvettes.c_checked' )->set( Field::SET_EDIT )
  )

  ->leftJoin( 'master_eprouvettes', 'master_eprouvettes.id_master_eprouvette', '=', 'eprouvettes.id_master_eprouvette' )
    ->leftJoin( 'tbljobs', 'tbljobs.id_tbljob', '=', 'eprouvettes.id_job' )
    ->leftJoin( 'enregistrementessais', 'enregistrementessais.id_eprouvette', '=', 'eprouvettes.id_eprouvette' )
    ->leftJoin( 'prestart', 'prestart.id_prestart', '=', 'enregistrementessais.id_prestart' )
    ->leftJoin( 'postes', 'postes.id_poste', '=', 'prestart.id_poste' )
    ->leftJoin( 'machines', 'machines.id_machine', '=', 'postes.id_machine' )

  ->where('id_job',(isset($_POST['idJob'])?$_POST['idJob']:0))
    ->where('eprouvette_actif',1)
    ->where('master_eprouvette_actif',1)


//enregistrement du user effectuant l'update
    ->on( 'preEdit', function ( $editor, $values ) {
        $editor
            ->field( 'eprouvettes.c_modif' )
            ->setValue( $_COOKIE['id_user'] );
    } )
    ->on( 'preEdit', function ( $editor, $values ) {
        $editor
            ->field( 'eprouvettes.c_checked' )
            ->setValue( -$_COOKIE['id_user'] );
    } )
    ->on( 'preEdit', function ( $editor, $values ) {
        $editor
            ->field( 'eprouvettes.c_frequence' )
            ->setValue( 1 );
    } )

  ->process($_POST)
  ->json();
  ?>
