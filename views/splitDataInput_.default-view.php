<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">

    <div class="form-group">
      <label for="Spec">Specification :</label>
      <input type="text" class="form-control" name="specification" value="<?= $split['specification'] ?>">
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
      <label for="Dy T">DyT Cust :</label>
      <input type="text" class="form-control" name="DyT_Cust" id="DyT_Cust" value="<?= $split['DyT_Cust'] ?>">
    </div>

  </form>
</div>
<script type="text/javascript" src="js/splitDataInput.js"></script>
<script>
$("#ref_customerST").change(function() {
  $("#id_contactST").load("controller/lstContact-controller.php?id_contact=<?= $split['id_contactST'] ?>&ref_customer=" + $("#ref_customerST").val());
}).change();

</script>
