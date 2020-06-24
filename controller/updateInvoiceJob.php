<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



// Rendre votre modèle accessible
include '../models/invoice-model.php';

//var_dump($_POST);

$oInvoice = new InvoiceModel($db);

//on extrait les POST et on les supprime pour pouvoir faire une boucle sur les post restant (les lignes d'invoice)
$oInvoice->id_tbljob=$_POST['id_tbljob'];
unset($_POST['id_tbljob']);

$oInvoice->order_val=$_POST['order_val'];
unset($_POST['order_val']);
$oInvoice->order_est=$_POST['order_est_mrsas'];
unset($_POST['order_est']);
$oInvoice->order_est_subc=$_POST['order_est_subc'];
unset($_POST['order_est_subc']);
$oInvoice->invoice_lang=($_POST['invoice_lang']=="true")?0:1;
unset($_POST['invoice_lang']);
$oInvoice->invoice_currency=($_POST['invoice_currency']=="true")?0:1;
unset($_POST['invoice_currency']);


$oInvoice->invoice_commentaire=$_POST['invoice_commentaire'];
unset($_POST['invoice_commentaire']);

$oInvoice->updateInvoiceComments();



//pour chaque ligne d'invoiceLine recu
foreach ($_POST as $posts) {
	$datapost = array();
	parse_str($posts, $datapost);	//$datapost=l'array


	if (isset($datapost['id_invoiceLine'])) {
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

				$oInvoice->prodCode=$datapost['prodCode'];
				$oInvoice->OpnCode=$datapost['OpnCode'];
				$oInvoice->type=$datapost['type'];
				$oInvoice->pricingList=$datapost['pricingList'];
				$oInvoice->qteUser=$datapost['qteUser'];
				$oInvoice->priceUnit=$datapost['priceUnit'];

				$oInvoice->addNewEntry();
				unset($oInvoice);
			}
		}
	}
	elseif (isset($datapost['id_payable'])) {
		$oInvoice = new InvoiceModel($db);
		$oInvoice->id_payable=$datapost['id_payable'];
		$oInvoice->applied=($datapost['checked']=="true")?"1":"0";
		$oInvoice->updateApplied();
		unset($oInvoice);
	}

}



?>
