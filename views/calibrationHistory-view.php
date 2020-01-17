<div class="col-md-12" style="height:100%">
  <table id="table_calibration" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th>Frame</th>
        <th>Dessin</th>
        <th>Mat</th>
        <th>T/C</th>
        <th>Scale</th>
        <th>date_start</th>
        <th>date_end</th>
        <th><acronym title='Technicien'>Tech.</acronym></th>
        <th><acronym title='Checker'>Chk.</acronym></th>
        <th>Adjustment</th>
        <th>Cancel</th>
        <th>Compliant</th>
        <th>Check</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>Frame</th>
        <th>Dessin</th>
        <th>Mat</th>
        <th>T/C</th>
        <th>Scale</th>
        <th>date_start</th>
        <th>date_end</th>
        <th><acronym title='Technicien'>Tech.</acronym></th>
        <th><acronym title='Checker'>Chk.</acronym></th>
        <th>Adjustment</th>
        <th>Cancel</th>
        <th>Compliant</th>
        <th>Check</th>
      </tr>
    </tfoot>
    <tbody>
      <?php for($k=0;$k < count($history);$k++): ?>
        <tr data-id="<?= $history[$k]['id_calibration'] ?>" data-compliant="<?= $history[$k]['compliant'] ?>">
          <td><?= $history[$k]['machine'] ?></td>
          <td><?= $history[$k]['dessin'] ?></td>
          <td><?= $history[$k]['matiere'] ?></td>
          <td><?= $history[$k]['thermocouple'] ?></td>
          <td><?= $history[$k]['scale'] ?></td>
          <td><?= $history[$k]['date_start'] ?></td>
          <td><?= $history[$k]['date_end'] ?></td>
          <td><?= $history[$k]['operator'] ?></td>
          <td><?= $history[$k]['checker'] ?></td>
          <td><?= $history[$k]['adjustment'] ?></td>
          <td><?= $history[$k]['cancelprevious'] ?></td>
          <td class="compliant_<?= $history[$k]['compliant'] ?>"><?= $history[$k]['compliant'] ?></td>
          <td>
            <?php if ($history[$k]['compliant']==2 AND isset($_COOKIE['id_user']) AND $_COOKIE['id_user']!=0 AND $_COOKIE['id_user']!=$history[$k]['operator']) : ?>
            <button type="button" class="btn btn-default btn-block btn-sm check" data-id="<?= $history[$k]['id_calibration'] ?>">
              <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Check
            </button>
          <?php endif ?>
          </td>
        </tr>
      <?php endfor ?>
    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/calibrationHistory.js"></script>
