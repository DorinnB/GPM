<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



// Rendre votre modèle accessible
include '../models/invoice-model.php';

//var_dump($_POST);

//on extrait id_tbljob et le commentaire invoiceJob
$id_tbljob=$_POST['id_tbljob'];
unset($_POST['id_tbljob']);
//on met a jour le commentaire invoice

$invoice_lang=($_POST['invoice_lang']=="true")?0:1;
unset($_POST['invoice_lang']);
$invoice_currency=($_POST['invoice_currency']=="true")?0:1;
unset($_POST['invoice_currency']);


$invoice_commentaire=$_POST['invoice_commentaire'];
unset($_POST['invoice_commentaire']);
$oInvoice = new InvoiceModel($db);
$oInvoice->updateInvoiceComments($invoice_lang,$invoice_currency,$invoice_commentaire, $id_tbljob);



//pour chaque ligne d'invoiceLine recu
foreach ($_POST as $posts) {
	$datapost = array();
	parse_str($posts, $datapost);	//$datapost=l'array

	//var_dump($datapost);

	if ($datapost['id_invoiceLine']>0) {	//update d'une ligne existante

		if ( $datapost['toDelete']>0) {	//on efface la ligne

			$oInvoice = new InvoiceModel($db);
			$oInvoice->id_invoiceLine=$datapost['id_invoiceLine'];

			$oInvoice->deleteInvoiceLine();
			unset($oInvoice);
		}
		else {
			$oInvoice = new InvoiceModel($db);
			$oInvoice->id_invoiceLine=$datapost['id_invoiceLine'];

			$oInvoice->pricingList=$datapost['pricingList'];
			$oInvoice->qteUser=$datapost['qteUser'];
			$oInvoice->priceUnit=$datapost['priceUnit'];

			$oInvoice->updateInvoiceLine();
			unset($oInvoice);
		}

	}
	elseif ($datapost['newEntry']>=0) {	//ajout d'une ligne

		if ( $datapost['toDelete']>0) {	//on efface la ligne

			$oInvoice = new InvoiceModel($db);
			$oInvoice->id_invoiceLine=$datapost['id_invoiceLine'];

			$oInvoice->deleteInvoiceLine();
			unset($oInvoice);
		}
		else {

			$oInvoice = new InvoiceModel($db);
			$oInvoice->id_pricingList=$datapost['id_pricingList'];
			$oInvoice->id_info_job=$datapost['id_info_job'];
			$oInvoice->id_tbljob=$datapost['id_tbljob'];

			$oInvoice->pricingList=$datapost['pricingList'];
			$oInvoice->qteUser=$datapost['qteUser'];
			$oInvoice->priceUnit=$datapost['priceUnit'];

			$oInvoice->addNewEntry();
			unset($oInvoice);
		}
	}



}



?>
