
<form method="POST" action="controller/updateJob.php" id="updatejob">
  <div class="row">


    <div class="bs-example customer" data-example-id="basic-forms">
      <div class="row">
        <div class="col-xs-6 form-group">
          <label for="customer">Cust. #</label>
          <select id="ref_customer" name="ref_customer">
            <?php foreach ($ref_customer as $row): ?>
              <option value="<?= $row['id_entreprise'] ?>" <?=  ($job['customer']== $row['id_entreprise'])?"selected":""  ?>><?= $row['id_entreprise'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-xs-6 form-group">
          <label for="Spec">Name :</label>
          <input type="text" id="nomclient" class="form-control" name="customer" value="<?= $job['customer'] ?>" disabled>
        </div>
      </div>
    </div>

  </div>



  <div class="row">

    <div class="col-sm-6">

      <div class="bs-example material" data-example-id="basic-forms">
        <div class="form-group">
          <label for="Spec">Cust. Name</label>
          <input type="text" class="form-control" name="ref_matiere" value="<?= $job['ref_matiere'] ?>">
        </div>
        <div class="form-group">
          <label for="Spec">Metcut Std :</label>
          <select id="id_matiere_std" name="id_matiere_std">
            <option value="0">-</option>
            <?php foreach ($matiere as $row): ?>
              <option value="<?= $row['id_matiere'] ?>" <?=  (($job['id_matiere_std'] == $row['id_matiere'])?"selected":"")  ?>><?= $row['matiere'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="bs-example contact" data-example-id="basic-forms">

          <div class="form-group">
            <label for="Spec">Main Contact Person :</label>
            <select id="id_resp" name="id_resp">
              <option value="">Please choose from above</option>
              <?php foreach ($techniciens as $row): ?>
                <option value="<?= $row['id_technicien'] ?>" <?=  ($job['id_resp']== $row['id_technicien'])?"selected":""  ?>><?= $row['technicien'] ?></option>
              <?php endforeach ?>
            </select>
          </div>

          <div class="form-group">
            <label for="Spec">Report Contact :</label>
            <select id="id_contact" name="id_contact">
              <option>Please choose from above</option>
            </select>
          </div>
          <div class="form-group">
            <label for="Spec">Contact 2 :</label>
            <select id="id_contact2" name="id_contact2">
              <option>Please choose from above</option>
            </select>
          </div>
          <div class="form-group">
            <label for="Spec">Contact 3 :</label>
            <select id="id_contact3" name="id_contact3">
              <option>Please choose from above</option>
            </select>
          </div>
          <div class="form-group">
            <label for="Spec">Contact 4 :</label>
            <select id="id_contact4" name="id_contact4">
              <option>Please choose from above</option>
            </select>
          </div>
      </div>
      <div class="bs-example date" data-example-id="basic-forms">
        <div class="form-group">
          <label for="Spec">Creation Date :</label>
          <input type="text" class="form-control" id="datecreation" name="datecreation" value="<?= $job['datecreation'] ?>">
        </div>
      </div>
    </div>
    <div class="col-sm-6">

      <div class="bs-example activity" data-example-id="basic-forms">
        <div class="form-group">
          <label for="activity_type">Activity Type :</label>
          <select id="activity_type" name="activity_type">
            <?php foreach ($ini['activity_type_list'] as $row): ?>
              <option value="<?= $row ?>" <?=  (($job['activity_type'] == $row)?"selected":"")  ?>><?= $row ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label for="specific_test">Specific Test :</label>
          <select id="specific_test" name="specific_test">
            <?php foreach ($ini['specific_test_list'] as $row): ?>
              <option value="<?= $row ?>" <?=  (($job['specific_test'] == $row)?"selected":"")  ?>><?= $row ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>

      <div class="bs-example accounting" data-example-id="basic-forms">
          <div class="form-group">
            <label for="Spec">PO Ref :</label>
            <input type="text" class="form-control" name="po_number" value="<?= $job['po_number'] ?>">
          </div>
          <div class="form-group">
            <label for="Pricing">Pricing :</label>
            <select id="pricing" name="pricing">
              <option value="0">-</option>
              <?php foreach ($pricing as $row): ?>
                <option value="<?= $row['id_pricing'] ?>" <?=  (($job['pricing'] == $row['id_pricing'])?"selected":"")  ?>><?= $row['ref_pricing'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="Spec">Quote Ref :</label>
            <input type="text" class="form-control" name="devis" value="<?= $job['devis'] ?>">
          </div>

          <div class="form-group">
            <label for="Spec">Order Amount (€/$) :</label>
            <input type="text" class="form-control" name="order_val" value="<?= $job['order_val'] ?>">
          </div>
          <div class="form-group">
            <label for="Spec">Est. Order MRSAS(€/$) :</label>
            <input type="text" class="form-control" name="order_est" value="<?= $job['order_est'] ?>">
          </div>
          <div class="form-group">
            <label for="Spec">Est. Order SubC (€/$) :</label>
            <input type="text" class="form-control" name="order_est_subc" value="<?= $job['order_est_subc'] ?>">
          </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">

      <div class="bs-example comm" data-example-id="basic-forms">
        <div class="form-group">
          <label for="instruction">Instructions :</label>
          <textarea style="width:100%;" name="instruction" placeholder="Customer reference or instructions for this job"><?= $job['instruction'] ?></textarea>
        </div>
        <div class="form-group">
          <label for="commentaire">Job Comments :</label>
          <textarea style="width:100%;" name="commentaire" placeholder="Metcut informations for this job"><?= $job['commentaire'] ?></textarea>
        </div>
      </div>



    </div>
  </div>


  <input type="hidden" id="id_tbljob" name="id_tbljob" value="<?= $_GET['id_tbljob']  ?>">
  <input type="hidden" id="job" name="job" value="<?= $job['job'] ?>">
  <input type="hidden" id="dataSplit" name="dataSplit">
  <input type="hidden" id="dataSplitNumber" name="dataSplitNumber">
  <input type="hidden" id="dataEp" name="dataEp">
  <input type="hidden" id="deletedEp" name="deletedEp">

</form>

<script>
$("#ref_customer").change(function() {
  $("#id_contact").load("controller/lstContact-controller.php?id_contact=<?= $job['id_contact'] ?>&ref_customer=" + $("#ref_customer").val());
  $("#id_contact2").load("controller/lstContact-controller.php?id_contact=<?= $job['id_contact2'] ?>&ref_customer=" + $("#ref_customer").val());
  $("#id_contact3").load("controller/lstContact-controller.php?id_contact=<?= $job['id_contact3'] ?>&ref_customer=" + $("#ref_customer").val());
  $("#id_contact4").load("controller/lstContact-controller.php?id_contact=<?= $job['id_contact4'] ?>&ref_customer=" + $("#ref_customer").val());
  $.get("controller/lstClient-controller.php?&ref_customer=" + $("#ref_customer").val(),function(result)  {
    $("#nomclient").val(result);
  });


});
$("#id_contact").load("controller/lstContact-controller.php?id_contact=<?= $job['id_contact'] ?>&ref_customer=" + $("#ref_customer").val());
$("#id_contact2").load("controller/lstContact-controller.php?id_contact=<?= $job['id_contact2'] ?>&ref_customer=" + $("#ref_customer").val());
$("#id_contact3").load("controller/lstContact-controller.php?id_contact=<?= $job['id_contact3'] ?>&ref_customer=" + $("#ref_customer").val());
$("#id_contact4").load("controller/lstContact-controller.php?id_contact=<?= $job['id_contact4'] ?>&ref_customer=" + $("#ref_customer").val());
//.load charge un html. On utilise .get pour faire une requete AJAX, recuperer la valeur, et l'inserer dans la .val de l'input
$.get("controller/lstClient-controller.php?&ref_customer=" + $("#ref_customer").val(),function(result)  {
  $("#nomclient").val(result);
});
</script>
