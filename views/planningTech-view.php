<link href="css/planningTech.css" rel="stylesheet">
<div class="" id="planningTech" style="width:100%; height:90%;">
  <h3 style="height:5%;">Planning Tech
  </h3>
  <div class="row" style="height:95%;">
    <div class="col-md-12" style="height:100%;">

      <table id="table_planningTech"  class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style=" white-space:nowrap;">

        <thead>
          <tr>
            <?php  foreach ($tblheader[0] as $titre): ?>
              <th><?= $titre  ?></th>
            <?php  endforeach  ?>
          </tr>
          <tr>
            <?php  foreach ($tblheader[1] as $titre): ?>
              <th><?= $titre  ?></th>
            <?php  endforeach  ?>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($tblplanning as $key => $line): ?>
            <tr>
              <?php foreach ($line as $col): ?>
                <td class="<?= ((!is_numeric($col)) OR ($col=="0"))?"black":"" ?>"><?= $col ?></td>
              <?php  endforeach  ?>
            </tr>
          <?php  endforeach  ?>
        </tbody>

      </table>

    </div>
  </div>
</div>
