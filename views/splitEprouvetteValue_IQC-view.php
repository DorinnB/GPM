<link href="css/splitEprouvette.css" rel="stylesheet">


<div class="col-md-12" style="height:100%">
  <table id="table_ep" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th>id</th>
        <th><acronym title="Prefixe">P</acronym></th>
        <th>ID</th>
        <?php  foreach ($dimDenomination as $dimTexte): ?>
          <th><?= $dimTexte  ?></th>
        <?php  endforeach  ?>
        <?php for($i=count($dimDenomination);$i < 3;$i++): ?>
          <th><?= 'NA'  ?></th>
        <?php  endfor  ?>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="suite">marquage</acronym></th>
        <th><acronym title="suite">surface</acronym></th>
        <th><acronym title="suite">grenaillage</acronym></th>
        <th><acronym title="suite">revetement</acronym></th>
        <th><acronym title="suite">protection</acronym></th>
        <th><acronym title="suite">autre</acronym></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>id</th>
        <th><acronym title="Prefixe">P</acronym></th>
        <th>ID</th>
        <?php  foreach ($dimDenomination as $dimTexte): ?>
          <th><?= $dimTexte  ?></th>
        <?php  endforeach  ?>
        <?php for($i=count($dimDenomination);$i < 3;$i++): ?>
          <th><?= 'NA'  ?></th>
        <?php  endfor  ?>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="suite">marquage</acronym></th>
        <th><acronym title="suite">surface</acronym></th>
        <th><acronym title="suite">grenaillage</acronym></th>
        <th><acronym title="suite">revetement</acronym></th>
        <th><acronym title="suite">protection</acronym></th>
        <th><acronym title="suite">autre</acronym></th>
      </tr>
    </tfoot>

  </table>


</div>

<script type="text/javascript" src="js/splitEprouvetteValue_IQC.js"></script>
