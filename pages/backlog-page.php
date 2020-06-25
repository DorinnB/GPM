<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_accounting()) : ?>

      <div style="display:none;" id="dateStartUBR"><?= $_GET['dateStartUBR'] ?></div>

      <link href="css/ubr.css" rel="stylesheet">

      <div class="col-md-12" style="height:100%">
        <div class="row" style="height:5%;margin-top:10px;">
          <div id="btn" class="col-md-3" style="height:100%;">
          </div>

          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=purchases" title="Purchases" class="btn btn-link  btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
              <img type="image" src="img/purchaserequest.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
            </a>
          </div>
          <?php if($user->is_accounting()) : ?>
            <div id="" class="col-md-1" style="height:100%;">
              <a href="index.php?page=payables" title="Payables" class="btn btn-link btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
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
              <a href="index.php?page=backlog" title="backlog" class="btn disabled btn-link btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
                <img type="image" src="img/backlog.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
              </a>
            </div>
          <?php endif ?>
        </div>

        <table id="table_backlog" class="table table-condensed table-striped table-hover table-bordered dataTable" cellspacing="0" width="100%">
          <caption>BACKLOG LIST</caption>
          <thead>
            <tr>

              <th colspan="5">Job</th>
              <th colspan="4">MRSAS</th>

              <th colspan="4">SubC</th>

            </tr>
            <tr>
              <th>INV</th>
              <th>Customer</th>
              <th>Job</th>
              <th>PO Amount</th>
              <th>Backlog</th>

              <th>Estimated MRSAS</th>
              <th>UBR MRSAS</th>
              <th>Invoices MRSAS</th>
              <th>Backlog MRSAS</th>

              <th>Estimated SubC</th>
              <th>UBR SubC</th>
              <th>Invoices SubC</th>
              <th>Backlog SubC</th>

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
            </tr>
          </tfoot>
        </table>
      </div>


      <script type="text/javascript" src="js/backlog.js"></script>

    <?php else : ?>
    </br>
    You are not allowed to reach this page!
  <?php endif ?>


</div>
</div>
<?php
require('views/login-view.php');
?>
