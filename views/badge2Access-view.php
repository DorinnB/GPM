<div class="col-md-8">
	<?php if ($clockState['in1']==""): ?>
		<button type="button" class="btn btn-success badgeAccess clockINOUT badgeAccessEnable" data-clockINOUT="in1">AM</button>
	<?php else: ?>
		<button type="button" class="btn btn-success badgeAccess disabled" data-clockINOUT="1">AM</button>
	<?php endif ?>

	<?php if ($clockState['unclocked2']!=0) :	?>
		<h4 class="badgeAccess blink_me" id="badgeAccess">
			Thank you for badging for this half day.
		</h4>
	<?php else : ?>
		<h4 class="badgeAccess" id="badgeAccess">
			Have a good day !
		</h4>
	<?php endif ?>

	<?php if ($clockState['in2']=="" AND $clockState['in1']!=""): ?>
		<button type="button" class="btn btn-success badgeAccess clockINOUT badgeAccessEnable" data-clockINOUT="in2">PM</button>
	<?php else: ?>
		<button type="button" class="btn btn-success badgeAccess disabled" data-clockINOUT="3">PM</button>
	<?php endif ?>


	<a href="index.php?page=badge2Users" class="btn btn-default btn-lg" style="width:50px; height:100%; padding:0px; border-radius:10px;">
		<img type="image" src="img/badge.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
	</a>
	
	<?php date_default_timezone_set('Europe/Paris'); $today = getdate();?>
	<label id="timer"><?= date('H:i:s')?></label>

</div>



<script>
var d = new Date(<?php echo $today['year'].",".$today['mon'].",".$today['mday'].",".$today['hours'].",".$today['minutes'].",".$today['seconds']; ?>);
console.log(d);
setInterval(function() {
	d.setSeconds(d.getSeconds() + 1);
	$('#timer').text((d.getHours() +':' + d.getMinutes() + ':' + d.getSeconds() ));
}, 1000);
</script>
