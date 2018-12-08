<div class="col-md-12" style="height:100%">
  <table id="table_Report" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" style="white-space:nowrap;">
    <thead>
      <tr>
        <td>Split</td>
        <td>Report</td>
        <td>Rev</td>
        <td>Q</td>
        <td>TM</td>
        <td>Date</td>
        <td>RawData Sent</td>
        <td>Expected</td>
        <td>Shipped</td>
      </tr>
    </thead>

    <tbody>
      <?php  foreach ($splits as $splitJob): ?>
        <?php if (is_numeric($splitJob['split'])): ?>
          <tr>
            <td><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></td>
            <td>
              <a href="controller/createReportPDF-controller?&id_tbljob=<?=	$splitJob['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;">
                Create PDF
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
