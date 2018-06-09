<script type="text/javascript" src="js/document.js"></script>
<div id="document" class="container-fluid" style="padding:10px; ">
  <div class="col-sm-12" style="font-size:130%; ">
    <?= $_POST['type'] ?>
  </div>
  <div class="col-sm-8" style="overflow:auto; height:80%">
    <?php if ($_POST['type']!='Other') :  ?>
      <div class="col-sm-12" style=" ">
        <a href="controller/create<?= $_POST['type'] ?>-controller.php?id_ep=<?= $_POST['id_tbl'] ?>">
          <div class="col-sm-2" style=" ">
            <img  src="img/add.png" height="20" alt="" />
          </div>
          <div class="col-sm-10" style="  text-align:left;">
            Print new <?=  $_POST['type'] ?>
          </div>
        </a>
      </div>
    <?php endif ?>

    <?php foreach ($documents as $key => $value) :  ?>
      <div class="col-sm-12" style=" ">
        <a href="controller/downloadDocument-controller.php?id_document=<?= $value['id_document'] ?>">
          <div class="col-sm-2" style=" ">
            <?= $key+1  ?>
          </div>
          <div class="col-sm-10" style="  text-align:left;">
            <?= $value['file']  ?>
          </div>
        </a>
      </div>
    <?php endforeach ?>




  </div>
  <div class="col-sm-4 image-upload">
    <label for="file-input">
      <img src="img/upload.png" height="80"/>
    </label>

      <input id="file-input" type="file"  style=display:none;/>
      <input id="id" type="hidden" value="<?= $_POST['id_tbl'] ?>">
      <input id="tbl" type="hidden" value="eprouvettes">
      <input id="type" type="hidden" value="<?= $_POST['type'] ?>">      
<!--<input id="file-input" type="file" multiple size="50" onchange="downloadFile()" style=display:none;/>-->
  </div>
</div>
