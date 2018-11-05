<div style="float : left; font-size : 25px; vertical-align : middle; height : 100%; color : red;">
  <span>JOB : </span>
  <span><?= $job['job'] ?></span>
</div>
<div style="float : right; font-size : 25px; vertical-align : middle; height : 100%;">
  <a href="controller/copyJob.php?id_info_job=<?= $job['id_info_job'] ?>&copyID=Yes&copyRequest=Yes" onClick="return confirm('Are you sure you want to copy this Job with Split, ID & Condition?');" class="btn btn-default btn-lg" style="padding : 0px; padding-left : 10px;padding-right:10px;height: 100%;" >
    <img type="image" src="img/copyAll.png" style="max-width:100%; max-height:100%;display: block; margin: auto;" />
  </a>
  <a href="controller/copyJob.php?id_info_job=<?= $job['id_info_job'] ?>&copyRequest=Yes" onClick="return confirm('Are you sure you want to copy this Job with Split & Condition without ID?');" class="btn btn-default btn-lg" style="padding : 0px; padding-left : 10px;padding-right:10px;height: 100%;" >
    <img type="image" src="img/copyCond.png" style="max-width:100%; max-height:100%;display: block; margin: auto;" />
  </a>
  <a href="controller/copyJob.php?id_info_job=<?= $job['id_info_job'] ?>&copyID=Yes" onClick="return confirm('Are you sure you want to copy this Job with Split & ID without Condition?');" class="btn btn-default btn-lg" style="padding : 0px; padding-left : 10px;padding-right:10px;height: 100%;" >
    <img type="image" src="img/copyID.png" style="max-width:100%; max-height:100%;display: block; margin: auto;" />
  </a>
  <a href="controller/copyJob.php?id_info_job=<?= $job['id_info_job'] ?>" onClick="return confirm('Are you sure you want to copy this Job with Split without ID & Condition?');" class="btn btn-default btn-lg" style="padding : 0px; padding-left : 10px;padding-right:10px;height: 100%;" >
    <img type="image" src="img/copySplit.png" style="max-width:100%; max-height:100%;display: block; margin: auto;" />
  </a>
</div>
