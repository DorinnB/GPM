<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" id="splitData" style="height:87%">

  <div class="bs-example testInfo" data-example-id="basic-forms">
    <p class="title">
      <span class="name">Specification :</span>
      <span class="value"><?= $tbljobHisto2['specification'] ?> <?= $split['specification'] ?></span>
    </p>
    <p class="title">
      <span class="name">Waveform :</span>
      <span class="value"><?= $tbljobHisto2['waveform'] ?> <?= $split['waveform'] ?></span>
    </p>
    <p class="title">
      <span class="name">Frequency :</span>
      <span class="value"><?= $tbljobHisto2['tbljob_frequence'] ?> <?= isset($split['tbljob_frequence'])?$split['tbljob_frequence']:"" ?></span>
    </p>
    <p class="title">
      <span class="name">Temperature :</span>
      <span class="value"><?= $splitEp['temperature'] ?></span>
    </p>
    <p class="title">
      <span class="name">Drawing :</span>
      <span class="value"><?= $split['dessin'] ?></span>
    </p>

    <p class="title">
      <span class="name">Cust Order :</span>
      <span class="value"><?= $split['c_type_1'].' & '.$split['c_type_2'].' ('. $split['c_unite'].')'  ?></span>
    </p>
    <p class="title">
      <span class="name">Raw Data :</span>
      <span class="value <?= ($split['id_rawData']==0)?'':'warning' ?>"><?= $tbljobHisto2['name'] ?> <?= $split['name'] ?></span>
    </p>
  </div>

  <div class="bs-example advancedTestInfo" data-example-id="basic-forms">
    <p class="title">
      <span class="name">Grip/Thread :</span>
      <span class="value"><i>taillegrip</i></span>
    </p>
    <p class="title">
      <span class="name">Load Cell :</span>
      <span class="value">
        <button type="button" id="load10" class="btn-group-xs">10</button>
        <button type="button" id="load100" class="btn-group-xs">100</button>
        <button type="button" id="load250" class="btn-group-xs">250</button>
      </span>
    </p>
    <p class="title">
      <span class="name">Frames Planned :</span>
      <span class="value"><?=  isset($machines['machines'])?$machines['machines']:'Not Planned' ?></span>
    </p>
    <p class="title <?= ($split['GE']==0)?'hide':'' ?>">
      <span class="name">GE Test :</span>
      <span class="value warning"><?= ($split['GE']==0)?'':'YES' ?></span>
    </p>
    <p class="title" id="stepcase_data">
      <span class="name" id="stepcase_data_name"></span>
      <span class="value warning" id="stepcase_data_value"></span>
    </p>
    <p class="title <?= ($split['staircase']==0)?'hide':'' ?>">
      <span class="name">Staircase :</span>
      <span class="value warning"><?= ($split['staircase']==0)?'NO':'YES' ?></span>
    </p>
    <p class="title <?= ($split['specific_protocol']==0)?'hide':'' ?>">
      <span class="name">Specific Protocol :</span>
      <span class="value warning"><?= ($split['specific_protocol']==0)?'NO':'YES' ?></span>
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
      <span class="value"><?= $split['nbepleft'] ?></span>
    </p>
    <p class="title">
      <span class="name">Test Planned :</span>
      <span class="value"><?= $split['nbtest'] ?></span>
    </p>
    <p class="title">
      <span class="name">Tests Done :</span>
      <span class="value"><?= $split['nbtestdone'] ?></span>
    </p>
    <p class="title">
      <span class="name"><acronym title="Test Duration / Supplementary Hours">Test (hrs) :</acronym></span>
      <span class="value">
        <?=
        (($split['tpscalc']=="")?'N/A':number_format($split['tpscalc'], 1, '.', ' ')).
        '  / '.
        (($split['tpssupcalc']=="")?'N/A':number_format($split['tpssupcalc'], 1, '.', ' '))
        ?>
      </span>
    </p>
  </div>

  <div class="bs-example planning" data-example-id="basic-forms">
    <p class="title">
      <span class="name">Availability :</span>
      <span class="value"><?= $split['available'] ?></span>
    </p>
    <p class="title">
      <span class="name">Estimated Day Left</span>
      <span class="value" id="estimatedDayLeft">???</span>
    </p>
    <p class="title">
      <span class="name">DyT Cust :</span>
      <span class="value"><?= $tbljobHisto2['DyT_Cust'] ?> <?= $split['DyT_Cust'] ?></span>
    </p>
  </div>



</div>
