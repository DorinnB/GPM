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
Editor::inst( $db, 'dummies' )
->pkey( 'dummies.id_dummie' )
->fields(
  Field::inst( 'dummies.id_dummie'),
  Field::inst( 'dummies.ID'),
  Field::inst( 'dummies.material'),
  Field::inst( 'dummies.ref'),
  Field::inst( 'dummies.dim1'),
  Field::inst( 'dummies.dim2'),
  Field::inst( 'dummies.dim3'),
  Field::inst( 'dummies.tc'),
  Field::inst( 'dummies.comments'),
  Field::inst( 'dummies.dummie_actif'),


  Field::inst( 'dummies.id_mat' )
  ->options( Options::inst()
  ->table( 'matieres' )
  ->value( 'id_matiere' )
  ->label( 'matiere' )),
  Field::inst( 'matieres.matiere' ),
  Field::inst( 'matieres.type_matiere' ),

  Field::inst( 'dummies.id_drawing' )
  ->options( Options::inst()
  ->table( 'dessins' )
  ->value( 'id_dessin' )
  ->label( 'dessin' )),
  Field::inst( 'dessins.dessin' ),
  Field::inst( 'dessins.gripDimension' ),
  Field::inst( 'dessin_types.dessin_type' )

  )
  ->leftJoin( 'matieres',     'matieres.id_matiere',          '=', 'dummies.id_mat' )
  ->leftJoin( 'dessins',     'dessins.id_dessin',          '=', 'dummies.id_drawing' )
  ->leftJoin( 'dessin_types',     'dessin_types.id_dessin_type',          '=', 'dessins.id_dessin_type' )


  ->process($_POST)
  ->json();
  ?>
