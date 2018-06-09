<link href="css/splitEprouvette.css" rel="stylesheet">
<div class="col-md-12" style="height:100%">
  <table id="table_ep" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th>id</th>
        <th><acronym title="Availability">A</acronym></th>
        <th>Prefixe</th>
        <th>ID</th>
        <?php  foreach ($dimDenomination as $dimTexte): ?>
          <th><?= $dimTexte  ?></th>
        <?php  endforeach  ?>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Order Check">Chk</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="Engraving">Engr.</acronym></th>
        <th><acronym title="Surface Quality">Surf.</acronym></th>
        <th><acronym title="Shot Peening">Shp</acronym></th>
        <th><acronym title="Surface Treatment/Coating">Coat.</acronym></th>
        <th><acronym title="Antioxydative Protection">Prot.</acronym></th>
        <th><acronym title="Other">Oth.</acronym></th>
        <th><acronym title="Value Check">Valid</acronym></th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th>id</th>
        <th><acronym title="Availability">A</acronym></th>
        <th>Prefixe</th>
        <th>ID</th>
        <?php  foreach ($dimDenomination as $dimTexte): ?>
          <th><?= $dimTexte  ?></th>
        <?php  endforeach  ?>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Order Check">Chk</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="Engraving">Engr.</acronym></th>
        <th><acronym title="Surface Quality">Surf.</acronym></th>
        <th><acronym title="Shot Peening">Shp</acronym></th>
        <th><acronym title="Surface Treatment/Coating">Coat.</acronym></th>
        <th><acronym title="Antioxydative Protection">Prot.</acronym></th>
        <th><acronym title="Other">Oth.</acronym></th>
        <th><acronym title="Value Check">Valid</acronym></th>
      </tr>
    </tfoot>

    <tbody>
      <?php for($k=0;$k < count($ep);$k++): ?>
        <tr class="chartTR">
          <td><?= $ep[$k]['id_master_eprouvette'] ?></td>
          <td class="dispo selectable chartFile" data-id="<?= $ep[$k]['id_eprouvette'] ?>" data-dispo="<?= $ep[$k]['dispo'] ?>"><acronym title="<?= $ep[$k]['dispoText'] ?>"><?= $ep[$k]['dispo'] ?></acronym></td>
          <td><?= $ep[$k]['prefixe'] ?></td>
          <td><?= $ep[$k]['nom_eprouvette'] ?></td>
          <?php for($i=1;$i <= count($dimDenomination);$i++): ?>
            <td class="chartIQC<?=  'dim'.$i  ?>" <?= $epHisto2[$k]['dim'.$i] ?>><?= $ep[$k]['dim'.$i]  ?></td>
          <?php  endfor  ?>
          <td class="popover-markup" data-placement="left"><?= ($ep[$k]['c_commentaire']=="")?"":substr($ep[$k]['c_commentaire'],0,5)." [...]" ?>
            <?php if ($ep[$k]['c_commentaire'] !=""):  ?>
              <div class="head hide">Order Comment</div>
              <div class="content hide">
                <div class="form-group">
                  <textarea class"bubble_commentaire" name="c_commentaire" rows="10" cols="50" style="resize:none;" disabled><?= $ep[$k]['c_commentaire'] ?></textarea>
                </div>
              </div>
            <?php endif ?>
          </td>
          <td class="checkEp selectable" data-checked="<?= max(0,$ep[$k]['c_checked']) ?>" data-idepchecked="<?= $ep[$k]['id_eprouvette'] ?>"><?= $ep[$k]['c_checked'] ?></td>
          <td class="popover-markup" data-placement="left"><?= ($ep[$k]['d_commentaire']=="")?"":substr($ep[$k]['d_commentaire'],0,5)." [...]" ?>
            <?php if ($ep[$k]['d_commentaire'] !=""):  ?>
              <div class="head hide">Lab Observation</div>
              <div class="content hide">
                <div class="form-group">
                  <textarea class"bubble_commentaire" name="d_commentaire" rows="10" cols="50" style="resize:none;" disabled><?= $ep[$k]['d_commentaire'] ?></textarea>
                </div>
              </div>
            <?php endif ?>
          </td>
          <td class="flag_qualite selectable" data-flagQualite="<?= $ep[$k]['flag_qualite'] ?>"  data-idepflagqualite="<?= $ep[$k]['id_eprouvette'] ?>"><?= $ep[$k]['flag_qualite'] ?></td>
          <td class="popover-markup" data-placement="left"><?= ($ep[$k]['q_commentaire']=="")?"":substr($ep[$k]['q_commentaire'],0,5)." [...]" ?>
            <?php if ($ep[$k]['q_commentaire'] !=""):  ?>
              <div class="head hide">Quality Observation</div>
              <div class="content hide">
                <div class="form-group">
                  <textarea class"bubble_commentaire" name="q_commentaire" rows="10" cols="50" style="resize:none;" disabled><?= $ep[$k]['q_commentaire'] ?></textarea>
                </div>
              </div>
            <?php endif ?>
          </td>
          <td class="IQC" data-value="<?= $ep[$k]['marquage'] ?>" <?= $epHisto2[$k]['marquage'] ?>><?= $ep[$k]['marquage'] ?></td>
          <td class="IQC" data-value="<?= $ep[$k]['surface'] ?>" <?= $epHisto2[$k]['surface'] ?>><?= $ep[$k]['surface'] ?></td>
          <td class="IQC" data-value="<?= $ep[$k]['grenaillage'] ?>" <?= $epHisto2[$k]['grenaillage'] ?>><?= $ep[$k]['grenaillage'] ?></td>
          <td class="IQC" data-value="<?= $ep[$k]['revetement'] ?>" <?= $epHisto2[$k]['revetement'] ?>><?= $ep[$k]['revetement'] ?></td>
          <td class="IQC" data-value="<?= $ep[$k]['protection'] ?>" <?= $epHisto2[$k]['protection'] ?>><?= $ep[$k]['protection'] ?></td>
          <td <?= $epHisto2[$k]['autre'] ?>><?= $ep[$k]['autre'] ?></td>
          <td class="dCheckEp selectable" data-dchecked="<?= max(0,$ep[$k]['d_checked']) ?>"  data-idepdchecked="<?= $ep[$k]['id_eprouvette'] ?>"><?= $ep[$k]['d_checked'] ?></td>

        </tr>
      <?php endfor ?>
    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/splitEprouvette.js"></script>
