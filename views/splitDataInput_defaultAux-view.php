<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData"style="height:100%;">
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

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="other_1">Number of Steps &nbsp;:</label>
          <select class="form-control" name="other_1">
            <?php for ($i=1; $i <= 20 ; $i++) : ?>
              <option value="<?= $i ?>" <?=  ($split['other_1']==$i)?'selected':''    ?>><?= $i ?></option>
            <?php endfor  ?>
          </select>
        </div>
        <div class="form-group">
          <label for="other_3">Specific Test :</label>
          <select id="other_3" name="other_3" class="form-control">
            <option value="0" <?=  ($split['other_3']== 0)?"selected":""  ?>>Standard</option>
            <option value="1" <?=  ($split['other_3']== 1)?"selected":""  ?>>OCV</option>
            <option value="2" <?=  ($split['other_3']== 2)?"selected":""  ?>>Tube</option>
            <option value="3" <?=  ($split['other_3']== 3)?"selected":""  ?>>Custom</option>
          </select>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="other_2">Other Information&nbsp;:</label>
          <input type="text" class="form-control" name="other_2" value="<?= $split['other_2'] ?>">
        </div>
        <div class="form-group">
          <label for="other_4">Camera :</label>
          <select id="other_4" name="other_4" class="form-control">
            <option value="0" <?=  ($split['other_4']== 0)?"selected":""  ?>>No</option>
            <option value="1" <?=  ($split['other_4']== 1)?"selected":""  ?>>Yes</option>
          </select>
        </div>
      </div>
    </div>


    <div class="form-group">
      <label for="">Special Instructions&nbsp;:</label>
      <div class="input-group">
        <label class="input-group-btn">
          <span class="btn btn-primary">
            Browse&hellip; <input type="file" id="special_instruction_file" style="display: none;" multiple>
          </span>
        </label>
        <input type="text" class="form-control" name="special_instruction" id="special_instruction" value="<?= $split['special_instruction'] ?>"readonly>
      </div>
      <a href="#" id="special_instruction_clear">Clear</a>
    </div>


  </form>
</div>
<script type="text/javascript" src="js/splitDataInput.js"></script>
