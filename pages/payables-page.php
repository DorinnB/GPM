<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">

    <?php
    if (!isset($_GET['dateStartPayable'])) {
      echo "<script type='text/javascript'>document.location.replace('index.php?page=payables&dateStartPayable=".date('Y-m-d',strtotime('first day of january'))."');</script>";
    }
    ?>
    <div style="display:none;" id="dateStartPayable"><?= $_GET['dateStartPayable'] ?></div>


    <link href="css/payables.css" rel="stylesheet">

    <div class="col-md-12" style="height:100%">
      <table id="table_payables" class="table table-condensed table-striped nowrap table-hover table-bordered dataTable" cellspacing="0" width="100%">
        <caption>PAYABLES LIST</caption>
        <thead>
          <tr>
            <th></th>
            <th>Inv n°</th>
            <th>Supplier</th>
            <th>Description</th>
            <th>Type</th>
            <th>Capitalized</th>
            <th>Posted Date</th>
            <th>Date Invoice</th>
            <th>Date Due</th>
            <th>PO</th>
            <th>Approb.</th>
            <th>N° Job</th>
            <th>Applied</th>
            <th>Amount USD</th>
            <th>USD Rate</th>
            <th>HT EUR</th>
            <th>TVA EUR</th>
            <th>TTC EUR</th>
            <th>Date Paid</th>
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
            <th><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span></th>
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
    <script type="text/javascript" src="js/payables.js"></script>



  </div>
</div>
<?php
require('views/login-view.php');
?>
