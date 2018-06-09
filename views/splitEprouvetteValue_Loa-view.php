<link href="css/splitEprouvette.css" rel="stylesheet">


<!--
<div id="customForm">
<fieldset class="name">
<legend>Name</legend>
<editor-field name="c_temperature"></editor-field>
<editor-field name="c_frequence"></editor-field>
</fieldset>
<fieldset class="office">
<legend>Office</legend>
<editor-field name="c_cycle_STL"></editor-field>
<editor-field name="c_frequence_STL"></editor-field>
</fieldset>
<fieldset class="hr">
<legend>HR info</legend>
<editor-field name="c_type_1_val"></editor-field>
</fieldset>
</div>
-->


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
        <th><acronym title="Minimum Requirement">Cy Min</acronym></th>
        <th>Runout</th>
        <th><acronym title="Estimated Cycle">Cy Est.</acronym></th>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="Test Number">Test</acronym></th>
        <th><acronym title="Files Number">Files</acronym></th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>Machine</th>
        <th>Date</th>
        <th><acronym title="Waveform">Wave.</acronym></th>
        <th><acronym title="Final Cycles">Final</acronym></th>
        <th><acronym title="Rupture">Rupt</acronym></th>
        <th><acronym title="Fracture">Fract.</acronym></th>
        <th><acronym title="Test Duration (h)">Tps</acronym></th>
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
        <th><acronym title="Minimum Requirement">Cy Min</acronym></th>
        <th>Runout</th>
        <th><acronym title="Estimated Cycle">Cy Est.</acronym></th>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="Test Number">Test</acronym></th>
        <th><acronym title="Files Number">Files</acronym></th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>Machine</th>
        <th>Date</th>
        <th><acronym title="Waveform">Wave.</acronym></th>
        <th><acronym title="Final Cycles">Final</acronym></th>
        <th><acronym title="Rupture">Rupt</acronym></th>
        <th><acronym title="Fracture">Fract.</acronym></th>
        <th><acronym title="Test Duration (h)">Tps</acronym></th>
      </tr>
    </tfoot>

  </table>


</div>

<script type="text/javascript" src="js/splitEprouvetteValue_Loa.js"></script>
