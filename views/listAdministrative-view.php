<link href="css/qualiteList.css" rel="stylesheet">
<div class="" id="qualiteList" style="width:100%; height:95%;">
  <h3 style="height:5%;">Administrative List
  </h3>
  <div class="row" style="height:47%;">
    <div class="col-md-12" style="height:100%;">
      <div class="row bandeau" style="height:25%;overflow-y:scroll;border-bottom:2px solid white;">
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
            <?php if ($oInOut->overdueSubC()): ?>
              Overdue SubC
            <?php else : ?>
              Expected SubC
            <?php endif ?>
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Weekly Report
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Weekly Report SubC
          </div>
        </div>
      </div>
      <div class="row bandeauVal" style="height:75%;overflow-y:scroll;">
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
          <?php foreach ($oInOut->overdueSubC() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>

          <?php if ($oInOut->overdueSubC() AND $oInOut->oneWeek()) : ?>
            <p href="#" class="titre bandeau" style="border-bottom: 2px solid white; margin-top:20px; width: 100%;display: inline-block; border">
              Expected SubC
            </p>
          <?php endif ?>

          <?php foreach ($oInOut->oneWeek() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->WeeklyReport() as $key => $value) : ?>
            <a href="index.php?page=WeeklyReport&customer=<?= $value['customer'] ?>" class="col-md-12 valeur">
              <?= $value['customer'] .' ('.$value['nbJob'].')' ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->WeeklyReportSubC() as $key => $value) : ?>
            <a href="index.php?page=WeeklyReportSubC&customer=<?= $value['ref_customer'] ?>" class="col-md-12 valeur">
              <?= $value['ref_customer'] .' ('.$value['nbJob'].')' ?>
            </a>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="height:47%;border-top:2px solid white;">
    <div class="col-md-12" style="height:100%;">
      <div class="row bandeau" style="height:24%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-2">
          <div class="col-md-12 titre">
            InOut Error
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
              Out Ready
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
            To Be Invoiced
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            To Be Closed [WIP]
          </div>
        </div>
      </div>
      <div class="row bandeauVal" style="height:75%;overflow-y:scroll;">
        <div class="col-md-2">
          <?php foreach ($oInOut->inOutError() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'] ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->outReady() as $key => $value) : ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->stepStatut(59) as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
          <?php foreach ($oInOut->stepStatut(70) as $key => $value) : ?>
            <a href="index.php?page=clotureJob&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
          <?php foreach ($oInOut->stepStatut(71) as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->stepStatut(80) as $key => $value) : ?>
            <a href="index.php?page=clotureJob&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->toBeInvoiced() as $key => $value) : ?>
            <?php if ($value['invoice_final']!=1): ?>
            <a href="index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job']  ?>
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
