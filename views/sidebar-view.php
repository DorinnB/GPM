<!-- Sidebar -->
<div id="sidebar-wrapper">
	<ul class="sidebar-nav" id="tools-nav">
		<li class="sidebar-brand">
			MENU
		</li>
				<li>
			<a href="index.php?page=gestionPoste">Frame Management</a>
		</li>
		<li>
			<a href="index.php?page=ListeEssais">Test list</a>
		</li>
		<li>
			<a href="../ticket/index.php?project=1">Issues Tracker</a>
		</li>
	</ul>

	<?php
	//include('pages/tbljobs.php');
	//include('controller/tblJobs-controller.php');
	?>



</div>
<!-- /#sidebar-wrapper -->
<!-- Menu Toggle Script -->
<script>
$("#tools-nav").hide();

$("#menu-toggle").click(function(e) {
	e.preventDefault();
	if (($("#tools-nav").is(":visible")) && ($("#wrapper:not(.toggled)"))) {
		$("#wrapper").toggleClass("toggled");
	}
	else if ($("#wrapper").hasClass("toggled")) {
		$("#wrapper").removeClass("toggled");
	}
	$("#tools-nav").show();
	$("#tbljob-nav").hide();
});

$("#tbljob-toggle").click(function(e) {
	e.preventDefault();
	if (($("#tbljob-nav").is(":visible")) && ($("#wrapper:not(.toggled)"))) {
		$("#wrapper").toggleClass("toggled");
	}
	else if ($("#wrapper").hasClass("toggled")) {
		$("#wrapper").removeClass("toggled");
	}
	$("#tools-nav").hide();
	$("#tbljob-nav").show();
});
</script>
