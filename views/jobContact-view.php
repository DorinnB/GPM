
		<p class="commentaire3" style="font-weight: bold; font-size: large;">
			<?= $split['entreprise'] ?>
		</p>
		<p class="commentaire3" >
			<acronym title="Tel : <?=	$split['telephone']	?>"><?= $split['prenom'].' '.$split['nom'] ?></acronym>
			<acronym title="Send Email to Customer(s)">
				<a href="
				mailto: <?= $split['email']	?>
				?subject=Job : <?= $split['customer'].'&nbsp;-&nbsp;'.$split['job'] ?> - <?= $split['info_jobs_instruction'] ?>
				&cc=<?= $split['email2']	?>;<?= $split['email3']	?>;<?= $split['email4']	?>
				&body=
				">
				<span class="glyphicon glyphicon-envelope"></span>
				</a
				>
			</acronym>
		</p>

		<p class="commentaire3" ><acronym title="Tel : <?=	$split['telephone2']	?>"><?= $split['prenom2'].' '.$split['nom2'] ?></acronym></p>
		<p class="commentaire3" ><acronym title="Tel : <?=	$split['telephone3']	?>"><?= $split['prenom3'].' '.$split['nom3'] ?></acronym></p>
		<p class="commentaire3" ><acronym title="Tel : <?=	$split['telephone4']	?>"><?= $split['prenom4'].' '.$split['nom4'] ?></acronym></p>
