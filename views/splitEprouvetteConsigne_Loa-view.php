<link href="css/splitEprouvette.css" rel="stylesheet">

<div class="col-md-12" style="height:100%">
  <table id="table_ep" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th>id</th>
        <th><acronym title="Prefixe">P</acronym></th>
        <th>ID</th>
        <th><acronym title="Temperature">T°</acronym></th>
        <th>Freq</th>
        <th><?= $split['cons1'] ?></th>
        <th><?= $split['cons2'] ?></th>
        <th><acronym title="StepCase Consigne">S+ C</acronym></th>
        <th><acronym title="Step">S+ S</acronym></th>
        <th><acronym title="Minimum Requirement">Cy Min</acronym></th>
        <th>Runout</th>
        <th><acronym title="Estimated Cycle">Cy Est.</acronym></th>
        <th><acronym title="Order Comment">Com.</acronym></th>

        <th><acronym title="Test Number">Test</acronym></th>
        <th><acronym title="Files Number">Files</acronym></th>
        <th><acronym title="Final Cycles">Final</acronym></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>id</th>
        <th><acronym title="Prefixe">P</acronym></th>
        <th>ID</th>
        <th><acronym title="Temperature">T°</acronym></th>
        <th>Freq</th>
        <th><?= $split['cons1'] ?></th>
        <th><?= $split['cons2'] ?></th>
        <th><acronym title="StepCase Consigne">S+ C</acronym></th>
        <th><acronym title="Step">S+ S</acronym></th>
        <th><acronym title="Minimum Requirement">Cy Min</acronym></th>
        <th>Runout</th>
        <th><acronym title="Estimated Cycle">Cy Est.</acronym></th>
        <th><acronym title="Order Comment">Com.</acronym></th>

        <th><acronym title="Test Number">Test</acronym></th>
        <th><acronym title="Files Number">Files</acronym></th>
        <th><acronym title="Final Cycles">Final</acronym></th>
      </tr>
    </tfoot>

  </table>


</div>

<script type="text/javascript" src="js/splitEprouvetteConsigne_Loa.js"></script>
