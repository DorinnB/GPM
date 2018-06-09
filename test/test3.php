<?php

// Rendre votre modèle accessible
include_once 'models/test-model.php';
// Création d'une instance
$oTest = new TestModel($db);
$test=$oTest->getTest();


?>
<h3>
  [WIP] Last test seen per frame
  <h4>only for LCF test <br/>
    Values refreshed every minutes if you refresh the page.
  </h4>
</h3>
<table class="table-condensed table-bordered">
  <tr>
    <th>Machine</th>
    <th>Split</th>
    <th>ID Spec</th>
    <th>Cycles</th>
    <th>Step</th>
  </tr>
<?php

foreach ($test as $key => $value) {
  ?>
  <tr>
      <td><?= $value['machine'] ?></td>
  <td><?= $value['customer'].' '.$value['job'].' '. $value['split']  ?></td>
  <td><?= $value['prefixe'].' '.$value['nom_eprouvette']  ?></td>
  <td><?= $value['Cycle_final'] ?></td>
  <td><?= $value['currentBlock']  ?></td>
</tr>
  <?php
}
?>
</table>
