<div class="col-md-6" style="height:100%">
  <table id="table_Report" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" style="white-space:nowrap;">
    <thead>
      <tr>
        <th>Split</th>
        <th><acronym title="Generation of PDF report">Report</acronym></th>
        <th><acronym title="Revision of the report">Rev</acronym></th>
        <th><acronym title="Quality signature on report">Q</acronym></th>
        <th><acronym title="Technical Manager signature on report">TM</acronym></th>
        <th><acronym title="Date of sending the report to customer">Date</acronym></th>
        <th><acronym title="Count of Rawdata sent to customer">RawData Sent</acronym></th>
        <th><acronym title="Count of Specimen in this split">Expected</acronym></th>
        <th><acronym title="Count of specimen shipped">Shipped</acronym></th>
      </tr>
    </thead>

    <tbody>
      <?php  foreach ($splits as $splitJob): ?>
        <?php if (is_numeric($splitJob['split'])): ?>
          <tr>
            <td><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></td>
            <td>
              <a href="controller/createReportPDF-controller?&id_tbljob=<?=	$splitJob['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Combine PDF(s) located on 'Rapports Temp' & 'Annexe PDF', numbers them and add 'End of report' at the end. It also copy the PDF and xlsx report on 'Rapports Finals'">
                Create Final PDF Report
              </a>
            </td>
            <td class="report_rev" data-idtbljob="<?=	$splitJob['id_tbljob']	?>" data-report_rev="<?= $splitJob['report_rev']  ?>" role="button"><?= $splitJob['report_rev']  ?></td>
            <td class="report_Q <?=  ($splitJob['report_Q']>0)?'ok':'nok'  ?>" data-idtbljob="<?=	$splitJob['id_tbljob']	?>" data-report_Q="<?= $splitJob['report_Q']  ?>" role="button"><?= $splitJob['report_Q']  ?></td>
            <td class="report_TM <?=  ($splitJob['report_TM']>0)?'ok':'nok'  ?>" data-idtbljob="<?=	$splitJob['id_tbljob']	?>" data-report_TM="<?= $splitJob['report_TM']  ?>" role="button"><?= $splitJob['report_TM']  ?></td>
            <td class="report_send report_send<?=	(($splitJob['report_send']<0)?0:$splitJob['report_send'])	?>" data-report_send="<?=	$splitJob['report_send']	?>" data-idtbljob="<?=	$splitJob['id_tbljob']	?>" role="button"><acronym title='<?= $splitJob['report_send'] ?>'><?= ($splitJob['report_send']>0)?$splitJob['report_date']:''  ?></acronym></td>
            <td class="<?=  (($splitJob['rawdatatobesent']==0)?'none':(($splitJob['nbrawdatasent']==$splitJob['expected'])?'ok':'nok')) ?>"><?= $splitJob['nbrawdatasent'] ?></td>
            <td><?= $splitJob['expected']  ?></td>
            <td class="<?=  ($splitJob['shipped']==$splitJob['expected'])?'ok':'nok'  ?>"><?= $splitJob['shipped']  ?></td>
          </tr>
        <?php  endif ?>
      <?php  endforeach  ?>
    </tbody>
  </table>
  <div id="dialog-rev" style="display:none;"  title="Revision Management">
    <p><span class="ui-icon ui-icon-info" style="float:left; margin:12px 12px 20px 0;"></span>Increase the revision number (or set to 0) on this Report ?<br/>Only Quality Manager should do this</p>
  </div>

  <div id="dialog-report_date" style="display:none;" title="Report Emission">
    <p class="validateTips">Date of the report emission</p>
    <form>
      <fieldset>
        <label for="report_date">Date</label>
        <input type="text" name="report_date" id="report_date" class="text ui-widget-content ui-corner-all">
        <!-- Allow form submission with keyboard without duplicating the dialog button -->
        <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
      </fieldset>
    </form>
  </div>
