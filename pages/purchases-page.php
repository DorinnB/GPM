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
      <div class="row" style="height:5%;margin-top:10px;">
        <div id="btn" class="col-md-3" style="height:100%;">
        </div>

        <div id="" class="col-md-1" style="height:100%;">
          <a href="index.php?page=purchases" title="Purchases" class="btn btn-link  btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
            <img type="image" src="img/purchaserequest.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">Purchase
          </a>
        </div>
            <?php if($user->is_accounting()) : ?>
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
          <a href="#" title="Accounting Files" class="btn btn-default btn-lg" data-toggle="modal" data-target="#AccountingFileModal" style="width:100%; height:100%; padding:0px; border-radius:10px;">
            <img type="image" src="img/export.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">Excel File
          </a>
        </div>
      <?php endif ?>
      </div>

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

<!-- Modal -->
<div id="AccountingFileModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Accounting Files Generation</h4>
      </div>
      <div class="modal-body">
        <form action="controller/createInvoicablePayables-controller.php" class="form-horizontal" method="get">
          <div class="form-group">
            <label class="control-label col-sm-2" for="dateStart">Starting Date:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="dateStart" id="dateStart" placeholder="Starting Date">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="dateEnd">Ending Date:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="dateEnd" id="dateEnd" placeholder="Ending Date">
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Submit</button>
            </div>
          </div>
       </form>
      </div>
    </div>

  </div>
</div>
<?php
require('views/login-view.php');
?>
