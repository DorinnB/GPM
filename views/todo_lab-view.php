<h3 style="color:white;">
  LAB Todo List
</h3>
<table class="table-condensed table-bordered" style="color:white; margin: auto;">
  <tr>
    <th>Icon</th>
    <th>Text</th>
    <th>Prio</th>
  </tr>
<?php
foreach ($todoLab as $key => $value) :
  ?>
  <tr>
  <td><img src="img/<?= $value['icone_file'] ?>" style="width: auto;max-height: 30px;background-color:white;"></td>
  <td><?= $value['texte_lab_forecast'] ?></td>
  <td><img src="img/medal_<?= $value['prio_lab_forecast'] ?>.png" style="width: auto;max-height: 20px;"></td>
</tr>
  <?php
endforeach
?>
</table>
