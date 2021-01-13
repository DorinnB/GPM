<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<?php
include('controller/prodIndicator-controller.php');
?>



<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_accounting()) : ?>
      <?php
      if (!isset($_GET['dateStart'])) {
        //echo "<script type='text/javascript'>document.location.replace('index.php?page=prodIndicator&dateStart=".date('Y-m-d',strtotime('first day of january'))."');</script>";
        echo "<script type='text/javascript'>document.location.replace('index.php?page=prodIndicator&dateStart=2000-01-01');</script>";
      }
      ?>
      <div style="display:none;" id="dateStar"><?= $_GET['dateStart'] ?></div>


      <link href="css/prodIndicator.css" rel="stylesheet">

      <div class="col-md-12" style="height:10%">
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


      </div>
      <div class="col-md-12" style="height:80%">




        <table id="table_prodIndicatorXXXXXXXXXXXXXX" class="table table-condensed table-striped table-hover table-bordered dataTable" cellspacing="0" width="100%" style="display: block; overflow: auto; white-space: nowrap; height: 100%;">
        <caption><acronym title="">PRODUCTION INDICATOR</acronym></caption>
        <thead>
          <tr>
            <th>Titre</th>
            <th>Titre</th>
            <?php foreach ($tableau as $key => $value) : ?>
              <th><?= $key ?></th>
            <?php endforeach  ?>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td rowspan="2" class="ubr">UBR</td>
            <td class="decimal2 ubr">UBR MRSAS</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['ubrMRSAS'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 ubr">UBR Total</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['ubrMRSAS']+$value['ubrSubC'] ?></td>
            <?php endforeach  ?>
          </tr>

          <tr>
            <td rowspan="2" class="inv">INV</td>
            <td class="decimal2 inv">INV MRSAS</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['inv_mrsas'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 inv">INV Total</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['inv_mrsas']+$value['inv_subc'] ?></td>
            <?php endforeach  ?>
          </tr>

          <tr>
            <td rowspan="8" class="payables">Payables</td>
            <td class="decimal2 payables">MRI EURO</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['payable_2'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 payables">Postage</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['payable_4'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 payables">Energie</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['payable_5'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 payables">Trips et Rbst frais</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['payable_11'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 payables">Production</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['payable_7'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 payables">others</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['payable_1'] + $value['payable_3'] + $value['payable_6'] + $value['payable_8'] + $value['payable_9'] + $value['payable_10'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 payables">Investissement</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['payable_capitalized'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 payables">Total</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['payable_1']+$value['payable_2']+$value['payable_3']+$value['payable_4']+$value['payable_5']+$value['payable_6']+$value['payable_7']+$value['payable_8']+$value['payable_9']+$value['payable_10']+$value['payable_11']+$value['payable_capitalized'] ?></td>
            <?php endforeach  ?>
          </tr>

          <tr>
            <td rowspan="2" class="hr">HR Prod</td>
            <td class="decimal2 hr">Jrs Travaillés</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['C1'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 hr">Jrs Maladie</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['C5'] ?></td>
            <?php endforeach  ?>
          </tr>

          <tr>
            <td rowspan="9" class="unknow">unknow</td>
            <td class="decimal2 unknow">Backlog Test</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 unknow">Backlog Total</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 unknow">Entrées Cde MRSAS mensuelle</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 unknow">Monthly Production MRSAS</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['var_ubrMRSAS']+$value['inv_mrsas'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 unknow">Monthly Production Total</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['var_ubrMRSAS']+$value['var_ubrSubC']+$value['inv_mrsas']+$value['inv_subc'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 unknow">Yearly Production MRSAS</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['c_var_ubrMRSAS']+$value['c_inv_mrsas'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 unknow">Yearly Production Total</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['c_var_ubrMRSAS']+$value['c_var_ubrSubC']+$value['c_inv_mrsas']+$value['c_inv_subc'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 unknow">SALES HT MRSAS</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['c_inv_mrsas'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 unknow">SALES Total</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['c_inv_mrsas']+$value['c_inv_subc'] ?></td>
            <?php endforeach  ?>
          </tr>

          <tr>
            <td rowspan="4" class="prodperf">Prod Perf</td>
            <td class="decimal2 prodperf">Ratio Prod</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= ($value['var_ubrMRSAS']+$value['inv_mrsas']) / $value['C1'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 prodperf">Total Tests</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['nbTest'] ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 prodperf">Occupation Réelle</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= $value['cycling'] / $value['cumul'] * 100 ?></td>
            <?php endforeach  ?>
          </tr>
          <tr>
            <td class="decimal2 prodperf">Occupation Etendue</td>
            <?php foreach ($tableau as $key => $value) : ?>
              <td class="decimal2"><?= ($value['cycling']+$value['rampToTemp']+$value['noncycling']) / $value['cumul'] * 100 ?></td>
            <?php endforeach  ?>
          </tr>

        </tbody>
      </table>


    </div>


    <script type="text/javascript" src="js/prodIndicator.js"></script>

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
