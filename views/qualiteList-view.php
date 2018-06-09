<link href="css/qualiteList.css" rel="stylesheet">
<div class="" id="qualiteList" style="width:100%; height:90%;">
  <h3 style="height:5%;">Quality List
  </h3>
  <div class="row" style="height:95%;">
    <div class="col-md-12" style="height:100%;">
      <div class="row" style="height:10%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Unchecked
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Unchecked Started
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Quality Flag
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Report Check
          </div>
        </div>
      </div>
      <div class="row" style="height:90%;overflow-y:scroll">
        <div class="col-md-3">
          <?php foreach ($uncheckedJob as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($uncheckedStartedJob as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($flag as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($oInOut->FQC() as $key => $value) : ?>
            <a href="index.php?page=clotureJob&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
</div>
