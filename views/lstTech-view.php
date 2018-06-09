<option value="0">-</option>
<?php foreach ($lstTech->getAllTech() as $row): ?>
	<option value="<?= $row['id_technicien'] ?>"><?= $row['technicien'] ?></option>
<?php endforeach ?>
