<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php
//var_dump($_POST);


// Rendre votre modèle accessible
include '../models/poste-model.php';
// Création d'une instance
$oPoste = new PosteModel($db, $_POST['id_poste']);

$oPoste->poste=$_POST['poste'];
$oPoste->id_cell_load=$_POST['id_cell_load'];
$oPoste->id_cell_displacement=$_POST['id_cell_displacement'];
$oPoste->id_extensometre=$_POST['id_extensometre'];
$oPoste->Disp_P=$_POST['Disp_P'];
$oPoste->Disp_i=$_POST['Disp_i'];
$oPoste->Disp_D=$_POST['Disp_D'];
$oPoste->Disp_Conv=$_POST['Disp_Conv'];
$oPoste->Disp_Sens=$_POST['Disp_Sens'];
$oPoste->Load_P=$_POST['Load_P'];
$oPoste->Load_i=$_POST['Load_i'];
$oPoste->Load_D=$_POST['Load_D'];
$oPoste->Load_Conv=$_POST['Load_Conv'];
$oPoste->Load_Sens=$_POST['Load_Sens'];
$oPoste->Strain_P=$_POST['Strain_P'];
$oPoste->Strain_i=$_POST['Strain_i'];
$oPoste->Strain_D=$_POST['Strain_D'];
$oPoste->Strain_Conv=$_POST['Strain_Conv'];
$oPoste->Strain_Sens=$_POST['Strain_Sens'];
$oPoste->id_outillage_top=$_POST['id_outillage_top'];
$oPoste->id_outillage_bot=$_POST['id_outillage_bot'];
$oPoste->id_enregistreur=$_POST['id_enregistreur'];
$oPoste->id_chauffage=$_POST['id_chauffage'];
$oPoste->id_ind_temp_top=$_POST['id_ind_temp_top'];
$oPoste->id_ind_temp_strap=$_POST['id_ind_temp_strap'];
$oPoste->id_ind_temp_bot=$_POST['id_ind_temp_bot'];
$oPoste->compresseur=(isset($_POST['compressor'])?1:0);
$oPoste->poste_commentaire=$_POST['poste_commentaire'];
$oPoste->poste_reason=$_POST['poste_reason'];
$oPoste->id_machine=$_POST['id_machine'];
$oPoste->id_operateur=$_COOKIE['id_user'];



$oPoste->newPoste();



  header('Location: ../index.php');

//<script type='text/javascript'>document.location.replace('../index.php?page=gestionPoste');</script>
