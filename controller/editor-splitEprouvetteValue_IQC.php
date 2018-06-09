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

  Field::inst( 'eprouvettes.c_commentaire')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.d_commentaire')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.flag_qualite')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.q_commentaire')->setFormatter( 'Format::nullEmpty' ),


  Field::inst( 'annexe_iqc.dim1')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'annexe_iqc.dim2')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'annexe_iqc.dim3')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'annexe_iqc.marquage')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'annexe_iqc.surface')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'annexe_iqc.grenaillage')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'annexe_iqc.revetement')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'annexe_iqc.protection')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'annexe_iqc.autre')->setFormatter( 'Format::nullEmpty' ),

  Field::inst( 'eprouvettes.d_checked')->setFormatter( 'Format::nullEmpty' ),
  Field::inst( 'eprouvettes.d_modif' )->set( Field::SET_EDIT )

  )

  ->leftJoin( 'master_eprouvettes', 'master_eprouvettes.id_master_eprouvette', '=', 'eprouvettes.id_master_eprouvette' )
  ->leftJoin( 'tbljobs', 'tbljobs.id_tbljob', '=', 'eprouvettes.id_job' )

  ->leftJoin( 'annexe_iqc', 'annexe_iqc.id_annexe_iqc', '=', 'eprouvettes.id_eprouvette' )

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
