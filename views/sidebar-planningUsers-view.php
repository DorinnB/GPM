<!-- Sidebar -->
<?php

$now = time();
$june = strtotime("1st June");

if ($now > $june) {
  $getBegin=date("y-m-d", strtotime('+0 year', $june));
  $getEnd=date("y-m-d", strtotime('+1 year -1 day', $june));
}
else {
  $getBegin=date("y-m-d", strtotime('-1 year -1 day', $june));
  $getEnd=date("y-m-d", strtotime('0 year', $june));
}




$getBegin=(isset($_GET['begin']))?$_GET['begin']:$getBegin;
$getEnd=(isset($_GET['end']))?$_GET['end']:$getEnd;

 ?>
<div id="sidebar-wrapper">
	<ul class="sidebar-nav" id="tools-nav">
		<li class="sidebar-brand">
			MENU
		</li>
		<li>
			<a href="index.php?page=planningManagers">Planning Managers</a>
		</li>
		<li>
			<a href="controller/createPlanningUsers-controller.php?begin=<?= $getBegin ?>&end=<?= $getEnd ?>">ICS File</a>
		</li>
		<li>
			<a href="index.php?page=PlanningLab&nbDayPlanned=93&nbDayBefore=5&color=customer">Planning Lab</a>
		</li>
		<li>
			<a href="../ticket/">Issues Tracker</a>
		</li>
	</ul>
</div>


<!-- /#sidebar-wrapper -->
<!-- Menu Toggle Script -->
<script>
$("#menu-toggle").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});
</script>
