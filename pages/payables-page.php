<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_accounting()) : ?>
      <?php
      if (!isset($_GET['dateStartPayable'])) {
                echo "<script type='text/javascript'>document.location.replace('index.php?page=payables&dateStartPayable=2020-01-01');</script>";
        echo "<script type='text/javascript'>document.location.replace('index.php?page=payables&dateStartPayable=".date('Y-m-d',strtotime('first day of january'))."');</script>";
      }
      ?>
      <div style="display:none;" id="dateStartPayable"><?= $_GET['dateStartPayable'] ?></div>


      <link href="css/payables.css" rel="stylesheet">

      <div class="col-md-12" style="height:100%">
        <div class="row" style="height:5%;margin-top:10px;">
          <div id="btn" class="col-md-2" style="height:100%;">
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=purchases" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;" role="button"><img src="img/purchaserequest.png" style="height:40px;" > POR</a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a href="index.php?page=payables" class="btn btn-info" style="width:100%; margin: 0px; padding:0px; border-radius:10px;" role="button"><img src="img/payable.png" style="height:40px;" > Payables</a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a  href="index.php?page=invoices" class="btn btn-info" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/invoice.png" style="height:40px;"> Invoices</a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a  href="index.php?page=quotations" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/quotation.png" style="height:40px;"> Quotations</a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a  href="index.php?page=UBR" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/ubr.png" style="height:40px;"> UBR</a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a  href="index.php?page=backlog" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/backlog.png" style="height:40px;"> Backlog</a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a  href="index.php?page=monthlyStatement" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/statement.png" style="height:40px;"> Monthly Stat.</a>
          </div>
          <div id="" class="col-md-1" style="height:100%;">
            <a  href="#" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;" role="button" title="Accounting Files" data-toggle="modal" data-target="#AccountingFileModal" ><img src="img/export.png" style="height:40px;"> Acc. File</a>
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
