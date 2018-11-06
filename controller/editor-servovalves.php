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
Editor::inst( $db, 'servovalves' )
->pkey( 'servovalves.id_servovalve' )
->fields(
  Field::inst( 'servovalves.servovalve')
    ->validator( 'Validate::notEmpty' ),
  Field::inst( 'servovalves.servovalve_model')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.manufacture')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.servovalve_capacity')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.fixing_type')
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'servovalves.manufacture_date')
    ->validator( 'Validate::dateFormat', array(
        "format"  => Format::DATE_ISO_8601,
        "message" => "Please enter a date in the format yyyy-mm-dd"
      ) )
    ->getFormatter( 'Format::date_sql_to_format', Format::DATE_ISO_8601 )
    ->setFormatter( 'Format::date_format_to_sql', Format::DATE_ISO_8601 )
    ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'machines1.machine'),
  Field::inst( 'machines2.machine'),
  Field::inst( 'servovalves.servovalve_actif')
  )

->leftJoin( 'postes as postes1', 'postes1.id_servovalve1', '=', 'servovalves.id_servovalve' )
->leftJoin( 'postes as postes2', 'postes2.id_servovalve2', '=', 'servovalves.id_servovalve' )

->leftJoin( 'machines as machines1', 'machines1.id_machine', '=', 'postes1.id_machine' )
->leftJoin( 'machines as machines2', 'machines2.id_machine', '=', 'postes2.id_machine' )

->where( function ( $q ) {
  $q->where( 'postes1.id_poste', '(SELECT max(id_poste) as id_poste
    FROM machines
    LEFT JOIN postes p ON p.id_machine=machines.id_machine
    WHERE machines.machine_actif=1
    GROUP BY machines.id_machine
    ORDER BY machines.machine ASC)', 'IN', false );
  $q->or_where( 'postes2.id_poste', '(SELECT max(id_poste) as id_poste
    FROM machines
    LEFT JOIN postes p ON p.id_machine=machines.id_machine
    WHERE machines.machine_actif=1
    GROUP BY machines.id_machine
    ORDER BY machines.machine ASC)', 'IN', false );
})
  ->process($_POST)
  ->json();
  ?>
