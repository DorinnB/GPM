<link href="css/qualiteList.css" rel="stylesheet">
<div class="" id="qualiteList" style="width:100%; height:90%;">
  <h3 style="height:5%;">Quality List
  </h3>
  <div class="row" style="height:95%;">
    <div class="col-md-12" style="height:100%;">
      <div class="row bandeau" style="height:10%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Unchecked
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Unchecked Started
          </div>
        </div>
        <div class="col-md-4">
          <div class="col-md-12 titre">
            Quality Flag (nb)
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Report Check
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Raw&nbsp;Data Requested
          </div>
        </div>
      </div>
      <div class="row bandeauVal" style="height:90%;overflow-y:scroll">
        <div class="col-md-2">
          <?php foreach ($oQualite->getUncheckedJob() as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oQualite->getUncheckedStartedJob() as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-4">
          <?php foreach ($oQualite->getFlagJob() as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split'].' ('.$value['nb'].')'  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->stepStatut(70) as $key => $value) : ?>
            <a href="index.php?page=clotureJob&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->RawData() as $key => $value) : ?>
            <?php if ($value['nbrawdataunsent']>0) : ?>
              <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
                <?= $value['job'].'-'.$value['split']  ?>
              </a>
            <?php endif ?>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">

        </div>
      </div>
    </div>
  </div>
</div>
