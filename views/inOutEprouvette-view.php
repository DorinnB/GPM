<link href="css/inOutEprouvette.css" rel="stylesheet">
<div class="col-md-12" style="height:100%">
  <table id="table_ep" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th rowspan=2>id</th>
        <th rowspan=2>Prefixe</th>
        <th rowspan=2>ID</th>
        <th rowspan=2>Dwg</th>
        <th rowspan=2>Initial<br/>Receipt</th>
        <?php  foreach ($splits as $splitInOut): ?>
          <th colspan=3><?= $splitInOut['split'].'-'.$splitInOut['test_type_abbr']  ?></th>
        <?php  endforeach  ?>
        <th rowspan=2>Final<br/>Return</th>
      </tr>
      <tr>
        <?php  foreach ($splits as $splitInOut): ?>
          <?php if (substr( $splitInOut['test_type_abbr'], 0, 1 ) === ".") :  ?>
            <th>Sent</th>
            <th><?= $splitInOut['test_type_abbr']  ?></th>
            <th>Return</th>
          <?php else: ?>
            <th>Start</th>
            <th><?= $splitInOut['test_type_abbr']  ?></th>
            <th>End</th>
          <?php endif ?>
        <?php  endforeach  ?>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th>id</th>
        <th>Prefixe</th>
        <th>ID</th>
        <th>Dwg</th>
        <th>Initial</th>
        <?php  foreach ($splits as $splitInOut): ?>
          <?php if ($splitInOut['ST'] == 1) :  ?>
            <th>Sent</th>
            <th><?= $splitInOut['test_type_abbr']  ?></th>
            <th>Return</th>
          <?php else: ?>
            <th>Start <?= $splitInOut['test_type_abbr']  ?></th>
            <th><?= $splitInOut['test_type_abbr']  ?></th>
            <th>End <?= $splitInOut['test_type_abbr']  ?></th>
          <?php endif ?>
        <?php  endforeach  ?>
        <th>Final</th>
      </tr>
    </tfoot>

    <tbody>
      <?php foreach ($ep as $key => $line): ?>
        <tr id="<?= $line['id_master_eprouvette'] ?>">
          <td><?= $line['id_master_eprouvette'] ?></td>
          <td><?= $line['prefixe'] ?></td>
          <td><?= $line['nom_eprouvette'] ?></td>
          <td><?= $line['dessin'] ?></td>
          <td class="selectable" data-idMaster="<?=  $line['id_master_eprouvette'] ?>" data-IO="master_eprouvette_inOut_A" data-oldValue="<?= $line['master_eprouvette_inOut_A']   ?>"><?= $line['master_eprouvette_inOut_A'] ?></td>

          <?php  foreach ($splits as $splitInOut): ?>
            <?php if (isset($line[$splitInOut['id_tbljob']])) : ?>
              <?php if ($splitInOut['ST'] == 1) :  ?>
                <td class="selectable" data-id="<?= $epA[$line[$splitInOut['id_tbljob']]]['id_eprouvette'] ?>" data-IO="eprouvette_inOut_A"  data-oldValue="<?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_A']   ?>"><?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_A'] ?></td>
                <td data-id="<?=  $epA[$line[$splitInOut['id_tbljob']]]['id_eprouvette'] ?>" class="done" data-done="<?= $epA[$line[$splitInOut['id_tbljob']]]['done']  ?>"><?= $epA[$line[$splitInOut['id_tbljob']]]['done']  ?></td>
                <td class="selectable" data-id="<?= $epA[$line[$splitInOut['id_tbljob']]]['id_eprouvette'] ?>" data-IO="eprouvette_inOut_B" data-oldValue="<?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_B']   ?>"><?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_B']   ?></td>
              <?php elseif ($splitInOut['auxilaire'] == 1) :  ?>
                  <td class="selectable" data-id="<?= $epA[$line[$splitInOut['id_tbljob']]]['id_eprouvette'] ?>" data-IO="eprouvette_inOut_A"  data-oldValue="<?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_A']   ?>"><?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_A'] ?></td>
                  <td data-id="<?=  $epA[$line[$splitInOut['id_tbljob']]]['id_eprouvette'] ?>" class="done" data-done="<?= $epA[$line[$splitInOut['id_tbljob']]]['done']  ?>"><?= $epA[$line[$splitInOut['id_tbljob']]]['done']  ?></td>
                  <td class="selectable" data-id="<?= $epA[$line[$splitInOut['id_tbljob']]]['id_eprouvette'] ?>" data-IO="eprouvette_inOut_B" data-oldValue="<?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_B']   ?>"><?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_B']   ?></td>
              <?php else: ?>
                <td><?= $epA[$line[$splitInOut['id_tbljob']]]['enregistrementessais_date'] ?></td>
                <td class="done" data-done="<?= $epA[$line[$splitInOut['id_tbljob']]]['done']  ?>"><?= $epA[$line[$splitInOut['id_tbljob']]]['done']  ?></td>
                <td><?= $epA[$line[$splitInOut['id_tbljob']]]['eprouvette_inOut_B']   ?></td>
              <?php endif ?>
            <?php else: ?>
              <td class="noInOut"></td>
              <td class="noInOut"></td>
              <td class="noInOut"></td>
            <?php endif ?>
          <?php  endforeach  ?>

          <td  class="selectable" data-idMaster="<?=  $line['id_master_eprouvette'] ?>" data-IO="master_eprouvette_inOut_B" data-oldValue="<?= $line['master_eprouvette_inOut_B']   ?>"><?= $line['master_eprouvette_inOut_B'] ?></td>

        </tr>
      <?php endforeach ?>

    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/inOutEprouvette.js"></script>
