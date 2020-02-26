
<link href="css/lab.css" rel="stylesheet">
<script type="text/javascript" src="js/lab.js"></script>

<?php include('controller/lab-large-controller.php') ?>
<div class="container-fluid" id="lab" style="margin:0px 10px; overflow:auto;">
  <div class="row"> <!--  ligne du fond   -->
    <div class="col-md-5">
      <div class="col-md-6">
        <?php $n_poste=502; $nb=8; include("views/lab-poste-large-view.php"); ?>
      </div>
      <div class="col-md-6">
        <?php $n_poste=501; $nb=8; include("views/lab-poste-large-view.php"); ?>
      </div>
    </div>
    <div class="col-md-1">
    </div>
    <div class="col-md-5">
      <div class="col-md-6">
        <?php $n_poste=503; $nb=8; include("views/lab-poste-large-view.php"); ?>
      </div>
      <div class="col-md-6">
        <?php $n_poste=504; $nb=8; include("views/lab-poste-large-view.php"); ?>
      </div>
    </div>
  </div>

  <div class="row"> <!-- 4 lignes labo -->
    <div class="col-md-5"> <!-- ligne coté mur -->
      <div class="col-md-6" > <!-- ligne 1 -->
        <?php $n_poste=101; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=102; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=103; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=104; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=105; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=106; $nb=7; include("views/lab-poste-large-view.php"); ?>


      </div>
      <div class="col-md-6"> <!-- ligne 2 -->
        <?php $n_poste=201; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=202; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=203; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=204; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=205; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=206; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=207; $nb=8; include("views/lab-poste-large-view.php"); ?>
      </div>
    </div>
    <div class="col-md-2">

      <div class="col-md-12" style="margin-top: 20px;margin-bottom: 20px;">
        <div style="background-color:;border: 1px solid black;">View</div>
        <a style="display:block" href="index.php?page=lab&view=default"><div style="background-color:gray;border: 1px solid black;">Default</div></a>
        <a style="display:block" href="index.php?page=lab&view=test"><div style="background-color:darkgreen;border: 1px solid black;">Test</div></a>
        <a style="display:block" href="index.php?page=lab&view=comment"><div style="background-color:#536E94;border: 1px solid black;">Comment</div></a>
        <!--<a style="display:block" href="index.php?page=lab&view=calibration"><div style="background-color:sienna;border: 1px solid black;">Calibration</div></a>-->
      </div>


      <div class="col-md-12" style="margin-top: 100px;margin-bottom: 20px;">
        <div style="background-color:;border: 1px solid black;">Frame (<?= $planned['nb'] ?> Planned)</div>
        <?= (isset(array_count_values($runStop)['RUN'])?'<div style="background-color:darkgreen;border: 1px solid black;"> Running : '.array_count_values($runStop)['RUN'].' ('.round(array_count_values($runStop)['RUN']/count($runStop)*100,1).'%)</div>':'') ?>
        <?= (isset(array_count_values($runStop)['WIP'])?'<div style="background-color:Sienna;border: 1px solid black;"> WIP : '.array_count_values($runStop)['WIP'].'</div>':'') ?>
      </div>
      <div class="col-md-12">
        <div style="background-color:;border: 1px solid black;">Split</div>
        <?php foreach ($splitToDo as $row): ?>
          <div style="background-color:<?=  $row['statut_color']  ?>; <?= ($row['statut_color']=='Gold')?'color:black; ':'' ?>border: 1px solid black;"><?= $row['statut'].' '.$row['nb'] ?></div>
        <?php endforeach  ?>
      </div>

    </div>
    <div class="col-md-5"> <!-- ligne cote fenetre -->
      <div class="col-md-6"> <!-- ligne 3 -->
        <?php $n_poste=301; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=302; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=303; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=304; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=305; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=306; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=307; $nb=8; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=308; $nb=8; include("views/lab-poste-large-view.php"); ?>
      </div>
      <div class="col-md-6"> <!-- ligne 4 -->
        <?php $n_poste=401; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=402; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=403; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=404; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=405; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=406; $nb=7; include("views/lab-poste-large-view.php"); ?>
        <?php $n_poste=407; $nb=7; include("views/lab-poste-large-view.php"); ?>
      </div>
    </div>
  </div>

</div>




</div>

<?php
require('views/login-view.php');
?>





<script type="text/javascript" src="js/lab-poste-large.js"></script>




<div class='icone-menu custom-menu'>
</div>
<div class='priorite-menu custom-menu'>
</div>

<style>
.custom-menu {
  display: none;
  z-index: 1000;
  position: absolute;
  overflow: hidden;
  border: 1px solid #CCC;
  white-space: nowrap;
  font-family: sans-serif;
  background: #FFF;
  color: #333;
  border-radius: 5px;
}

.custom-menu li {
  cursor: pointer;
}

.custom-menu li:hover {
  background-color: #DEF;
}
</style>
