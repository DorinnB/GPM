<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">

    <?php
    if (!isset($_GET['dateStartUBR'])) {
      echo "<script type='text/javascript'>document.location.replace('index.php?page=ubr&dateStartUBR=".date('Y-m-d',strtotime('first day of january'))."');</script>";
    }
    ?>

    <div style="display:none;" id="dateStartUBR"><?= $_GET['dateStartUBR'] ?></div>

    <link href="css/ubr.css" rel="stylesheet">

    <div class="col-md-12" style="height:100%">

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



  </div>
</div>
<?php
require('views/login-view.php');
?>