</div>
<div class="col-md-6" style="height:100%">
  <table id="table_Report2" class="table table-condensed table-bordered" cellspacing="0" style="white-space:nowrap;">
    <caption>Global report for the job</caption>
    <thead>
      <tr>
        <th colspan="1">Language</th>
        <th colspan="2"><acronym title="Summary page for this job">Excel Report</acronym></th>
        <th colspan="1"><acronym title="Generation of PDF report">PDF Fusion</acronym></th>
        <th colspan="1"><acronym title="Revision of the report">Rev</acronym></th>
        <th colspan="1"><acronym title="Quality signature on report">Q</acronym></th>
        <th colspan="1"><acronym title="Technical Manager signature on report">TM</acronym></th>
        <th colspan="1"><acronym title="Date of sending the report to customer">Date</acronym></th>
        <th colspan="1"><acronym title="Count of Specimen in this split">Expected</acronym></th>
        <th colspan="1"><acronym title="Count of specimen shipped">Shipped</acronym></th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td><img src="img/FlagFrench.png" style="width: auto;max-height: 30px;"></td>
        <td>
          <?php if (file_exists('templates/Report Default_FR_Std.xlsm')) :  ?>
            <a href="controller/createReport-controller.php?id_tbljob=<?= $split['id_tbljob']  ?>&type=Job&language=FR&specific=Std" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Create an Excel Standard Summary for this job">
              Standard
            </a>
          <?php else :  ?>
            <a href="#" class="btn btn-default btn-lg disabled" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Create an Excel Standard Summary for this job">
              Standard
            </a>
          <?php endif ?>
        </td>
        <td>
          <?php if (file_exists('templates/Report Default_FR_'.$split['specific_test'].'.xlsm')) :  ?>
            <a href="controller/createReport-controller.php?id_tbljob=<?= $split['id_tbljob']  ?>&type=Job&language=FR&specific=<?= $split['specific_test'] ?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Create an Excel Specific Summary for this job">
              Specific
            </a>
          <?php else :  ?>
            <a href="#" class="btn btn-default btn-lg disabled" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Create an Excel Specific Summary for this job">
              Specific
            </a>
          <?php endif ?>
        </td>
        <td rowspan="2" style="vertical-align: middle;">
          <a href="controller/createReportJobPDF-controller?&id_tbljob=<?= $split['id_tbljob']  ?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;"
            title="Combine all the Annexe(s) located on 'Rapports Final' on GPM's order. It also zip all the xlsx Annexe(s)">
            Create Global PDF Report for the Job
          </a>
        </td>
        <td rowspan="2" style="vertical-align: middle;" class="report_rev" data-idJob="<?=	$split['id_tbljob']	?>" data-report_rev="<?= $infoJob['info_job_rev']  ?>" role="button"><?= $infoJob['info_job_rev']  ?></td>
        <td class="report_Q <?=  ($infoJob['info_job_report_Q']>0)?'ok':'nok'  ?>" data-idJob="<?=	$split['id_tbljob']	?>" data-report_Q="<?= $infoJob['info_job_report_Q']  ?>" role="button"><?= $infoJob['info_job_report_Q']  ?></td>
        <td class="report_TM <?=  ($infoJob['info_job_report_TM']>0)?'ok':'nok'  ?>" data-idJob="<?=	$split['id_tbljob']	?>" data-report_TM="<?= $infoJob['info_job_report_TM']  ?>" role="button"><?= $infoJob['info_job_report_TM']  ?></td>
        <td rowspan="2" style="vertical-align: middle;" class="report_send report_send<?=	(($infoJob['info_job_send']<0)?0:$infoJob['info_job_send'])	?>" data-report_send="<?=	$infoJob['info_job_send']	?>" data-idJob="<?=	$split['id_tbljob']	?>" role="button"><acronym title='<?= $infoJob['info_job_send'] ?>'><?= ($infoJob['info_job_send']>0)?$infoJob['info_job_date']:''  ?></acronym></td>
        <td rowspan="2" style="vertical-align: middle;"><?= $countInfojob['expected']  ?></td>
        <td rowspan="2" style="vertical-align: middle;" class="<?=  ($countInfojob['shipped']==$countInfojob['expected'])?'ok':'nok'  ?>"><?= $countInfojob['shipped']  ?></td>
      </tr>
      <tr>
        <td><img src="img/FlagUSA.png" style="width: auto;max-height: 30px;"></td>
        <td>
          <?php if (file_exists('templates/Report Default_USA_Std.xlsm')) :  ?>
            <a href="controller/createReport-controller.php?id_tbljob=<?= $split['id_tbljob']  ?>&type=Job&language=USA&specific=Std" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Create an Excel Standard Summary for this job">
              Standard
            </a>
          <?php else :  ?>
            <a href="#" class="btn btn-default btn-lg disabled" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Create an Excel Standard Summary for this job">
              Standard
            </a>
          <?php endif ?>
        </td>
        <td>
          <?php if (file_exists('templates/Report Default_USA_'.$split['specific_test'].'.xlsm')) :  ?>
            <a href="controller/createReport-controller.php?id_tbljob=<?= $split['id_tbljob']  ?>&type=Job&language=USA&specific=<?= $split['specific_test'] ?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Create an Excel Specific Summary for this job">
              Specific
            </a>
          <?php else :  ?>
            <a href="#" class="btn btn-default btn-lg disabled" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;" title="Create an Excel Specific Summary for this job">
              Specific
            </a>
          <?php endif ?>
        </td>

        <td class="report_Q <?=  ($infoJob['info_job_report_Q']>0)?'ok':'nok'  ?>" data-idJob="<?=	$split['id_tbljob']	?>" data-report_Q="<?= $infoJob['info_job_report_Q']  ?>" role="button"><?= $infoJob['info_job_report_Q']  ?></td>
        <td class="report_TM <?=  ($infoJob['info_job_report_TM']>0)?'ok':'nok'  ?>" data-idJob="<?=	$split['id_tbljob']	?>" data-report_TM="<?= $infoJob['info_job_report_TM']  ?>" role="button"><?= $infoJob['info_job_report_TM']  ?></td>
      </tr>
    </tbody>
  </table>
</div>
