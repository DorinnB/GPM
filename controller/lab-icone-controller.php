<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<script type="text/javascript" src="js/lab-icone.js"></script>
<?php

// Rendre votre modèle accessible
include '../models/lstIcone-model.php';


// Création d'une instance
$oIcone = new IconeModel($db);

// Retour de l'update si erreur
$icone=$oIcone->getAllIcone();

?>

<?php if ($_GET['type']=='icone') : ?>
  <p style="text-align: center; font-weight:bold;">Action</p>
  <ul class='ulicone-menu list-group'>
    <?php foreach ($icone as $key => $value) :  ?>
      <li class="list-group-item" data-id_icone = "<?=  $value['id_icone']  ?>" data-id_machine="<?=  $_GET['id_machine'] ?>">
        <img src="img/<?= $value['icone_file']  ?>" style="width: auto;max-height: 30px;">
        <?= $value['icone_name']  ?>
      </li>
    <?php endforeach ?>
  </ul>
<?php endif ?>


<?php if ($_GET['type']=='priorite') : ?>
  <p style="text-align: center; font-weight:bold;">Priorité</p>
  <ul class='ulpriorite-menu list-group'>
    <?php for ($i=0; $i < 4 ; $i++) :?>
      <li class="list-group-item" data-id_icone = "<?= $i  ?>" data-id_machine="<?=  $_GET['id_machine'] ?>">
        <img src="img/medal_<?= $i  ?>.png" style="width: auto;max-height: 30px;">
      </li>
    <?php endfor  ?>
  </ul>
<?php endif ?>

<?php if ($_GET['type']=='commentaire') : ?>
  <textarea rows=3 style="resize: none; background-color:#536E94; width:100%; border:0px;">
    <?= $_GET['type']['texte_machine_forecast'] ?>
  </textarea>
  <img src="img/save.png" style="width: auto;max-height: 30px;">
<?php endif ?>
