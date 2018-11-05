<link href="css/qualiteList.css" rel="stylesheet">
<div class="" id="qualiteList" style="width:100%; height:90%;">
  <h3 style="height:5%;">Lab List
  </h3>
  <div class="row" style="height:95%;">
    <div class="col-md-12" style="height:100%;">
      <div class="row bandeau" style="height:10%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-3">
          <div class="col-md-12 titre">
            <acronym title="Machine - Technician">Unchecked</acronym>
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            <acronym title="Job - Specimen ID">Rupture</acronym>
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            <acronym title="Job - File Number">DataValue</acronym>
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            <acronym title="Machine - Technician ">Running</acronym>
          </div>
        </div>
      </div>
      <div class="row bandeauVal" style="height:90%;overflow-y:scroll">
        <div class="col-md-3">
          <?php foreach ($test as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_job'] ?>" class="col-md-12 valeur" style="border-bottom: white solid 1px;">
              <?= $value['machine'].'</br>'.$value['controleur']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($checkRupture as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_job'] ?>" class="col-md-12 valeur" style="border-bottom: white solid 1px;">
              <?= $value['customer'].'-'.$value['job'].'-'. $value['split'].'</br>'.$value['prefixe'].'-'.$value['nom_eprouvette']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($checkDataValue as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_job'] ?>" class="col-md-12 valeur"  style="border-bottom: white solid 1px;">

              <?= $value['customer'].'-'.$value['job'].'-'. $value['split'].'</br>'.$value['n_fichier']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($awaitingTechnician as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_job'] ?>" class="col-md-12 valeur" style="border-bottom: white solid 1px;">
              <?= $value['machine'].'</br>'.$value['currentBlock_temp']."&nbsp;-&nbsp;".$value['nom_operateur']  ?>
            </a>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
</div>
