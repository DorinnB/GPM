<?php
include_once( "../DataTables/Editor-PHP-1.9.2/lib/DataTables.php" );

$test=array(0=>array('value'=>0, 'label'=>'UNREGISTERED YET'));

$noms = $db
    ->select( 'contacts', ['id_contact as value', 'CONCAT(LEFT(prenom,1), ". ", nom) as label'], ['ref_customer' => $_REQUEST['values']['quotations.id_customer']] )
    ->fetchAll();

$result = array_merge($test, $noms);

echo json_encode( [
    'options' => [
        'quotations.id_contact' => $result
    ]
] );

?>
