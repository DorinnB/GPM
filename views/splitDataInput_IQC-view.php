<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">
    <div class="form-group">
      <label for="Spec">Spec :</label>
      <input type="text" class="form-control" name="specification" value="<?= $split['specification'] ?>">
    </div>

    <div class="form-group">
      <label for="comments">Equipment :</label>
      <input type="text" class="form-control" name="comments" value="<?= $split['comments'] ?>">
    </div>

    <div class="form-group">
      <label for="Dy T">Dy T :</label>
      <input type="text" class="form-control" name="DyT_Cust" id="DyT_Cust" value="<?= $split['DyT_Cust'] ?>">
    </div>

  </form>
</div>
<script type="text/javascript" src="js/splitDataInput.js"></script>
