<table id="table_planningJobFrame" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:95%; white-space:nowrap;">
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
				<th class="<?= ($value==date("Y-m-d"))?'today':''  ?> <?= isHoliday(strtotime($value))?'ferie':'' ?>">
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
					<td class="selectable <?= ($value==date("Y-m-d"))?'today':''  ?>" data-id_tbljob="<?=	(isset($planningFrame[$frame['id_machine']][$value]))?$planningFrame[$frame['id_machine']][$value]:'0'	?>" data-id_machine="<?=	$frame['id_machine'] ?>" data-date="<?=	$value	?>" data-past="<?= ($value<date("Y-m-d"))?'1':'0'  ?>" data-color="<?=	(isset($planningJob[$frame['id_machine']][$value]))?substr($planningJob[$frame['id_machine']][$value], 1,1):''	?>"
						<?=	(isset($planningFrameJob[$frame['id_machine']][$value]))?'data-customer="'.$planningFrameJob[$frame['id_machine']][$value].'"':''	?>>
						<?=	(isset($planningJob[$frame['id_machine']][$value]))?$planningJob[$frame['id_machine']][$value]:''	?>
					</td>
				<?php endforeach ?>
			</tr>

		<?php endforeach ?>



	</tbody>
</table>
