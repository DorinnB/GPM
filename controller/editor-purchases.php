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
Editor::inst( $db, 'purchaserequests' )
->pkey( 'purchaserequests.id_purchaserequest' )
->fields(
  Field::inst( 'purchaserequests.id_purchaserequest'),

  Field::inst( 'purchaserequests.purchaserequest_date'),
  Field::inst( 'purchaserequests.description')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'purchaserequests.id_user')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'techniciens.technicien'),
  Field::inst( 'purchaserequests.supplier')
  ->validator( 'Validate::notEmpty' ),
  Field::inst( 'purchaserequests.usd')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'purchaserequests.euro')
  ->validator( 'Validate::numeric' )
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'purchaserequests.id_validator'),
  Field::inst( 't2.technicien'),
  Field::inst( 'purchases.id_purchase'),
  Field::inst( 'purchases.id_receipt'),
  Field::inst( 't3.technicien'),
  Field::inst( 'purchaserequests.job')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'purchaserequests.comments')
  ->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'purchases.generate'),
  Field::inst( 'payables.invoice'),

  Field::inst( 'payables.HT'),
  Field::inst( 'payables.USD')

  )
  ->leftJoin( 'purchases',     'purchases.id_purchaserequest',          '=', 'purchaserequests.id_purchaserequest' )

  ->leftJoin( 'techniciens',     'techniciens.id_technicien',          '=', 'purchaserequests.id_user' )
  ->leftJoin( 'techniciens as t2',     't2.id_technicien',          '=', 'abs(purchaserequests.id_validator)' )
  ->leftJoin( 'techniciens as t3',     't3.id_technicien',          '=', 'abs(purchases.id_receipt)' )

  ->leftJoin( 'payables',     'payables.purchase',          '=', 'purchases.id_purchase' )

  ->where('purchaserequests.purchaserequest_date',$_POST['dateStartPurchase'],'>=')

  //enregistrement du user effectuant l'update
  ->on( 'preCreate', function ( $editor, $values ) {
    $editor
    ->field( 'purchaserequests.id_user' )
    ->setValue( $_COOKIE['id_user'] );


  } )
  //enregistrement du user effectuant l'update
  ->on( 'postCreate', function ( $editor, $id, $values, $row ) {

    $_POST['sendTo']  ='jgalipaud@metcut.com';
    $_POST['subject'] = 'New POR registered';
    $_POST['body']    =
    'POR '.$id.'<br/>'.
    'Supplier : '.$values['purchaserequests']['supplier'].'<br/>'.
    'Description : '.$values['purchaserequests']['description'].'<br/>'.
    'Amount : '.($values['purchaserequests']['euro']>0?$values['purchaserequests']['euro'].' €':'$ '.$values['purchaserequests']['usd']).'<br/>'.
    'User : #'.$_COOKIE['id_user'];

    $_POST['altBody'] = $_POST['body'];

    include( 'sendEmail.php' );
  } )


  ->process($_POST)
  ->json();
  ?>
