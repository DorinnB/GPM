<h3 style="color:white;">
  Check before test List
</h3>
<table class="table-condensed table-bordered" style="color:white; margin: auto;">
  <tr>
    <th>Machine</th>
    <th>Split</th>
    <th>ID Spec</th>
    <th>Files</th>
    <th>Tech</th>
    <th>Chk</th>
  </tr>
<?php

foreach ($test as $key => $value) {
  ?>
  <tr onclick="document.location='index.php?page=split&id_tbljob=<?= $value['id_tbljob']  ?>'" style="cursor:help">
  <td><?= $value['machine'] ?></td>
  <td><?= $value['customer'].' '.$value['job'].' '. $value['split']  ?></td>
  <td><?= $value['prefixe'].' '.$value['nom_eprouvette']  ?></td>
  <td><?= $value['n_fichier'] ?></td>
  <td><?= $value['operateur'] ?></td>
  <td><?= $value['controleur'] ?></td>
</tr>
  <?php
}
?>
</table>
