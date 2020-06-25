<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_accounting()) : ?>
      <?php
      if (!isset($_GET['dateStartPayable'])) {
        echo "<script type='text/javascript'>document.location.replace('index.php?page=payables&dateStartPayable=".date('Y-m-d',strtotime('first day of january'))."');</script>";
      }
      ?>
      <div style="display:none;" id="dateStartPayable"><?= $_GET['dateStartPayable'] ?></div>


      <link href="css/payables.css" rel="stylesheet">

      <div class="col-md-12" style="height:100%">
        <div class="row" style="height:5%;margin-top:10px;">
          <div id="btn" class="col-md-3" style="height:100%;">
          </div>

          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=purchases" title="Purchases" class="btn btn-link  btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/purchaserequest.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=payables" title="Payables" class="btn disabled btn-link btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/payable.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=UBR" title="UBR" class="btn btn-link btn-lg"style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/ubr.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=invoices" title="Invoices" class="btn btn-link btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/invoice.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=backlog" title="backlog" class="btn btn-link btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/backlog.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="controller/createInvoicablePayables-controller.php?dateStart=<?= $_GET['dateStartPayable'] ?>" title="Accounting Files" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/export.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
            </a>
          </div>
        </div>

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
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
      <script type="text/javascript" src="js/payables.js"></script>
    <?php else : ?>
    </br>
    You are not allowed to reach this page!
  <?php endif ?>

</div>
</div>
<?php
require('views/login-view.php');
?>
