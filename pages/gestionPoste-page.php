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
		<div class="row" style="height:50%; overflow:auto;">
			<form id="formGestionPoste" action="controller/updatePoste.php" method="POST" onsubmit="return validateForm()" style="height:100%;">
				<div class="col-md-6" style="height:100%;">
					<?php
					include('views/gestionPosteLeft-view.php');
					?>
				</div>
				<div class="col-md-3" style="height:100%;">
					<img src="img/poste.jpg" style="max-width:100%; max-height:100%;padding:5px 0px;display: block; margin: auto;" usemap="#imgmap2017322155332">
					<map id="imgmap2017322155332" name="imgmap2017322155332">
						<area shape="rect" alt="Machine" title="Machine" coords="232,25,273,51" href="#" />
						<area shape="rect" alt="Poste" title="Poste" coords="221,54,288,77" href="#"  onclick="hideAll(); $('#posteMachine').css('display','block');"/>
						<area shape="rect" alt="Load Cell" title="Load Cell" coords="214,163,286,206" href="#" onclick="hideAll(); $('#loadCell').css('display','block');"/>
						<area shape="rect" alt="Grip" title="Grip" coords="213,341,291,417" href="#" />
						<area shape="rect" alt="Heating" title="Heating" coords="292,282,479,361" href="#" />
						<area shape="rect" alt="Extensometer" title="Extensometer" coords="170,300,262,330" href="#" />
						<area shape="rect" alt="Servovalve" title="Servovalve" coords="232,522,264,611" href="#" />
						<area shape="rect" alt="Filtre" title="Filtre" coords="184,572,232,737" href="#" />
						<area shape="rect" alt="Accus" title="Accus" coords="256,587,296,664" href="#" />
						<area shape="rect" alt="Manifold" title="Manifold" coords="198,490,296,615" href="#" />
						<area shape="rect" alt="Computer" title="Computer" coords="143,255,207,327" href="#" />
						<area shape="rect" alt="Displacement" title="Displacement" coords="228,400,269,490" href="#" onclick="hideAll(); $('#displacementCell').css('display','block');"/>
						<!-- Created by Online Image Map Editor (http://www.maschek.hu/imagemap/index) -->
					</map>
				</div>
				<div class="col-md-3" style="height:100%;">
					<div class="row" style="height:90%; overflow:auto;">

						<?php
						include('views/gestionPosteRight-view.php');
						?>
					</div>
					<div class="row" id="save" style="height:10%;">
						<input type="hidden" name="id_machine" value="<?=	$poste['id_machine']	?>">
						<input type="hidden" name="id_poste" value="<?=	$poste['id_poste']	?>">
						<input type="image" alt="Submit" src="img/save.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
					</div>

				</div>
			</form>
		</div>
		<div class="row" style="height:45%;">
			<?php
			include('views/gestionPosteHistory-view.php');
			?>
		</div>
	</div>
</div>

<script type="text/javascript" src="js/gestionPoste.js"></script>

<?php require('views/login-view.php');	?>
