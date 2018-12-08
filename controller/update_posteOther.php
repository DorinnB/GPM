<h3>
	Processing...<br/>
	Make take up to few seconds</br>

</h3>
<a href="index.php">click here if you aren't redirected</a>
<br/><br/>
<?php
//var_dump($_POST);

// Rendre votre modèle accessible
include 'models/poste-model.php';
// Création d'une instance
$oPoste = new PosteModel($db, 0);

$oPoste->itemValue=$_POST['id'];
$oPoste->id_machine=$_POST['id_machine'];
$oPoste->id_operateur=$_COOKIE['id_user'];

$oPoste->newPosteOther($_POST['item']);


if ($_POST['item']=="id_extensometre") {
  include 'models/lstExtensometre-model.php';
  $oExtensometre = new ExtensometreModel($db);
  $oExtensometre->id=$_POST['id'];
  $oExtensometre->extensometre_comment=$_POST['extensometre_comment'];
  $oExtensometre->updateExtensometre();
}
elseif ($_POST['item']=="id_chauffage") {
  include 'models/lstChauffage-model.php';
  $oChauffage = new ChauffageModel($db);
  $oChauffage->id=$_POST['id'];
  $oChauffage->chauffage_comment=$_POST['chauffage_comment'];
  $oChauffage->updateHeating();
}
elseif ($_POST['item']=="id_outillage_top") {
  include 'models/lstOutillage-model.php';
  $oOutillage = new OutillageModel($db);
  $oOutillage->id=$_POST['id'];
  $oOutillage->comments=$_POST['comments'];
  $oOutillage->updateOutillage();
}
elseif ($_POST['item']=="id_servovalve1") {
  include 'models/lstServovalve-model.php';
  $oServovalve = new ServovalveModel($db);
  $oServovalve->id=$_POST['id'];
  $oServovalve->comments=$_POST['servovalve_comment'];
  $oServovalve->updateServovalve();
}
elseif ($_POST['item']=="id_cell_load") {
  include 'models/lstCellLoad-model.php';
$oLstCellLoad = new CellLoadModel($db);
  $oLstCellLoad->id=$_POST['id'];
  $oLstCellLoad->cell_load_comment=$_POST['cell_load_comment'];
  $oLstCellLoad->updateCell_load();
}

?>
<script type='text/javascript'>document.location.replace('<?= $_SERVER['HTTP_REFERER'] ?>');</script>
