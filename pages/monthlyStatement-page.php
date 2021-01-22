<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_accounting()) : ?>
      <?php
      if (!isset($_GET['dateStartMonthlyStatement'])) {
        echo "<script type='text/javascript'>document.location.replace('index.php?page=monthlyStatement&dateStartMonthlyStatement=".date('Y-m-d',strtotime('last day of previous month'))."');</script>";
      }
      ?>

      <div style="display:none;" id="dateStartMonthlyStatement"><?= $_GET['dateStartMonthlyStatement'] ?></div>

      <link href="css/ubr.css" rel="stylesheet">

      <div class="col-md-12" style="height:100%">
        <?php include('views/accounting_btn-view.php'); ?>

        <table id="table_ubr" class="table table-condensed table-striped nowrap table-hover table-bordered dataTable">
          <caption>MONTHLY STATEMENT - <?= date("M", strtotime($_GET['dateStartMonthlyStatement'])) ?></caption>
          <thead>
            <tr>
              <th>Customer</th>
              <th>Job</th>
              <th>Creation Date</th>
              <th>Status</th>
              <th>Invoice Date</th>
              <th>Monthly Invoice Amount</th>
              <th>UBR <?= date("M",strtotime(date("Y-m-t", strtotime($_GET['dateStartMonthlyStatement'])) . "-35 days")) ?></th>
              <th>UBR <?= date("M", strtotime($_GET['dateStartMonthlyStatement'])) ?></th>
              <th>Production</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th id="monthlyInvoice"></th>
              <th id="ubrold"></th>
              <th id="ubr"></th>
              <th id="prodMRSAS"></th>
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
            </tr>
          </tfoot>
        </table>
      </div>


      <script type="text/javascript" src="js/monthlyStatement.js"></script>

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
