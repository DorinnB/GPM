<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<?php
include('controller/KPI-controller.php');
?>



<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_bu()) : ?>
      <?php
      if (!isset($_GET['dateStart'])) {
        //echo "<script type='text/javascript'>document.location.replace('index.php?page=KPI&dateStart=".date('Y-m-d',strtotime('first day of january'))."');</script>";
        echo "<script type='text/javascript'>document.location.replace('index.php?page=KPI&dateStart=2000-01-01');</script>";
      }
      ?>
      <div style="display:none;" id="dateStar"><?= $_GET['dateStart'] ?></div>


      <link href="css/KPI.css" rel="stylesheet">

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
          <?php if($user->is_bu()) : ?>
            <div id="" class="col-md-1" style="height:100%;">
              <a  href="index.php?page=kpi" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/statement.png" style="height:40px;"> KPI</a>
            </div>
          <?php endif ?>
          <div id="" class="col-md-1" style="height:100%;">
            <a  href="#" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;" role="button" title="Accounting Files" data-toggle="modal" data-target="#AccountingFileModal" ><img src="img/export.png" style="height:40px;"> Acc. File</a>
          </div>
        </div>

              <H3><acronym title="Key Performance Indicator">KPI</acronym></H3>
        <ul class="nav nav-tabs nav-justified">
          <li class="active"><a data-toggle="tab" href="#kpi">KPI</a></li>
          <li><a data-toggle="tab" href="#graphs1" id="graphs1Toggle">Graphs</a></li>
        </ul>
        <div class="tab-content">
          <div id="kpi" class="tab-pane fade in active">
            <table id="table_KPI" class="table table-condensed table-striped table-hover table-bordered dataTable" cellspacing="0">

              <thead>
                <tr>
                  <th></th>
                  <th colspan="2" class="ubr">UBR</th>
                  <th colspan="2" class="inv">INV</th>
                  <th colspan="8" class="payables">Payables</th>
                  <th colspan="2" class="hr">HR Prod</th>
                  <th colspan="9" class="unknow">unknow</th>
                  <th colspan="4" class="prodperf">Prod Perf</th>
                  <th colspan="2" class="prodperf">Objectif</th>
                  <th colspan="2" class="prodperf">N-1</th>
                </tr>
                <tr>
                  <th>Date</th>
                  <th class="decimal2 ubr">UBR MRSAS</th>
                  <th class="decimal2 ubr">UBR Total</th>
                  <th class="decimal2 inv">INV MRSAS</th>
                  <th class="decimal2 inv">INV Total</th>
                  <th class="decimal2 payables">MRI EURO</th>
                  <th class="decimal2 payables">Postage</th>
                  <th class="decimal2 payables">Energie</th>
                  <th class="decimal2 payables">Trips et Rbst frais</th>
                  <th class="decimal2 payables">Production</th>
                  <th class="decimal2 payables">others</th>
                  <th class="decimal2 payables">Investissement</th>
                  <th class="decimal2 payables">Total</th>
                  <th class="decimal2 hr">Jrs Travaildlés</th>
                  <th class="decimal2 hr">Jrs Maladie</th>
                  <th class="decimal2 unknow">Backlog Test</th>
                  <th class="decimal2 unknow">Backlog Total</th>
                  <th class="decimal2 unknow">Entrées Cde MRSAS mensuelle</th>
                  <th class="decimal2 unknow">Monthly Production MRSAS</th>
                  <th class="decimal2 unknow">Monthly Production Total</th>
                  <th class="decimal2 unknow">Yearly Production MRSAS</th>
                  <th class="decimal2 unknow">Yearly Production Total</th>
                  <th class="decimal2 unknow">SALES HT MRSAS</th>
                  <th class="decimal2 unknow">SALES Total</th>
                  <th class="decimal2 prodperf">Ratio Prod</th>
                  <th class="decimal2 prodperf">Total Tests</th>
                  <th class="decimal2 prodperf">Occupation Réelle</th>
                  <th class="decimal2 prodperf">Occupation Etendue</th>
                  <th class="decimal2 prodperf">Prod MRSAS</th>
                  <th class="decimal2 prodperf">Sales</th>
                  <th class="decimal2 prodperf">% prod N-1</th>
                  <th class="decimal2 prodperf">% Sales N-1</th>
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

              <tbody>
