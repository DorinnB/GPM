<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<h3>
	Processing...<br/>
	Make take up to few seconds</br>

</h3>
<a href="../index.php?page=split&id_tbljob=<?= $_POST['id_tbljob'] ?>">click here if you aren't redirected</a>
<br/><br/>
<div id="spoiler" style="display:none">

	<?php


	var_dump($_POST);



	// Rendre votre modèle accessible
	include '../models/split-model.php';
	//$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);

	include '../models/eprouvettes-model.php';
	include '../models/master_eprouvettes-model.php';
	include '../models/workflow.class.php';
	// Rendre votre modèle accessible
	include '../models/infojob-model.php';
	// Création d'une instance
	$oJob = new InfoJob($db,$_POST['id_tbljob']);


	//envoi des attributs du job
	$oJob->job=$_POST['job'];
	$oJob->customer=$_POST['ref_customer'];
	$oJob->id_contact=$_POST['id_contact'];
	$oJob->id_contact2=$_POST['id_contact2'];
	$oJob->ref_matiere=$_POST['ref_matiere'];
	$oJob->id_contact3=$_POST['id_contact3'];
	$oJob->id_matiere_std=$_POST['id_matiere_std'];
	$oJob->id_contact4=$_POST['id_contact4'];
	$oJob->po_number=$_POST['po_number'];
	$oJob->pricing=$_POST['pricing'];
	$oJob->devis=$_POST['devis'];
	$oJob->instruction=$_POST['instruction'];
	$oJob->commentaire=$_POST['commentaire'];
	$oJob->available_expected=$_POST['available_expected'];
	$oJob->info_job_actif=1;


	// Retour de l'update si erreur
	$oJob->updateInfoJob();
	// ATTENTION : probleme si on change pas les données infos jobs. Il ne faut pas de message d'erreur si 0 retour d'execute


	//Pour chaque nouveau split
	foreach (explode("&", $_POST['dataSplit']) as $value) {
		$idTypeSplit=explode("=", $value);
		$nSplit=explode("-", $idTypeSplit[0]);
		//On n'écrase pas la valeur au 2eme passage des th (bug datatables ?)
		if(!isset($newSplit[$nSplit[1]])) {
			//On ne garde que les split définit
			if($idTypeSplit[1]!=0){
				$newSplit[$nSplit[1]]=$idTypeSplit[1];

				//creation d'un Objet Split
				$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);
				//insertion des attributs
				$oSplit->id_info_job=$oJob->getInfoJob()['id_info_job'];
				$oSplit->id_test_type=$idTypeSplit[1];
				//appel de la method de creation et recuperation de l'id
				$split[$nSplit[1]]=$oSplit->newSplit();
			}
		}
	}
	//var_dump($newSplit);



	$dataEp = array();
	parse_str($_POST['dataEp'], $dataEp);
	var_dump($dataEp);


	//Pour chaque elements d'eprouvette
	foreach ($dataEp as $key => $value) {
		//  echo $value.'<br/>';
		$valeur=$value;
		$ref_eprouvette=explode("-", $key)[0];
		$attribut=explode("-", $key)[1];

		if ($attribut=="prefixe" || $attribut=="nom_eprouvette" || $attribut=="id_dwg"){
			$ep[$ref_eprouvette][$attribut]=$valeur;
		}
	}



	//determination de l'id master eprouvette
	foreach ($ep as $key=>$value) {
		if (explode("_", $key)[0]=='existingEp') {
			$ep[$key]['id_master_eprouvette']=explode("_", $key)[1];
			//on update le prefixe, nom et id_dwg
			$oMasterEprouvette = new LstMasterEprouvettesModel($db);
			$oMasterEprouvette->prefixe = $ep[$key]['prefixe'];
			$oMasterEprouvette->nom_eprouvette = $ep[$key]['nom_eprouvette'];
			$oMasterEprouvette->id_dwg = $ep[$key]['id_dwg'];
			$oMasterEprouvette->updateMasterEp($ep[$key]['id_master_eprouvette']);
		}
		else {
			$oMasterEprouvette = new LstMasterEprouvettesModel($db);
			$oMasterEprouvette->id_info_job=  $oJob->getInfoJob()['id_info_job'];
			$oMasterEprouvette->prefixe = $ep[$key]['prefixe'];
			$oMasterEprouvette->nom_eprouvette = $ep[$key]['nom_eprouvette'];
			$oMasterEprouvette->id_dwg = $ep[$key]['id_dwg'];

			$ep[$key]['id_master_eprouvette']= $oMasterEprouvette->addMasterEp();
		}
	}
	var_dump($ep);


	//pour chaque split on regarde si l'on doit créer ou supprimer une eprouvette
	foreach ($dataEp as $key => $value) {
		//  echo $value.'<br/>';
		$valeur=$value;
		$ref_eprouvette=explode("-", $key)[0];
		$attribut=explode("-", $key)[1];

		if ($attribut=="prefixe" || $attribut=="nom_eprouvette" || $attribut=="id_dwg"){
			echo '-<br/>';
		}
		else{

			$splitexistant=explode("_",$attribut)[0];
			$refsplit=explode("_",$attribut)[1];
			if ($splitexistant=="existingSplit") {
				echo ' existant : '.$refsplit;
				if ($valeur==0) {
					echo ' check puis del ep ';
					echo $ep[$ref_eprouvette]['id_master_eprouvette'];
					$oEprouvette = new LstEprouvettesModel($db,$refsplit);
					$oEprouvette->id_master = $ep[$ref_eprouvette]['id_master_eprouvette'];
					$oEprouvette->delEp();
				}
				elseif ($valeur==1) {
					echo ' check puis add ep ';
					$oEprouvette = new LstEprouvettesModel($db,$refsplit);
					$oEprouvette->prefixe = $ep[$ref_eprouvette]['prefixe'];
					$oEprouvette->nom_eprouvette = $ep[$ref_eprouvette]['nom_eprouvette'];
					$oEprouvette->id_dwg = $ep[$ref_eprouvette]['id_dwg'];
					$oEprouvette->id_master=$ep[$ref_eprouvette]['id_master_eprouvette'];
					$oEprouvette->addEp();
				}
			}
			elseif ($splitexistant=="newsplit") {
				if (isset($split[$refsplit])) {
					echo ' NEW SPLIT : '.$split[$refsplit];
					if ($valeur==0) {
						echo ' pas dans ce nouveau split ';
					}
					elseif ($valeur==1) {
						echo ' add ep ';
						$oEprouvette = new LstEprouvettesModel($db,$split[$refsplit]);
						$oEprouvette->prefixe = $ep[$ref_eprouvette]['prefixe'];
						$oEprouvette->nom_eprouvette = $ep[$ref_eprouvette]['nom_eprouvette'];
						$oEprouvette->id_dwg = $ep[$ref_eprouvette]['id_dwg'];
						$oEprouvette->id_master=$ep[$ref_eprouvette]['id_master_eprouvette'];
						$oEprouvette->addEp();
					}
				}
				else {
					echo " pas de new n ".$refsplit;
				}
			}
			else {
				echo " ERREUR";
			}
			echo '<br/>';

		}
	}


	//Pour chaque eprouvette supprimée on supprime le master
	foreach (explode("&", $_POST['deletedEp']) as $value) {
		if ($value!="") {
			echo 'suppression de '.$value.'<br/>';
			$oMasterEprouvette = new LstMasterEprouvettesModel($db);
			$oMasterEprouvette->delMasterEp($value);
		}
	}





	//pour chaque split on crée un array avec id, splitnumber et on trace l'ordre (la phase)
	$phase=1;
	foreach (explode("&", $_POST['dataSplitNumber']) as $value) {

		//On ne gere pas le 2eme passage des dataSplitNumber (bug datatables ?)
		$valeur=explode("=", $value)[1];
		$refSplit=explode("=", $value)[0];
		$nSplit=explode('_', $refSplit)[1];

		if (explode('_', $refSplit)[0]=="existingSplit") {
			if(!isset($splitOrder[$nSplit])) {
				$splitOrder[$nSplit]=$valeur;
				echo "<br/>exists : ".$nSplit;
				$oSplitNumber = new LstSplitModel($db,$nSplit);
				$oSplitNumber->splitNumber = $valeur;
				$oSplitNumber->updateSplitNumber($phase++);
			}
		}
		else {
			if(!isset($splitOrder[$nSplit])) {
				$splitOrder[$nSplit]=$valeur;
				if (isset($split[$nSplit]))  {
					echo "<br/>new : ".$nSplit." donc id = ".$split[$nSplit];
					$oSplitNumber = new LstSplitModel($db,$split[$nSplit]);
					$oSplitNumber->splitNumber = $valeur;
					$oSplitNumber->updateSplitNumber($phase++);
				}
				else {
					echo "<br/>new : ".$nSplit." mais pas d'id";
				}
			}
		}

	}
	var_dump($splitOrder);


	//suppression des split vide
	$oWorflow = new WORKFLOW($db,$_POST['id_tbljob']);
	foreach ($oWorflow->getEmptySplit() as $split) {
		var_dump($split);
		if ($split['nbep']==0) {
			echo 'sup de '. $split['id_tbljob'];
			$oWorflow->delSplit($split['id_tbljob']);
		}
	}


include 'createJobFolder-controller.php';

	?>

</div>

<button title="Click to show/hide content" type="button" onclick="if(document.getElementById('spoiler') .style.display=='none') {document.getElementById('spoiler') .style.display=''}else{document.getElementById('spoiler') .style.display='none'}">Show/hide</button>

<script type='text/javascript'>document.location.replace('../index.php?page=split&id_tbljob=<?= $_POST['id_tbljob'] ?>');</script>
