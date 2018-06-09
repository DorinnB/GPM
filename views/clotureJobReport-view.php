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
        <td>RawData</td>
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
            <td class="report_rawdata <?=  ($splitJob['id_rawData']==0 AND $splitJob['report_rawdata']<=0)?'none':(($splitJob['report_rawdata']<=0)?'nok':'ok')  ?>" data-idtbljob="<?=	$splitJob['id_tbljob']	?>" data-report_rawdata="<?= $splitJob['report_rawdata']  ?>" role="button"><?= $splitJob['report_rawdata'].(($splitJob['id_rawData']==0 AND $splitJob['report_rawdata']>0)?'(?)':'')  ?></td>
            <td><?= $splitJob['expected']  ?></td>
            <td class="<?=  ($splitJob['shipped']==$splitJob['expected'])?'ok':'nok'  ?>"><?= $splitJob['shipped']  ?></td>
          </tr>
        <?php  endif ?>
      <?php  endforeach  ?>
    </tbody>
  </table>

</div>
