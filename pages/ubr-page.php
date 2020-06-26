<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_accounting()) : ?>

      <?php
      if (!isset($_GET['dateStartUBR'])) {
        echo "<script type='text/javascript'>document.location.replace('index.php?page=ubr&dateStartUBR=".date('Y-m-d',strtotime('first day of january'))."');</script>";
      }
      ?>

      <div style="display:none;" id="dateStartUBR"><?= $_GET['dateStartUBR'] ?></div>

      <link href="css/ubr.css" rel="stylesheet">

      <div class="col-md-12" style="height:100%">
        <div class="row" style="height:5%;margin-top:10px;">
          <div id="btn" class="col-md-3" style="height:100%;">
          </div>

          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=purchases" title="Purchases" class="btn btn-link  btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/purchaserequest.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">Purchase
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=payables" title="Payables" class="btn btn-link btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/payable.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">Payable
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=UBR" title="UBR" class="btn btn-link btn-lg"style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/ubr.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">UBR
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=invoices" title="Invoices" class="btn btn-link btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/invoice.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">Invoice
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=backlog" title="backlog" class="btn btn-link btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/backlog.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">Backlog
            </a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="controller/createInvoicablePayables-controller.php?dateStart=<?= $_GET['dateStartUBR'] ?>" title="Accounting Files" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/export.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">Excel File
            </a>
          </div>
        </div>

        <table id="table_ubr" class="table table-condensed table-striped table-hover table-bordered dataTable" cellspacing="0" width="100%">
          <caption>UBR LIST</caption>
          <thead>
            <tr>
              <th>Date UBR</th>
              <th>Date UBR</th>
              <th>Date Insertion</th>
              <th>Job</th>
              <th>UBR MRSAS</th>
              <th>UBR SubC</th>
              <th>UBR Total</th>
              <th></th>
            </tr>
            <tr>
              <th>Date UBR</th>
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
              <th>Date UBR</th>
              <th>date ubr</th>
              <th>date creation</th>
              <th>job</th>
              <th>ubr MRSAS</th>
              <th>ubr SubC</th>
              <th>ubr Total</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>


      <script type="text/javascript" src="js/ubr.js"></script>

    <?php else : ?>
    </br>
    You are not allowed to reach this page!
  <?php endif ?>


</div>
</div>
<?php
require('views/login-view.php');
?>
