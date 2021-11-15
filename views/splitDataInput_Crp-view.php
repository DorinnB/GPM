<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">

    <div class="form-group">
      <label for="Spec">Specification&nbsp;:&nbsp;<span class="glyphicon glyphicon-info-sign" data-toggle="collapse" href="#specifications" role="button" aria-expanded="false" aria-controls="specifications">
      </label>
      <input type="text" class="form-control" name="specification" value="<?= $split['specification'] ?>">

      <div class="collapse" id="specifications" style="text-align: left;">
        <b>Specification List:</b>
        <?php if ($specifications) : ?>
          <ul>
            <?php foreach ($specifications as $row): ?>
              <li onclick="console.log('a');$('input[name=specification]').val('<?= $row['specification'].(isset($row['version'])?'_'.$row['version']:'') ?>');"><?= $row['specification'].(isset($row['version'])?'_'.$row['version']:'') ?></li>
            <?php endforeach ?>
          </ul>
        <?php endif ?>
      </div>
    </div>


    <div class="form-group">
      <label for="Units">Units :</label>
      <select class="form-control" name="c_unite">
        <option value="%" <?=  ($split['c_unite']=="%")?'selected':'' ?>>%</option>
        <option value="kN" <?=  ($split['c_unite']=="kN")?'selected':'' ?>>kN</option>
        <option value="MPa" <?=  ($split['c_unite']=="MPa")?'selected':'' ?>>MPa</option>
      </select>
    </div>

    <div class="form-group">
      <label for="id_rawData">Raw Data :</label>
      <select class="form-control" name="id_rawData">
        <?php foreach ($RawData as $row): ?>
          <option value="<?= $row['id_rawData'] ?>" <?=  ($row['id_rawData']==$split['id_rawData'])?'selected':''    ?>><?= $row['name'] ?></option>
        <?php endforeach ?>
      </select>
    </div>

  </form>
</div>
<script type="text/javascript" src="js/splitDataInput.js"></script>
