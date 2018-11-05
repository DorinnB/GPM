<link href="css/newJob.css" rel="stylesheet">
<!--<link href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css" rel="stylesheet">-->
<div class="row" style="height:100%;">
  <div class="col-md-12" style="height:90%">

    <button id="save_selected" style="color:black;">Save Selection</button>
    <table id="table_ep" class="table table-condensed table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
      <thead>
        <tr>
          <th>Add</th>
          <th>Pref.</th>
          <th>ID</th>
          <th>DWG</th>
          <?php foreach ($oWorkflow->getAllSplit() as $row): ?>
            <th style="text-align:center;">
              <?= $row['test_type_abbr'] ?>
              <br />
              <input type="text" class="splitNumber" value="<?= $row['split'] ?>" style="width:40px; text-align:center;" name="existingSplit_<?= $row['id_tbljob'] ?>">
            </th>
          <?php endforeach ?>
          <?php for ($i=count($splits); $i<= 20; $i++): ?>
            <th style="text-align:center;">
              <select name="newSplit-<?= $i  ?>">
                <option value="0">-</option>
                <?php foreach ($testType as $row): ?>
                  <option value="<?= $row['id_test_type'] ?>"><?= $row['test_type_abbr'] ?></option>
                <?php endforeach  ?>
              </select>
              <br />
              <input type="text" class="splitNumber" value="" style="width:40px; text-align:center;" name="newSplit_<?= $i  ?>">
            </th>
          <?php endfor  ?>
          <th>Del</th>
        </tr>
        <tr>
          <th>Add</th>
          <th>Pref.</th>
          <th>ID</th>
          <th>DWG</th>
          <?php foreach ($oWorkflow->getAllSplit() as $row): ?>
            <th id="row-<?=  $row['phase']-1 ?>"><?= $row['nbep'] ?></th>
          <?php endforeach ?>
          <?php for ($i=count($splits); $i<= 20; $i++): ?>
            <th id="row-<?= $i ?>"></th>
          <?php endfor  ?>
                    <th>Del</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>Add</th>
          <th>Pref.</th>
          <th>ID</th>
          <th>DWG</th>
          <?php foreach ($oWorkflow->getAllSplit() as $row): ?>
            <th><?= $row['split'].'-'.$row['test_type_abbr'] ?></th>
          <?php endforeach ?>
          <?php for ($i=count($splits); $i<= 20; $i++): ?>
            <th><?= $row['split'].'<br/>'.$row['test_type_abbr'] ?></th>
          <?php endfor  ?>
                              <th>Del</th>
        </tr>
      </tfoot>
      <tbody>
        <?php foreach ($ep as $key => $line): ?>
          <tr id="<?= $line['id_master_eprouvette'] ?>">
            <td class="copy">
              <span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="float:center;"></span>
            </td>
            <td>
              <input type="text" value="<?= $line['prefixe'] ?>" style="width:100px;" name="existingEp_<?= $line['id_master_eprouvette'] ?>-prefixe">
            </td>
            <td>
              <input type="text" value="<?= $line['nom_eprouvette'] ?>" style="width:100px;" name="existingEp_<?= $line['id_master_eprouvette'] ?>-nom_eprouvette">
            </td>
            <td>
              <select name="existingEp_<?= $line['id_master_eprouvette'] ?>-id_dwg" class="dwg">
                <?php foreach ($Dwg as $row): ?>
                  <option value="<?= $row['id_dessin'] ?>" data-subtext="<?= $row['type'] ?>" <?=  ($row['id_dessin']==$line['id_dwg'])?'selected':''    ?>><?= $row['dessin'] ?></option>
                <?php endforeach ?>
              </select>
            </td>
            <?php foreach ($oWorkflow->getAllSplit() as $row): ?>
              <td class="color-<?= ((isset($line[$row['id_tbljob']]))?(($line[$row['id_tbljob']]==1)?1:2):0)   ?> row-<?= $row['phase']-1 ?>" name="existingEp_<?= $line['id_master_eprouvette'] ?>-existingSplit_<?= $row['id_tbljob']  ?>">
                <?= ((isset($line[$row['id_tbljob']]))?(($line[$row['id_tbljob']]==1)?1:2):0)   ?>

              </td>
            <?php endforeach ?>
            <?php for ($i=count($splits); $i<= 20; $i++): ?>
              <td class="color-0 row-<?= $i ?>" name="existingEp_<?= $line['id_master_eprouvette'] ?>-newsplit_<?= $i  ?>">0</td>
            <?php endfor  ?>
            <td class="delete">
              <span class="glyphicon glyphicon-minus-sign" aria-hidden="true" style="float:center;"></span>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>

  </div>

  <div class="col-md-12" style="height:10%;">
    <button id="submit_ep" style="float: right; color:black;"">SAVE</button>
  </div>
</div>

<script type="text/javascript" src="js/updateJob.js"></script>
