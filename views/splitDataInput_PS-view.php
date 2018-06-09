<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData" style="height:100%;">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">

    <div class="form-group">
      <label for="Spec">Specification&nbsp;:</label>
      <input type="text" class="form-control" name="specification" value="<?= $split['specification'] ?>">
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="Waveform">Waveform&nbsp;:</label>
          <select class="form-control" name="waveform">
            <option value="Sinus" <?=  ($split['waveform']=="Sinus")?'selected':'' ?>>Sinus</option>
            <option value="Triangle" <?=  ($split['waveform']=="Triangle")?'selected':'' ?>>Triangle</option>
            <option value="Rampe" <?=  ($split['waveform']=="Rampe")?'selected':'' ?>>Rampe</option>
          </select>
        </div>
        <div class="form-group">
          <label for="tbljob_frequence">Frequency&nbsp;:</label>
          <input type="text" class="form-control" name="tbljob_frequence" value="<?= $split['tbljob_frequence'] ?>">
        </div>

        <div class="form-group">
          <label for="Consigne 1">Consigne 1&nbsp;:</label>
          <select class="form-control" name="c_type_1">
            <?php foreach ($Consigne as $row): ?>
              <option value="<?= $row['id_consigne_type'] ?>" <?=  ($row['id_consigne_type']==$split['id_c_type_1'])?'selected':''    ?>><?= $row['consigne_type'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label for="Consigne 2">Consigne&nbsp;2&nbsp;:</label>
          <select class="form-control" name="c_type_2">
            <?php foreach ($Consigne as $row): ?>
              <option value="<?= $row['id_consigne_type'] ?>" <?=  ($row['id_consigne_type']==$split['id_c_type_2'])?'selected':''    ?>><?= $row['consigne_type'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label for="Units">Units&nbsp;:</label>
          <select class="form-control" name="c_unite">
            <option value="%" <?=  ($split['c_unite']=="%")?'selected':'' ?>>%</option>
            <option value="kN" <?=  ($split['c_unite']=="kN")?'selected':'' ?>>kN</option>
            <option value="MPa" <?=  ($split['c_unite']=="MPa")?'selected':'' ?>>MPa</option>
          </select>
        </div>



      </div>
      <div class="col-md-6">

        <div class="form-group">
          <label for="other_1">Rotation &nbsp;:</label>
          <select class="form-control" name="other_1">
            <option value="0" <?=  ($split['other_1']==0)?'selected':''    ?>>No</option>
            <option value="1" <?=  ($split['other_1']==1)?'selected':''    ?>>1</option>
            <option value="2" <?=  ($split['other_1']==2)?'selected':''    ?>>2</option>
          </select>
        </div>
        <div class="form-group">
          <label for="other_2">APS Min. (%)&nbsp;:</label>
          <input type="text" class="form-control" name="other_2" value="<?= $split['other_2'] ?>">
        </div>
        <div class="form-group">
          <label for="id_rawData">Raw Data&nbsp;:</label>
          <select class="form-control" name="id_rawData">
            <?php foreach ($RawData as $row): ?>
              <option value="<?= $row['id_rawData'] ?>" <?=  ($row['id_rawData']==$split['id_rawData'])?'selected':''    ?>><?= $row['name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>

        <div class="form-group">
          <label for="staircase">Staircase&nbsp;:</label>
          <select class="form-control" name="staircase">
            <option value="0" <?=  ($split['staircase']==0)?'selected':''    ?>>No</option>
            <option value="1" <?=  ($split['staircase']==1)?'selected':''    ?>>Yes</option>
          </select>
        </div>
        <div class="form-group">
          <label for="specific_protocol">Specific&nbsp;Protocol&nbsp;:</label>
          <select class="form-control" name="specific_protocol">
            <option value="0" <?=  ($split['specific_protocol']==0)?'selected':''    ?>>No</option>
            <option value="1" <?=  ($split['specific_protocol']==1)?'selected':''    ?>>Yes</option>
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


    <div class="form-group">
      <label for="Dy T">Dy&nbsp;T&nbsp;:</label>
      <input type="text" class="form-control" name="DyT_Cust" id="DyT_Cust" value="<?= $split['DyT_Cust'] ?>">
    </div>

  </form>
</div>
<script type="text/javascript" src="js/splitDataInput.js"></script>
