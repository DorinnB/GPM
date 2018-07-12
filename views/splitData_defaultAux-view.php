<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" id="splitData" style="height:87%">

  <div class="bs-example testInfo" data-example-id="basic-forms">
    <p class="title">
      <span class="name">Specification :</span>
      <span class="value"><?= $tbljobHisto2['specification'] ?> <?= $split['specification'] ?></span>
    </p>
    <p class="title">
      <span class="name">Drawing :</span>
      <span class="value"><?= $split['dessin'] ?></span>
    </p>
    <p class="title <?= (($split['other_3']==0) AND ($tbljobHisto2['other_3']==""))?'hide':'' ?>">
      <span class="name">Specific Test :</span>
      <span class="value warning"><?= $tbljobHisto2['other_3'] ?>
        <?= ($split['other_3']==0)?'Standard':'' ?>
        <?= ($split['other_3']==1)?'OCV':'' ?>
        <?= ($split['other_3']==2)?'Tube':'' ?>
        <?= ($split['other_3']==3)?'Custom':'' ?>
      </span>
    </p>
  </div>

  <div class="bs-example advancedTestInfo" data-example-id="basic-forms">
    <p class="title" style="<?= (($split['other_1']!="")?"":"display:none;") ?>">
      <span class="name">Number of Steps :</span>
      <span class="value warning"><?= $split['other_1'] ?></span>
    </p>
<p class="title" style="<?= (($split['other_2']!="")?"":"display:none;") ?>">
  <span class="name">Number of other Steps :</span>
  <span class="value warning"><?= $split['other_2'] ?></span>
</p>
    <p class="title <?= (($split['other_4']==0) AND ($tbljobHisto2['other_4']==""))?'hide':'' ?>">
      <span class="name">Camera :</span>
      <span class="value warning"><?= $tbljobHisto2['other_4'] ?> <?= ($split['other_4']==0)?'NO':'YES' ?></span>
    </p>
    <p class="title <?= ($split['special_instruction']=="")?'hide':'' ?>">
      <span class="name">Special Instructions :</span>
      <span class="value">
        <?php foreach (explode(",", $split['special_instruction'].',') as $key => $value) :  ?>
          <a href="#" class="warning openDocument" data-type="special_instruction" data-file="<?= $value ?>"><?= $value ?></a>
        <?php endforeach ?>

      </span>
    </p>
  </div>

  <div class="bs-example avancement" data-example-id="basic-forms">
    <p class="title">
      <span class="name">Specimen Nb:</span>
      <span class="value"><?= $split['nbep'] ?></span>
    </p>
    <p class="title">
      <span class="name">Specimen Untested</span>
      <span class="value"><?= $split['nbepCheckedleft'] ?></span>
    </p>
    <p class="title">
      <span class="name">Test Planned :</span>
      <span class="value"><?= $split['nbtest'] ?></span>
    </p>
    <p class="title">
      <span class="name">Tests Done :</span>
      <span class="value"><?= $split['nbtest']-$split['nbepCheckedleft'] ?></span>
    </p>
  </div>

  <div class="bs-example planning" data-example-id="basic-forms">
    <p class="title">
      <span class="name">Availability :</span>
      <span class="value"><?= $split['available'] ?></span>
    </p>
    <p class="title">
      <span class="name">DyT Cust :</span>
      <span class="value"><?= $tbljobHisto2['DyT_Cust'] ?> <?= $split['DyT_Cust'] ?></span>
    </p>
  </div>



</div>
