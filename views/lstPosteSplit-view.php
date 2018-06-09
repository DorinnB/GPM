<option value="0">-</option>
<?php foreach ($lstPoste->getAllPosteSplit($_GET['id_Ep']) as $row): ?>
	<option value="<?= $row['id_poste'] ?>" <?=  ((isset($_COOKIE['id_machine']) and ($_COOKIE['id_machine']==$row['id_machine']))?"selected":"" )  ?>><?= $row['machine'] ?></option>
<?php endforeach ?>
