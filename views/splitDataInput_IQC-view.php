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
      <label for="comments">Equipment :</label>
      <input type="text" class="form-control" name="comments" value="<?= $split['comments'] ?>">
    </div>

  </form>
</div>
<script type="text/javascript" src="js/splitDataInput.js"></script>
