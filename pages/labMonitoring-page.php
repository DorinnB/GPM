
<link href="css/lab.css" rel="stylesheet">
<script type="text/javascript" src="js/lab.js"></script>

<?php include('controller/labMonitoring-controller.php') ?>





<div id="myCarousel" class="container-fluid carousel" style="margin:0px 10px; height:100%;">


  <!-- Wrapper for slides -->
  <div class="carousel-inner" style="height:100%;">

    <?php foreach ($screen as $key => $value) : ?>
      <div class="item <?= ($machine==$key)?'active':''?>" style="height:100%;">
        <img src="<?= $value ?>" alt="<?= $key ?>" class="img-responsive center-block" style="max-height:100%;">
        <div class="carousel-caption">
          <h3 style="margin-bottom:0px;"><?= $key ?></h3>
        </div>
      </div>
    <?php endforeach ?>


    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <style>
  .carousel-caption {
    top: auto;
    bottom: 0;
    padding-bottom:0px;
  }
</style>



<?php
require('views/login-view.php');
?>
