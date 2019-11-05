

<link href="css/lab.css" rel="stylesheet">
<script type="text/javascript" src="js/lab.js"></script>

<?php include('controller/lab-large-controller.php') ?>
<div class="container-fluid" id="lab" style="margin:0px 10px; overflow:auto;">



  <div class="row">
    <div class="col-md-6">
      <div class="row">

        <div class="col-md-4">



          <div class="col-md-12 machine" style="margin:5px 0px;">
            <div class="col-md-6">
              <div style="background-color:;border: 1px solid black;">Frame (<?= $planned['nb'] ?> Planned)</div>
              <?= (isset(array_count_values($runStop)['RUN'])?'<div style="background-color:darkgreen;border: 1px solid black;"> Running : '.array_count_values($runStop)['RUN'].'</div>':'') ?>
              <?= (isset(array_count_values($runStop)['WIP'])?'<div style="background-color:Sienna;border: 1px solid black;"> WIP : '.array_count_values($runStop)['WIP'].'</div>':'') ?>
            </div>
            <div class="col-md-6">
              <div style="background-color:;border: 1px solid black;">Split</div>
              <?php foreach ($splitToDo as $row): ?>
                <div style="background-color:<?=  $row['statut_color']  ?>;border: 1px solid black;"><?= $row['statut'].' '.$row['nb'] ?></div>
              <?php endforeach  ?>
            </div>
          </div>





        </div>
        <div class="col-md-2"><?php $n_poste=106; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=105; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=104; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=103; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=102; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=101; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=502; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=207; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=206; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=205; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=204; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=203; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=202; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=201; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=501; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-1">
          a
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=307; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=306; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=305; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=304; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=303; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=302; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=301; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=503; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=407; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=406; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=405; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=404; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=403; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=402; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=401; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2"><?php $n_poste=503; include("views/lab-poste-large-view.php"); ?>
        </div>
        <div class="col-md-1">
        </div>
      </div>
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
