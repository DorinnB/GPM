<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">

    <?php
    if (!isset($_GET['dateStartPurchase'])) {
      echo "<script type='text/javascript'>document.location.replace('index.php?page=purchases&dateStartPurchase=".date('Y-m-d',strtotime('first day of january'))."');</script>";
    }
    ?>
    <div style="display:none;" id="dateStartpurchase"><?= $_GET['dateStartpurchase'] ?></div>


    <link href="css/purchases.css" rel="stylesheet">

    <div class="col-md-12" style="height:100%">
      <table id="table_purchases" class="table table-condensed table-striped nowrap table-hover table-bordered dataTable" cellspacing="0" width="100%">
        <caption>PURCHASES ORDER REQUEST</caption>
        <thead>
          <tr>
            <th><acronym title="Purchase Order Request">POR</acronym></th>
            <th>Date</th>
            <th>Applicant</th>
            <th>Supplier</th>
            <th>Description</th>
            <th>Job</th>
            <th>USD HT</th>
            <th>EUR HT</th>
            <th>Comments</th>
            <th>Validator</th>
            <th><acronym title="Click here to generate your PO number">PO</acronym></th>
            <th>Tech. Approv.</th>
          </tr>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <script type="text/javascript" src="js/purchases.js"></script>



  </div>
</div>
<?php
require('views/login-view.php');
?>
