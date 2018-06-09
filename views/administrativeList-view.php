<link href="css/qualiteList.css" rel="stylesheet">
<div class="" id="qualiteList" style="width:100%; height:95%;">
  <h3 style="height:5%;">Administrative List
  </h3>
  <div class="row" style="height:47%;">
    <div class="col-md-12" style="height:100%;">
      <div class="row" style="height:25%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Awaiting specimen
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Job w/o PO
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            no refSubC
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Expected SubC
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Overdue SubC
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            InOut Error
          </div>
        </div>
      </div>
      <div class="row" style="height:75%;overflow-y:scroll;">
        <div class="col-md-2">
          <?php foreach ($oInOut->awaitingArrival() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->needPO() as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->noRefSubC() as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->oneWeek() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->overdueSubC() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->inOutError() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'] ?>
            </a>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="height:47%;border-top:2px solid white;">
    <div class="col-md-12" style="height:100%;">
      <div class="row" style="height:24%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Out Ready
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Overdue Out
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Report Check
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Report Ready
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Not Invoiced
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Not Completed
          </div>
        </div>
      </div>
      <div class="row" style="height:75%;overflow-y:scroll;">
        <div class="col-md-2">
          <?php foreach ($oInOut->outReady() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->overdueOut() as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->FQC() as $key => $value) : ?>
            <a href="index.php?page=clotureJob&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->reportReady() as $key => $value) : ?>
            <a href="index.php?page=clotureJob&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->notInvoiced() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->invoicedNotCompleted() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job']  ?>
            </a>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
</div>
