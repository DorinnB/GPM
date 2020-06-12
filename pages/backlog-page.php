<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">


    <div style="display:none;" id="dateStartUBR"><?= $_GET['dateStartUBR'] ?></div>

    <link href="css/ubr.css" rel="stylesheet">

    <div class="col-md-12" style="height:100%">

      <table id="table_backlog" class="table table-condensed table-striped table-hover table-bordered dataTable" cellspacing="0" width="100%">
        <caption>BACKLOG LIST</caption>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Job</th>
            <th>PO Amount</th>
            <th>Estimated</th>
            <th>UBR</th>
            <th>Invoices</th>
            <th>Backlog</th>
          </tr>
          <tr>
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
            <th>Customer</th>
            <th>Job</th>
            <th>PO Amount</th>
            <th>Estimated</th>
            <th>UBR</th>
            <th>Invoices</th>
            <th>Backlog</th>
          </tr>
        </tfoot>
      </table>
    </div>


    <script type="text/javascript" src="js/backlog.js"></script>



  </div>
</div>
<?php
require('views/login-view.php');
?>
