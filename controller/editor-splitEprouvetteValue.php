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
  Field::inst( 'master_eprouvettes.prefixe')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'master_eprouvettes.nom_eprouvette'),
  Field::inst( 'eprouvettes.c_temperature')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_frequence')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_cycle_STL')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_frequence_STL')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_type_1_val')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_type_2_val')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_type_3_val')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_type_4_val')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.Cycle_min')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.runout')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.cycle_estime')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c_commentaire')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.d_commentaire')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.flag_qualite')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.q_commentaire')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.n_essai')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'enregistrementessais.n_fichier')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.dim_1')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.dim_2')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.dim_3')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'machines.machine')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'enregistrementessais.date')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.waveform')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.Cycle_STL')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.Cycle_final')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.Rupture')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.Fracture')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.temps_essais')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.dilatation')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.E_RT')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c1_E_montant')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c1_max_strain')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c1_min_strain')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c1_max_stress')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c1_min_stress')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c2_cycle')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c2_E_montant')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c2_max_stress')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c2_min_stress')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c2_max_strain')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c2_min_strain')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c2_calc_inelastic_strain')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.c2_meas_inelastic_strain')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.Ni')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.Nf75')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.val_1')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.val_2')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.val_3')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.val_4')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.val_5')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.val_6')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.d_checked')->setFormatter( 'Format::nullEmpty' ),
    Field::inst( 'eprouvettes.currentBlock')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.d_modif' )->set( Field::SET_EDIT )

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
    ->field( 'eprouvettes.d_modif' )
    ->setValue( $_COOKIE['id_user'] );
  } )
  //suppression du check value
  ->on( 'preEdit', function ( $editor, $values ) {
    $editor
    ->field( 'eprouvettes.d_checked' )
    ->setValue( 0 );
  } )
  ->process($_POST)
  ->json();
  ?>
