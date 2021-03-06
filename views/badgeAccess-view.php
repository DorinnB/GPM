<div class="col-md-9">
	<?php if ($clockState['in1']==""): ?>
		<button type="button" class="btn btn-success badgeAccess clockINOUT badgeAccessEnable" data-clockINOUT="in1">IN</button>
		<button type="button" class="btn btn-primary badgeAccess disabled" data-clockINOUT="2">OUT</button>
		<img type="image" src="img/pause.png" style="max-width:30px; max-height:100%; padding:5px 0px;display: inline; margin: auto;">
	<?php elseif ($clockState['out1']==""):	?>
		<button type="button" class="btn btn-success badgeAccess disabled" data-clockINOUT="1">IN</button>
		<button type="button" class="btn btn-primary badgeAccess clockINOUT badgeAccessEnable" data-clockINOUT="out1">OUT</button>
		<img type="image" src="img/continue.png" style="max-width:30px; max-height:100%; padding:5px 0px;display: inline; margin: auto;">
	<?php else: ?>
		<button type="button" class="btn btn-success badgeAccess disabled" data-clockINOUT="1">IN</button>
		<button type="button" class="btn btn-primary badgeAccess disabled" data-clockINOUT="2">OUT</button>
	<?php endif ?>

	<h4 class="badgeAccess <?=($clockState['unclocked']==0)?"blink_me":""	?>" id="badgeAccess">
		<acronym title="<?= $endOfWorkEstimated ?>"><?= $clockCount['clockCount'] ?></acronym>
	</h4>

	<?php if ($clockState['in2']=="" AND $clockState['out1']!=""): ?>
		<img type="image" src="img/pause.png" style="max-width:30px; max-height:100%; padding:5px 0px;display: inline; margin: auto;">
		<button type="button" class="btn btn-success badgeAccess clockINOUT badgeAccessEnable" data-clockINOUT="in2">IN</button>
		<button type="button" class="btn btn-primary badgeAccess disabled" data-clockINOUT="4">OUT</button>
	<?php elseif ($clockState['out2']=="" AND $clockState['out1']!=""):	?>
		<img type="image" src="img/continue.png" style="max-width:30px; max-height:100%; padding:5px 0px;display: inline; margin: auto;">
		<button type="button" class="btn btn-success badgeAccess disabled" data-clockINOUT="3">IN</button>
		<button type="button" class="btn btn-primary badgeAccess clockINOUT badgeAccessEnable" data-clockINOUT="out2">OUT</button>
	<?php else: ?>
		<button type="button" class="btn btn-success badgeAccess disabled" data-clockINOUT="3">IN</button>
		<button type="button" class="btn btn-primary badgeAccess disabled" data-clockINOUT="4">OUT</button>
	<?php endif ?>


	<a href="index.php?page=badgeUsers" class="btn btn-default btn-lg" style="width:50px; height:100%; padding:0px; border-radius:10px;">
		<img type="image" src="img/badge.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
	</a>

	<label id="timer"><?= date('H:i:s')?></label>
</div>
