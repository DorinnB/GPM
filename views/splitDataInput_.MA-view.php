<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:87%; overflow: auto;">
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
      <label for="ref_customerST">Cust. #</label>
      <select id="ref_customerST" name="ref_customerST" class="form-control">
        <?php foreach ($ref_customerST as $row): ?>
          <option value="<?= $row['id_entreprise'] ?>" <?=  ($split['id_entrepriseST']== $row['id_entreprise'])?"selected":""  ?>><?= $row['id_entreprise'] ?> <?= $row['entreprise_abbr'] ?></option>
        <?php endforeach ?>
      </select>
    </div>
    <div class="form-group">
      <label for="id_contactST">Report Contact :</label>
      <select id="id_contactST" name="id_contactST" class="form-control">
        <option>Please choose from above</option>
      </select>
    </div>

    <div class="form-group">
      <label for="other_1">Shot Peening :</label>
      <select id="other_1" name="other_1" class="form-control">
        <option value="0" <?=  ($split['other_1']== 0)?"selected":""  ?>>No</option>
        <option value="1" <?=  ($split['other_1']== 1)?"selected":""  ?>>Fatigue Specimen</option>
      </select>
    </div>
    <div class="form-group">
      <label for="other_2">Anti-Corrosion Protection :</label>
      <select id="other_2" name="other_2" class="form-control">
        <option value="0" <?=  ($split['other_2']== 0)?"selected":""  ?>>No</option>
        <option value="1" <?=  ($split['other_2']== 1)?"selected":""  ?>>Yes</option>
      </select>
    </div>
    <div class="form-group">
      <label for="other_3">Pricing :</label>
      <input type="text" class="form-control" name="other_3" value="<?= $split['other_3'] ?>" placeholder="Book or Quote Number">
    </div>

    <div class="form-group" id="deliveredGoods" style="padding:0 15px;">
      <label>Delivered Goods :</label>
      <div class="row">
        <div class="col-md-8">Delivered Goods
        </div>
        <div class="col-md-4">Qty
        </div>
      </div>
      <div class="row">
        <input type="text" class="col-md-8 deliveredGoods" name="1_DG" value="<?=  (isset($deliveredGoods['1_DG'])?$deliveredGoods['1_DG']:"")  ?>">
        <input type="text" class="col-md-4 deliveredGoods" name="1_DGQty" value="<?=  (isset($deliveredGoods['1_DGQty'])?$deliveredGoods['1_DGQty']:"")  ?>">
      </div>
      <div class="row">
        <input type="text" class="col-md-8 deliveredGoods" name="2_DG" value="<?=  (isset($deliveredGoods['2_DG'])?$deliveredGoods['2_DG']:"")  ?>">
        <input type="text" class="col-md-4 deliveredGoods" name="2_DGQty" value="<?=  (isset($deliveredGoods['2_DGQty'])?$deliveredGoods['2_DGQty']:"")  ?>">
      </div>
      <div class="row">
        <input type="text" class="col-md-8 deliveredGoods" name="3_DG" value="<?=  (isset($deliveredGoods['3_DG'])?$deliveredGoods['3_DG']:"")  ?>">
        <input type="text" class="col-md-4 deliveredGoods" name="3_DGQty" value="<?=  (isset($deliveredGoods['3_DGQty'])?$deliveredGoods['3_DGQty']:"")  ?>">
      </div>
    </div>

    <div class="form-group">
      <input type="hidden" class="form-control" name="other_4" id="other_4" value="<?= $split['other_4'] ?>">
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
<script>
$("#ref_customerST").change(function() {
  $("#id_contactST").load("controller/lstContact-controller.php?id_contact=<?= $split['id_contactST'] ?>&ref_customer=" + $("#ref_customerST").val());
}).change();

</script>
