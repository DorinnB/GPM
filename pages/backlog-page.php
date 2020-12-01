<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_accounting()) : ?>
      <?php
      if (!isset($_GET['dateStartBacklog'])) {
        //echo "<script type='text/javascript'>document.location.replace('index.php?page=backlog&dateStartBacklog=".date('Y-m-d',strtotime('first day of january'))."');</script>";
        echo "<script type='text/javascript'>document.location.replace('index.php?page=backlog&dateStartBacklog=2000-01-01');</script>";
      }
      ?>
      <div style="display:none;" id="dateStartBacklog"><?= $_GET['dateStartBacklog'] ?></div>


      <link href="css/ubr.css" rel="stylesheet">

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

        <table id="table_backlog" class="table table-condensed table-striped table-hover table-bordered dataTable" cellspacing="0" width="100%">
          <caption><acronym title="Default Filter on INV = R">BACKLOG LIST</acronym></caption>
          <thead>
            <tr>
              <th colspan="4">Job</th>
              <th colspan="3">PO</th>
              <th colspan="5">MRSAS</th>
              <th colspan="5">SubC</th>
            </tr>
            <tr>
              <th>Creation Date</th>
              <th>INV</th>
              <th>Customer</th>
              <th>Job</th>
              <th>PO Amount</th>
              <th>Reached</th>
              <th>Backlog</th>

              <th>Estimated MRSAS</th>
              <th>Reached</th>
              <th>UBR MRSAS</th>
              <th>Invoices MRSAS</th>
              <th>Backlog MRSAS</th>

              <th>Estimated SubC</th>
              <th>Reached</th>
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
