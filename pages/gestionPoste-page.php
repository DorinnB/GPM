<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script type="text/javascript" src="lib/jQuery-rwdImageMaps-master/jquery.rwdImageMaps.min.js"></script>
<link href="lib/dropdown-with-search-using-jquery/select2.min.css" rel="stylesheet" />
<script src="lib/dropdown-with-search-using-jquery/select2.min.js"></script>

<link href="css/gestionPoste.css" rel="stylesheet">
<?php
include('controller/gestionPoste-controller.php');
?>
<!-- Page Content -->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">
		<div class="row" style="height:5%;">

			<div class="col-md-1 col-centered" style="height:100%;     float: none;margin: 0 auto;">
				<div class="btn-group" style="width:100%;">
					<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="splitStatut" style="float:none;">
						<?= $poste['machine'] ?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu statut">
						<?php foreach ($postes as $row): ?>
							<li onclick="location.href='index.php?page=gestionPoste&id_poste=<?= $row['id_poste'] ?>';"><a href="#"><?= $row['machine'] ?></a></li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>

		</div>

		<?php
		if ($poste['machine_other']==0) {
			include('views/gestionPosteFrame-view.php');
		}
		else {
			include('views/gestionPosteOther-view.php');
		}
		?>
	</div>
</div>

<script type="text/javascript" src="js/gestionPoste.js"></script>

<?php require('views/login-view.php');	?>