<?php $row=0; ?>
                <?php foreach ($tableau as $key => $value) : ?>
                <?php $row++; ?>
                  <?php $key_year = date("Y-m",strtotime("-1 year", strtotime($key))); ?>
                  <tr class="chartTR" id="row_<?= $key ?>">
                    <td class="key"><?= $key ?></td>
                    <td class="decimal2 ubrMRSAS" data-val="<?= $value['var_ubrMRSAS'] ?>"><?= $value['ubrMRSAS'] ?></td>
                    <td class="decimal2 ubrTotal" data-val="<?= $value['var_ubrMRSAS']+$value['var_ubrSubC'] ?>"><?= $value['ubrMRSAS']+$value['ubrSubC'] ?></td>
                    <td class="decimal2 invMRSAS" data-val="<?= $value['inv_mrsas'] ?>"><?= $value['inv_mrsas'] ?></td>
                    <td class="decimal2 invTotal"><?= $value['inv_mrsas']+$value['inv_subc'] ?></td>
                    <td class="decimal2"><?= $value['payable_2'] ?></td>
                    <td class="decimal2"><?= $value['payable_4'] ?></td>
                    <td class="decimal2"><?= $value['payable_5'] ?></td>
                    <td class="decimal2"><?= $value['payable_11'] ?></td>
                    <td class="decimal2"><?= $value['payable_7'] ?></td>
                    <td class="decimal2"><?= $value['payable_1'] + $value['payable_3'] + $value['payable_6'] + $value['payable_8'] + $value['payable_9'] + $value['payable_10'] ?></td>
                    <td class="decimal2"><?= $value['payable_capitalized'] ?></td>
                    <td class="decimal2"><?= $value['payable_1']+$value['payable_2']+$value['payable_3']+$value['payable_4']+$value['payable_5']+$value['payable_6']+$value['payable_7']+$value['payable_8']+$value['payable_9']+$value['payable_10']+$value['payable_11']+$value['payable_capitalized'] ?></td>
                    <td class="decimal2"><?= $value['C1'] ?></td>
                    <td class="decimal2"><?= $value['C5'] ?></td>
                    <td class="decimal2"><?= $value['backlogMRSAS'] ?></td>
                    <td class="decimal2"><?= $value['backlogTOTAL'] ?></td>
                    <td class="decimal2"><?= $value['cdeMRSAS'] ?></td>
                    <td class="decimal2 prodMRSAS" data-val="<?= $value['var_ubrMRSAS']+$value['inv_mrsas'] ?>" data-y="<?= isset($tableau[$key_year])?$tableau[$key_year]['var_ubrMRSAS']+$tableau[$key_year]['inv_mrsas']:0 ?>"><?= $value['var_ubrMRSAS']+$value['inv_mrsas'] ?></td>
                    <td class="decimal2"><?= $value['var_ubrMRSAS']+$value['var_ubrSubC']+$value['inv_mrsas']+$value['inv_subc'] ?></td>
                    <td class="decimal2"><?= $value['c_var_ubrMRSAS']+$value['c_inv_mrsas'] ?></td>
                    <td class="decimal2"><?= $value['c_var_ubrMRSAS']+$value['c_var_ubrSubC']+$value['c_inv_mrsas']+$value['c_inv_subc'] ?></td>
                    <td class="decimal2"><?= $value['c_inv_mrsas'] ?></td>
                    <td class="decimal2"><?= $value['c_inv_mrsas']+$value['c_inv_subc'] ?></td>
                    <td class="decimal2"><?= ($value['var_ubrMRSAS']+$value['inv_mrsas']) / $value['C1'] ?></td>
                    <td class="decimal2"><?= $value['nbTest'] ?></td>
                    <td class="decimal2"><?= $value['cycling'] / $value['cumul'] * 100 ?></td>
                    <td class="decimal2"><?= ($value['cycling']+$value['rampToTemp']+$value['noncycling']) / $value['cumul'] * 100 ?></td>

                    <td class="decimal2"><?= $value['obj_prodMRSAS'] ?></td>
                    <td class="decimal2"><?= $value['obj_invMRSAS'] ?></td>
                    <td class="decimal2"><?= isset($tableau[$key_year])?($value['var_ubrMRSAS']+$value['inv_mrsas'])/($tableau[$key_year]['var_ubrMRSAS']+$tableau[$key_year]['inv_mrsas'])*100:"" ?></td>
                    <td class="decimal2"><?= isset($tableau[$key_year])?$value['inv_mrsas']/$tableau[$key_year]['inv_mrsas']*100:"" ?></td>
                  </tr>
                <?php endforeach  ?>

              </tbody>
            </table>
          </div>

          <div id="graphs1" class="tab-pane fade">
            <div id='chart'></div>
          </div>
        </div>




      </div>

      <script src="lib/plotly/plotly-latest.min.js"></script>
      <script type="text/javascript" src="js/KPI.js"></script>

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
