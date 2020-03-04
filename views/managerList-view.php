<link href="css/qualiteList.css" rel="stylesheet">
<div class="" id="qualiteList" style="width:100%; height:95%;">
  <h3 style="height:5%;">Manager List
  </h3>
  <div class="row" style="height:37%;">
    <div class="col-md-12" style="height:100%;">
      <div class="row bandeau" style="height:30%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-2">
          <div class="col-md-12 titre">

          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">

          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Last Condition
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            FQC TM
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">

          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Modif Planning
          </div>
        </div>
      </div>
      <div class="row bandeauVal" style="height:70%;overflow-y:scroll;">
        <div class="col-md-2">

        </div>
        <div class="col-md-2">

        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->stepStatut(53) as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->stepStatut(71) as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">

        </div>
        <div class="col-md-2">
          <?php foreach ($nbModifPlanning as $key => $value) : ?>
            <a href="index.php?page=planningManagers" class="col-md-12 valeur">
              <?= $value['technicien'].' ('.$value['nb'].')'  ?>
            </a>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="height:19%;border-top:2px solid white;">
    <div class="col-md-12" style="height:100%;">
      <div class="row bandeau" style="height:60%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Check OT
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            FQC
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Report Ready
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Quality Flag
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Job w/o PO
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Overdue SubC
          </div>
        </div>
      </div>
      <div class="row bandeauVal" style="height:60%;overflow-y:scroll;">
        <div class="col-md-2">
          <?= count($oInOut->stepStatut(10))  ?>
        </div>
        <div class="col-md-2">
          <?= count($oInOut->stepStatut(70))  ?>
        </div>
        <div class="col-md-2">
          <?= count($oInOut->stepStatut(80))  ?>
        </div>
        <div class="col-md-2">
          <?= count($oQualite->getFlagJob())  ?>
        </div>
        <div class="col-md-2">
          <?= count($oInOut->needPO())  ?>
        </div>
        <div class="col-md-2">
          <?= count($oInOut->overdueSubC())  ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="height:37%;border-top:2px solid white;">
    <div class="col-md-12" style="height:100%;">
      <div class="row bandeau" style="height:30%;overflow-y:scroll;border-bottom:2px solid white;">
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Create OT
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Data Unchecked
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            Raw&nbsp;Data Expected
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            NO Ref_SubC
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            NO DyT&nbsp;SubC
          </div>
        </div>
        <div class="col-md-2">
          <div class="col-md-12 titre">
            NO DyT&nbsp;Cust
          </div>
        </div>
      </div>
      <div class="row bandeauVal" style="height:70%;overflow-y:scroll;">
        <div class="col-md-2">
          <?php foreach ($oInOut->stepStatut(1) as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
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
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->RawData() as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
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
          <?php foreach ($oInOut->noDateSubC() as $key => $value) : ?>
            <a href="index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
        <div class="col-md-2">
          <?php foreach ($oInOut->noDateCust() as $key => $value) : ?>
            <a href="index.php?page=clotureJob&amp;id_tbljob=<?= $value['id_tbljob'] ?>" class="col-md-12 valeur">
              <?= $value['job'].'-'.$value['split']  ?>
            </a>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
</div>
