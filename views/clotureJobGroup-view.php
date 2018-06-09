<div class="col-md-12" style="height:100%;">
  <table id="table_group" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th rowspan="2">Groupe</th>
        <?php  foreach ($splits as $splitJob): ?>
          <th  style="background-color:<?= $splitJob['statut_color']  ?>;"><?= $splitJob['split'].' - '.$splitJob['test_type_abbr'].' ('.$splitJob['nbep'].' ep)'  ?></th>
        <?php  endforeach  ?>
      </tr>
      <tr>
        <?php  foreach ($splits as $splitJob): ?>
            <th><?= $splitJob['refSubC']  ?></th>
        <?php  endforeach  ?>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($groupes as $key => $line): ?>
        <tr>
          <td>Group <?= $key+1 ?></td>
          <?php  foreach ($splits as $splitJob): ?>
            <?php if (isset($groupes[$key]['split'][$splitJob['id_tbljob']])) : ?>
              <?php if (is_numeric($splitJob['split'])) :  ?>
                <td style="border: 3px solid yellow; background-color:<?=$splitJob['statut_color']  ?>;"><?= $splitJob['etape']  ?></td>
            <?php else: ?>
                <td style="background-color:<?=$splitJob['statut_color']  ?>;"><?= $splitJob['etape']  ?></td>
              <?php endif ?>
            <?php else: ?>
              <td class="xxxxnoInOut"></td>
            <?php endif ?>
          <?php  endforeach  ?>
        </tr>
      <?php endforeach ?>

    </tbody>
  </table>


</div>
