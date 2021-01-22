<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_accounting()) : ?>
      <?php
      if (!isset($_GET['dateStartInvoice'])) {
        echo "<script type='text/javascript'>document.location.replace('index.php?page=invoices&dateStartInvoice=2020-01-01');</script>";
        echo "<script type='text/javascript'>document.location.replace('index.php?page=invoices&dateStartInvoice=".date('Y-m-d',strtotime('first day of january'))."');</script>";
      }
      ?>
      <div style="display:none;" id="dateStartInvoice"><?= $_GET['dateStartInvoice'] ?></div>


      <link href="css/payables.css" rel="stylesheet">

      <div class="col-md-12" style="height:100%">
        <?php include('views/accounting_btn-view.php'); ?>

        <table id="table_invoices" class="table table-condensed table-striped nowrap table-hover table-bordered dataTable">
          <caption>INVOICES LIST</caption>
          <thead>
            <tr>
              <th><acronym title="Statut">Statut</acronym></th>
              <th><acronym title="Customer">Cust.</acronym></th>
              <th><acronym title="Job Number">Job</acronym></th>
              <th><acronym title="PO Amount">PO</acronym></th>
              <th><acronym title="Estimated MRSAS">Est.MRSAS</acronym></th>
              <th><acronym title="Invoice N°">Inv N°</acronym></th>
              <th><acronym title="Invoice Date">Inv Date</acronym></th>
              <th><acronym title="Invoice Due Date">Due Date</acronym></th>
              <th><acronym title="HT SubC">HT SubC</acronym></th>
              <th><acronym title="HT MRSAS">HT MRSAS</acronym></th>
              <th><acronym title="HT Total">HT Total</acronym></th>
              <th><acronym title="TVA">TVA</acronym></th>
              <th><acronym title="TTC">TTC</acronym></th>
              <th><acronym title="USD/€ Exchange Rate">USD Rate</acronym></th>
              <th><acronym title="HT SubC">HT SubC</acronym></th>
              <th><acronym title="HT MRSAS">HT MRSAS</acronym></th>
              <th><acronym title="HT Total">HT Total</acronym></th>
              <th><acronym title="TVA">TVA</acronym></th>
              <th><acronym title="TTC">TTC</acronym></th>
              <th><acronym title="Invoice Payment Date">Payment Date</acronym></th>
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
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
      <script type="text/javascript" src="js/invoices.js"></script>

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
