<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" id="splitData" style="height:87%">
  <form type="GET" action="controller/updateData.php" id="updateData" style="height:99%">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">


    <div class="bs-example designation" data-example-id="basic-forms">
      <p class="title">
        <span class="name">Specification :</span>
        <span class="value"><?= $tbljobHisto2['specification'] ?> <?= $split['specification'] ?></span>
      </p>
      <p class="title">
        <span class="name">Drawing :</span>
        <span class="value"><?= $split['dessin'] ?></span>
      </p>
      <p class="title">
        <span class="name">Sub Contractor :</span>
        <span class="value"><acronym title="<?= $split['entrepriseST'] ?>"><?= $tbljobHisto2['entreprise_abbrST'] ?> <?= $split['entreprise_abbrST'] ?></acronym></span>
      </p>
      <p class="title">
        <span class="name">Contact :</span>
        <span class="value"><?= $split['prenomST'].' '.$split['nomST'] ?></span>
      </p>
    </div>

    <div class="bs-example avancement" data-example-id="basic-forms">
      <p class="title">
        <span class="name">Test Planned :</span>
        <span class="value"><?= $split['nbtest'] ?></span>
      </p>
      <p class="title">
        <span class="name">Specimen Untested </span>
        <span class="value"><?= $split['nbepCheckedleft'] ?></span>
      </p>

      <p class="title">
        <span class="name" id="subCRef">Sub Ref : <span class="glyphicon glyphicon-pencil"></span></span>
        <span class="date" id="refSubC_alt"><?= $tbljobHisto2['refSubC'] ?> <?= $split['refSubC'] ?></span>
        <input type="text" class="form-control flip" id="refSubC" name="refSubC" value="<?= $split['refSubC'] ?>">
      </p>
    </div>

    <div class="bs-example planning" data-example-id="basic-forms">
      <p class="title">
        <span class="name">Availability : </span></span>
        <span class="value"><?= $split['available'] ?></span>
      </p>
      <p class="title">
        <span class="name" id="DyT_SubCFlip">DyT SubC : </span>
        <span class="value" id="DyT_SubC_alt"><?= $tbljobHisto2['DyT_SubC'] ?> <?= (($split['DyT_SubC']=="")?'Undefined':$split['DyT_SubC']) ?></span>
      </p>
      <p class="title">
        <span class="name" id="DyT_expectedFlip">DyT expected : </span>
        <span class="value" id="DyT_expected_alt"><?= $tbljobHisto2['DyT_expected'] ?> <?= (($split['DyT_expected']=="")?'Undefined':$split['DyT_expected']) ?></span>
      </p>
      <p class="title">
        <span class="name">DyT Cust :</span>
        <span class="value"><?= $tbljobHisto2['DyT_Cust'] ?> <?= $split['DyT_Cust'] ?></span>
      </p>
    </div>



  </form>
</div>
<script type="text/javascript" src="js/splitData.js"></script>
