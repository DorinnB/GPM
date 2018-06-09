<table id="table_planningJobFrame2" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:95%; white-space:nowrap;">
	<thead>
		<tr>
			<th rowspan=2>
				Frame
			</th>
			<?php foreach ($occurences as $k => $v) : ?>
				<th colspan=<?=	$v ?> style="text-align:center;">
					<?=	$k	?>
				</th>
			<?php endforeach ?>
		</tr>

		<tr>
			<?php foreach ($date as $key => $value) : ?>
				<th class="<?= isHoliday(strtotime($value))?'ferie':'' ?>">
					<?=	date('d', strtotime($value))	?>
				</th>
			<?php endforeach ?>

		</tr>
	</thead>


	<tbody>


		<?php foreach ($lstFrames as $frame) : ?>
			<tr>
				<td>
					<?=	$frame['machine'] ?>
				</td>
				<?php foreach ($date as $key => $value) : ?>
					<td class="popover-markup" data-id_tbljob="<?=	(isset($planningFrame[$frame['id_machine']][$value]))?$planningFrame[$frame['id_machine']][$value]:'0'	?>" data-id_machine="<?=	$frame['id_machine'] ?>" data-date="<?=	$value	?>" data-past="<?= ($value<date("Y-m-d"))?'1':'0'  ?>" data-color="<?=	(isset($planningJob[$frame['id_machine']][$value]))?substr($planningJob[$frame['id_machine']][$value], 1,1):''	?>" data-placement="auto">
						<?php
						$id_tbljob=(isset($planningJob[$frame['id_machine']][$value]))?$planningFrame[$frame['id_machine']][$value]:'';
						?>
						<?php foreach ($lstJobs as $key => $value) :	?>
							<?php if ($value['id_tbljob']==$id_tbljob) : ?>
								<div class="hide" style="background-color:inherit; color:inherit;" data-customer="<?= $value['customer']	?>">
									<a href="index.php?page=split&id_tbljob=<?= $value['id_tbljob'] ?>">
										<?= $value['customer'].'-'.$value['job'].'-'.$value['split']	?>
									</a>
								</div>
								<div class="content hide">

									<div class="form-group">
										<H3>Job : <?= $value['customer'].'-'.$value['job'].'-'.$value['split']	?></H3>
										<p>Test Type : <?= $value['test_type_abbr']	?></p>
										<p>Drawing : <?= $value['dessin']	?></p>
										<p>Temperature : <?= $value['temperature']	?></p>
										<p>Specimen Nb : <?= $value['nbep']	?></p>
										<p>DyT Cust : <?= $value['DyT_Cust']	?></p>
									</div>
								</div>
							<?php endif ?>
						<?php endforeach ?>
						<?php if ($id_tbljob==10) : ?>
							<div class="hide" style="background-color:inherit; color:inherit;">Cal Extenso</div>
						<?php elseif ($id_tbljob==11) : ?>
							<div class="hide" style="background-color:inherit; color:inherit;">Alignement</div>
						<?php elseif ($id_tbljob==12) : ?>
							<div class="hide" style="background-color:inherit; color:inherit;">Cal Temperature</div>
						<?php elseif ($id_tbljob==13) : ?>
							<div class="hide" style="background-color:inherit; color:inherit;">Dummy</div>
						<?php elseif ($id_tbljob==14) : ?>
							<div class="hide" style="background-color:inherit; color:inherit;">???</div>
						<?php elseif ($id_tbljob==15) : ?>
							<div class="hide" style="background-color:inherit; color:inherit;">MTS</div>
						<?php endif ?>
					</td>
				<?php endforeach ?>
			</tr>

		<?php endforeach ?>



	</tbody>
</table>
