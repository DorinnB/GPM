<!-- Sidebar -->
<div id="sidebar-wrapper">
	<ul class="sidebar-nav" id="tools-nav">
		<li class="sidebar-brand">
			MENU
		</li>
		<li>
			<a href="index.php?page=gestionQualite">Quality Managment</a>
		</li>
		<li>
			<a href="index.php?page=listeFlagQualite&filtre='>'">Quality Flag to proceed</a>
		</li>
		<li>
			<a href="index.php?page=gestionPoste">Frame Management</a>
		</li>
		<li>
			<a href="index.php?page=ListeEssais&startFile=<?=	date("Y")	?>"">Test list</a>
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
