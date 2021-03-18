<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">
    <?php if($user->is_bu()) : ?>
      <?php
      if (!isset($_GET['dateStart']) OR !isset($_GET['dateEnd'])) {
        echo "<script type='text/javascript'>document.location.replace('index.php?page=KPI&dateStart=".date("Y-m", strtotime("-1 year", strtotime('first day of january last year')))."&dateEnd=".date("Y-m", strtotime('first day of january next year'))."');</script>";
        //echo "<script type='text/javascript'>document.location.replace('index.php?page=KPI&dateStart=2000-01-01');</script>";
      }
      else {
        include('controller/KPI-controller.php');
      }
      ?>


      <link href="css/KPI.css" rel="stylesheet">

      <div class="col-md-12" style="height:100%">
        <?php include('views/accounting_btn-view.php'); ?>

        <H1><acronym title="Key Performance Indicator">KPI</acronym></H1>

        <ul class="nav nav-tabs nav-justified">
          <li class="active"><a data-toggle="tab" href="#kpi">KPI Data</a></li>
          <li><a data-toggle="tab" href="#graphs" id="tab_graphs">Graphs</a></li>

        </ul>

        <div class="tab-content" style="height:80%">
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

                    <input type="hidden" name="dateKPI" value="<?= $key ?>">
                    <input type="hidden" name="dateKPI_y" value="<?= date("Y", strtotime($key)) ?>">
                    <input type="hidden" name="ubrMRSAS" value="<?= $value['var_ubrMRSAS'] ?>">
                    <input type="hidden" name="ubrTotal" value="<?= $value['ubrMRSAS']+$value['ubrSubC'] ?>">
                    <input type="hidden" name="var_ubrTotal" value="<?= $value['var_ubrMRSAS']+$value['var_ubrSubC'] ?>">
                    <input type="hidden" name="invMRSAS" value="<?= $value['inv_mrsas'] ?>">
                    <input type="hidden" name="invMRSAS_base" value="<?= ($value['var_ubrMRSAS']>0)?$value['var_ubrMRSAS']:0 ?>">
                    <input type="hidden" name="invTotal" value="<?= $value['inv_mrsas'] + $value['inv_subc'] ?>">
                    <input type="hidden" name="invTotal_y" value="<?= isset($tableau[$key_year])?$value['inv_mrsas'] + $tableau[$key_year]['inv_subc']:"" ?>">
                    <input type="hidden" name="prodMRSAS" value="<?= $value['var_ubrMRSAS']+$value['inv_mrsas'] ?>">
                    <input type="hidden" name="prodMRSAS_y" value="<?= isset($tableau[$key_year])?$tableau[$key_year]['var_ubrMRSAS']+$tableau[$key_year]['inv_mrsas']:0 ?>">
                    <input type="hidden" name="c_inv_Total" value="<?= $value['c_inv_mrsas']+$value['c_inv_subc'] ?>">
                    <input type="hidden" name="c_inv_Total_y" value="<?= isset($tableau[$key_year])?$tableau[$key_year]['c_inv_mrsas']+$tableau[$key_year]['c_inv_subc']:0 ?>">
                    <input type="hidden" name="obj_prodMRSAS" value="<?= $value['obj_prodMRSAS'] ?>">
                    <input type="hidden" name="obj_invTotal" value="<?= $value['obj_invTotal'] ?>">

                    <input type="hidden" name="prodMRSASTech" value="<?= $value['C1']>0?round(($value['var_ubrMRSAS']+$value['inv_mrsas'])/$value['C1']):"" ?>">
                    <input type="hidden" name="occupancy" value="<?= ($value['cumul'] >0)?round($value['cycling'] / $value['cumul'] * 100,1):"" ?>">
                    <input type="hidden" name="c_prodMRSAS" value="<?= $value['c_var_ubrMRSAS']+$value['c_inv_mrsas'] ?>">
                    <input type="hidden" name="c_prodMRSAS_y" value="<?= isset($tableau[$key_year])?$tableau[$key_year]['c_var_ubrMRSAS']+$tableau[$key_year]['c_inv_mrsas']:0 ?>">

                    <input type="hidden" name="backlogMRSAS" value="<?= $value['backlogMRSAS'] ?>">
                    <input type="hidden" name="backlogTotal" value="<?= $value['backlogTotal'] ?>">
                    <input type="hidden" name="cdeMRSAS" value="<?= $value['cdeMRSAS'] ?>">

                    <input type="hidden" name="objcdeMRSASmax" value="150000">
                    <input type="hidden" name="objcdeMRSASmin" value="100000">

                    <input type="hidden" name="nbtest" value="<?= $value['nbTest'] ?>">
                    <input type="hidden" name="test_type_cat_Strain_ET" value="<?= $value['test_type_cat_Strain_ET'] ?>">
                    <input type="hidden" name="test_type_cat_Strain_RT" value="<?= $value['test_type_cat_Strain_RT'] ?>">
                    <input type="hidden" name="test_type_cat_Load_ET" value="<?= $value['test_type_cat_Load_ET'] ?>">
                    <input type="hidden" name="test_type_cat_Load_RT" value="<?= $value['test_type_cat_Load_RT'] ?>">
                    <input type="hidden" name="test_type_cat_Other_ET" value="<?= $value['test_type_cat_Other_ET'] ?>">
                    <input type="hidden" name="test_type_cat_Other_RT" value="<?= $value['test_type_cat_Other_RT'] ?>">

                    <input type="hidden" name="prod_MRSAS_ratio" value="<?= (isset($tableau[$key_year]) AND $tableau[$key_year]['var_ubrMRSAS']!=0 AND $value['var_ubrMRSAS']!=0)?(($value['c_var_ubrMRSAS']+$value['c_inv_mrsas'])-($tableau[$key_year]['c_var_ubrMRSAS']+$tableau[$key_year]['c_inv_mrsas']))/($tableau[$key_year]['c_var_ubrMRSAS']+$tableau[$key_year]['c_inv_mrsas'])*100:"" ?>">
                    <input type="hidden" name="invTotal_ratio" value="<?= (isset($tableau[$key_year]) AND ($tableau[$key_year]['inv_mrsas']+$tableau[$key_year]['inv_subc'])!=0 AND ($value['inv_mrsas']+$value['inv_subc'])!=0)?(($value['c_inv_mrsas']+$value['c_inv_subc'])-($tableau[$key_year]['c_inv_mrsas']+$tableau[$key_year]['c_inv_subc']))/($tableau[$key_year]['c_inv_mrsas']+$tableau[$key_year]['c_inv_subc'])*100:"" ?>">

                    <td class="key"><?= $key ?></td>
                    <td class="decimal2"><?= $value['ubrMRSAS'] ?></td>
                    <td class="decimal2"><?= ($value['ubrMRSAS']+$value['ubrSubC']!=0)?$value['ubrMRSAS']+$value['ubrSubC']:"" ?></td>
                    <td class="decimal2"><?= $value['inv_mrsas'] ?></td>
                    <td class="decimal2"><?= ($value['inv_mrsas']+$value['inv_subc']!=0)?$value['inv_mrsas']+$value['inv_subc']:"" ?></td>
                    <td class="decimal2"><?= $value['payable_2'] ?></td>
                    <td class="decimal2"><?= $value['payable_4'] ?></td>
                    <td class="decimal2"><?= $value['payable_5'] ?></td>
                    <td class="decimal2"><?= $value['payable_11'] ?></td>
                    <td class="decimal2"><?= $value['payable_7'] ?></td>
                    <td class="decimal2"><?= ($value['payable_1'] + $value['payable_3'] + $value['payable_6'] + $value['payable_8'] + $value['payable_9'] + $value['payable_10']>0)?$value['payable_1'] + $value['payable_3'] + $value['payable_6'] + $value['payable_8'] + $value['payable_9'] + $value['payable_10']:"" ?></td>
                    <td class="decimal2"><?= $value['payable_capitalized'] ?></td>
                    <td class="decimal2"><?= ($value['payable_1']+$value['payable_2']+$value['payable_3']+$value['payable_4']+$value['payable_5']+$value['payable_6']+$value['payable_7']+$value['payable_8']+$value['payable_9']+$value['payable_10']+$value['payable_11']+$value['payable_capitalized']>0)?$value['payable_1']+$value['payable_2']+$value['payable_3']+$value['payable_4']+$value['payable_5']+$value['payable_6']+$value['payable_7']+$value['payable_8']+$value['payable_9']+$value['payable_10']+$value['payable_11']+$value['payable_capitalized']:"" ?></td>
                    <td class="decimal2"><?= $value['C1'] ?></td>
                    <td class="decimal2"><?= $value['C5'] ?></td>
                    <td class="decimal2"><?= $value['backlogMRSAS'] ?></td>
                    <td class="decimal2"><?= $value['backlogTotal'] ?></td>
                    <td class="decimal2"><?= $value['cdeMRSAS'] ?></td>
                    <td class="decimal2"><?= ($value['var_ubrMRSAS']!=0)?$value['var_ubrMRSAS']+$value['inv_mrsas']:"" ?></td>
                    <td class="decimal2"><?= ($value['var_ubrMRSAS']!=0)?$value['var_ubrMRSAS']+$value['var_ubrSubC']+$value['inv_mrsas']+$value['inv_subc']:"" ?></td>
                    <td class="decimal2"><?= ($value['c_var_ubrMRSAS']!=0)?$value['c_var_ubrMRSAS']+$value['c_inv_mrsas']:"" ?></td>
                    <td class="decimal2"><?= ($value['c_var_ubrMRSAS']!=0)?$value['c_var_ubrMRSAS']+$value['c_var_ubrSubC']+$value['c_inv_mrsas']+$value['c_inv_subc']:"" ?></td>
                    <td class="decimal2"><?= ($value['c_var_ubrMRSAS']!=0)?$value['c_inv_mrsas']:"" ?></td>
                    <td class="decimal2"><?= ($value['c_var_ubrMRSAS']!=0)?$value['c_inv_mrsas']+$value['c_inv_subc']:"" ?></td>
                    <td class="decimal2"><?= ($value['C1']>0 AND $value['c_var_ubrMRSAS']!=0)?($value['var_ubrMRSAS']+$value['inv_mrsas']) / $value['C1']:"" ?></td>
                    <td class="decimal2"><?= $value['nbTest'] ?></td>
                    <td class="decimal2"><?= ($value['cumul'] >0)?round($value['cycling'] / $value['cumul'] * 100):"" ?></td>
                    <td class="decimal2"><?= ($value['cumul'] >0)?round(($value['cycling']+$value['rampToTemp']+$value['noncycling']) / $value['cumul'] * 100):"" ?></td>

                    <td class="decimal2"><?= $value['obj_prodMRSAS'] ?></td>
                    <td class="decimal2"><?= $value['obj_invTotal'] ?></td>

                    <td class="decimal2"><?= (isset($tableau[$key_year]) AND $tableau[$key_year]['var_ubrMRSAS']!=0 AND $value['var_ubrMRSAS']!=0)?(($value['c_var_ubrMRSAS']+$value['c_inv_mrsas'])-($tableau[$key_year]['c_var_ubrMRSAS']+$tableau[$key_year]['c_inv_mrsas']))/($tableau[$key_year]['c_var_ubrMRSAS']+$tableau[$key_year]['c_inv_mrsas'])*100:"" ?></td>
                    <td class="decimal2"><?= (isset($tableau[$key_year]) AND ($tableau[$key_year]['inv_mrsas']+$tableau[$key_year]['inv_subc'])!=0 AND ($value['inv_mrsas']+$value['inv_subc'])!=0)?(($value['c_inv_mrsas']+$value['c_inv_subc'])-($tableau[$key_year]['c_inv_mrsas']+$tableau[$key_year]['c_inv_subc']))/($tableau[$key_year]['c_inv_mrsas']+$tableau[$key_year]['c_inv_subc'])*100:"" ?></td>
                  </tr>
                <?php endforeach  ?>

              </tbody>
            </table>
          </div>

          <div id="graphs" class="tab-pane fade">
            <div id="ubrMRSAS" class="col-md-4">
            </div>
            <div id="sales" class="col-md-4">
            </div>
            <div id="cde" class="col-md-4">
            </div>
            <div id="testingProd" class="col-md-4">
            </div>
            <div id="prodTech" class="col-md-4">
            </div>
            <div id="testNumber" class="col-md-4">
            </div>
            <div id="occupancyProd" class="col-md-4">
            </div>
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
