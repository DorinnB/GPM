<div style="float : left; font-size : 25px; vertical-align : middle; height : 100%; color : red;">
  <span>JOB : </span>
  <span><?= $job['job'] ?></span>
</div>
<div style="float : right; font-size : 25px; vertical-align : middle; height : 100%;">
  <a href="controller/copyJob.php?id_info_job=<?= $job['id_info_job'] ?>" onClick="return confirm('Are you sure you want to copy this Job?');" style="padding : 0px; padding-left : 10px;padding-right:10px;" >
    <button style="float: right;">COPY JOB</button>
  </a>
</div>
