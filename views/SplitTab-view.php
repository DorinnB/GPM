<link rel="stylesheet" href="css/splitTab.css">

<ul  class="nav nav-pills tab">
	<?php foreach ($Tabs as $row): ?>
		<li>
			<a href="index.php?page=split&id_tbljob=<?= $row['id_tbljob'] ?>" style="background-color : <?= $row['statut_color'] ?>" class="<?=	$row['class']	?>"><acronym title="<?= $row['test_type'] ?>"><?= $row['split'] ?>-<?= $row['test_type_abbr'] ?></acronym></a>
		</li>
	<?php endforeach ?>
</ul>
