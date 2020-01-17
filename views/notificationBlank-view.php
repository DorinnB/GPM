<link rel="stylesheet" href="css/notification.css">

<div id="notification" class="modal fade" role="dialog">
</div>
	<?php date_default_timezone_set('Europe/Paris'); $today = getdate();?>
<script>
var d = new Date(<?php echo $today['year'].",".$today['mon'].",".$today['mday'].",".$today['hours'].",".$today['minutes'].",".$today['seconds']; ?>);
setInterval(function() {
	d.setSeconds(d.getSeconds() + 1);
	$('#timer').text((d.getHours() +':' + d.getMinutes() + ':' + d.getSeconds() ));
}, 1000);
</script>
