<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">

    <?php
    if (!isset($_GET['dateStartInvoice'])) {
      echo "<script type='text/javascript'>document.location.replace('index.php?page=invoices&dateStartInvoice=".date('Y-m-d',strtotime('first day of january'))."');</script>";
    }
    ?>
    <div style="display:none;" id="dateStartInvoice"><?= $_GET['dateStartInvoice'] ?></div>


    <link href="css/payables.css" rel="stylesheet">

    <div class="col-md-12" style="height:100%">
      <table id="table_invoices" class="table table-condensed table-striped nowrap table-hover table-bordered dataTable" cellspacing="0" width="100%">
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



  </div>
</div>
<?php
require('views/login-view.php');
?>
