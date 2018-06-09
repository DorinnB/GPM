<link href="css/scheduleEprouvette.css" rel="stylesheet">
<div class="col-md-12" style="height:100%">
  <table id="table_ep" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th rowspan=2>Groupe</th>
        <th rowspan=2 class="selectableTitle" data-id="<?=  $split['available_expected'] ?>" data-IO="available_expected" data-value="<?= $split['available_expected']   ?>" data-oldValue="<?= $split['available_expected']   ?>">Receipt</th>
        <?php  foreach ($splits as $splitJob): ?>
          <th colspan=2><?= $splitJob['split'].' - '.$splitJob['test_type_abbr'].' ('.$splitJob['nbep'].' ep)'   ?></th>
        <?php  endforeach  ?>
      </tr>
      <tr>
        <?php  foreach ($splits as $splitJob): ?>
          <?php if (substr( $splitJob['test_type_abbr'], 0, 1 ) === ".") :  ?>
            <th class="selectableTitle" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-IO="DyT_SubC"  data-value="<?= $splitJob['DyT_SubC']   ?>"data-oldValue="<?= $splitJob['DyT_SubC']   ?>">DyT SubC</th>
            <th class="selectableTitle" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-IO="DyT_expected"  data-value="<?= $splitJob['DyT_expected']   ?>"data-oldValue="<?= $splitJob['DyT_expected']   ?>">Expected</th>
          <?php else: ?>
            <th class="selectableTitle" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-IO="DyT_Cust"  data-value="<?= $splitJob['DyT_Cust']   ?>"data-oldValue="<?= $splitJob['DyT_Cust']   ?>">DyT Cust</th>
            <th class="selectableTitle" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-IO="DyT_expected"  data-value="<?= $splitJob['DyT_expected']   ?>"data-oldValue="<?= $splitJob['DyT_expected']   ?>">Expected</th>
          <?php endif ?>
        <?php  endforeach  ?>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th>Groupe</th>
        <th>receipt</th>
        <?php  foreach ($splits as $splitJob): ?>
          <?php if ($splitJob['ST'] == 1) :  ?>
            <th>Sent</th>
            <th><?= $splitJob['test_type_abbr']  ?></th>
          <?php else: ?>
            <th>Start <?= $splitJob['test_type_abbr']  ?></th>
            <th><?= $splitJob['test_type_abbr']  ?></th>
          <?php endif ?>
        <?php  endforeach  ?>
      </tr>
    </tfoot>

    <tbody>
      <?php foreach ($groupes as $key => $line): ?>
        <tr>
          <td>Group <?= $key+1 ?></td>
          <td class="selectable" data-id="<?=  (($split['available_expected']=="")?" ":$split['available_expected']) ?>" data-IO="available_expected" data-oldValue="<?= $split['available_expected']   ?>"><?= $split['available_expected'] ?></td>

          <?php  foreach ($splits as $splitJob): ?>
            <?php if (isset($groupes[$key]['split'][$splitJob['id_tbljob']])) : ?>
              <?php if ($splitJob['ST'] == 1) :  ?>
                <td class="selectable <?= (isset($split2[$splitJob['id_tbljob']]['erreur_DyT_SubC']))?'error':'' ?>" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-IO="DyT_SubC" data-oldValue="<?= $splitJob['DyT_SubC']   ?>"><?= $splitJob['DyT_SubC']  ?></td>
                <td class="selectable <?= (isset($split2[$splitJob['id_tbljob']]['erreur_DyT_expected']))?'error':'' ?>" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-IO="DyT_expected" data-oldValue="<?= $splitJob['DyT_expected']   ?>"><?= $splitJob['DyT_expected']  ?></td>
              <?php else: ?>
                <td class="selectable <?= (isset($split2[$splitJob['id_tbljob']]['erreur_DyT_Cust']))?'error':'' ?>" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-IO="DyT_Cust" data-oldValue="<?= $splitJob['DyT_Cust']   ?>"><?= $splitJob['DyT_Cust']  ?></td>
                <td class="selectable <?= (isset($split2[$splitJob['id_tbljob']]['erreur_DyT_expected']))?'error':'' ?>" data-idJob="<?=  $splitJob['id_tbljob'] ?>" data-IO="DyT_expected" data-oldValue="<?= $splitJob['DyT_expected']   ?>"><?= $splitJob['DyT_expected']  ?></td>
              <?php endif ?>
            <?php else: ?>
              <td class="noInOut"></td>
              <td class="noInOut"></td>
            <?php endif ?>
          <?php  endforeach  ?>

        </tr>
      <?php endforeach ?>

    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/scheduleEprouvette.js"></script>
