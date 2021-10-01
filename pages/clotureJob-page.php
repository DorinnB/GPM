<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">
<script type="text/javascript" src="js/labo.js"></script>

<!-- Page Content -->
<div id="split-nav" style="height:100%">
	<div class="container-fluid">
		<div class="col-md-12 unique" id="pageunique" style="height:100%;">
			<?php
			include('controller/clotureJob-controller.php');
			?>
		</div>
	</div>
</div>
<!-- /#page-content-wrapper -->



<!-- Modal -->
<div id="ArchivingModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Archiving</h3>
			</div>
			<div class="modal-body">
				<div class="data">
					<div class="row bandeau" style="border-bottom:2px solid white;">
						<div class="col-md-2 titre">
							Split Status
						</div>
						<div class="col-md-2 titre">
							Unchecked Specimen
						</div>
						<div class="col-md-2 titre">
							Missing Report Sent
						</div>
						<div class="col-md-2 titre">
							Missing Shippement
						</div>
						<div class="col-md-2 titre">
							Status
						</div>
						<div class="col-md-2 titre">
							Missing File
						</div>
					</div>
					<div class="row bandeauVal">
						<div class="col-md-2 valeur" id="splitStatus">
						</div>
						<div class="col-md-2 valeur" id="Unchecked">
						</div>


						<div class="col-md-2 valeur" id="MissingReport">
						</div>
						<div class="col-md-2 valeur" id="MissingShipped">
						</div>
						<div class="col-md-2 valeur">
							<div class="col-md-12 titre">
								Invoice Status
							</div>
							<div class="col-md-12 valeur" id="MissingInvoice">
							</div>
							<div class="col-md-12 titre">
								OneNote
							</div>
							<div class="col-md-12 valeur" id="OneNote">
							</div>
						</div>
						<div class="col-md-2 valeur">
							<div class="col-md-12 titre">
								Missing Trans
							</div>
							<div class="col-md-12 valeur" id="MissingTrans" style="background-color:darkred; margin-bottom:5px;">
							</div>
							<div class="col-md-12 titre">
								Missing Job
							</div>
							<div class="col-md-12 valeur" id="MissingTestFile" style="margin-bottom:5px;">
							</div>
						</div>
					</div>
				</div>
				<div class="footer">
					<div class="col-md-3" style="height:100%;">
						<abbr title="Remove the job from all the Administrative/Manager and UBR">
							<a href="#" id="closeJob" class="btn btn-default btn-lg " style="width:100%; height:100%; padding:0px; border-radius:10px;">
								<p style="font-size:small;height:100%;">Close
									<img type="image" src="img/check.png" style="max-width: 50%; max-height: 80%; padding: 5px 0px;display: block; margin: auto;">
								</p>
							</a>
						</abbr>
					</div>
					<div class="col-md-3" style="height:100%;">
						<abbr title="Copy test data on Trans to Job">
						<a href="#" id="copyTestFile" class="btn btn-default btn-lg " style="width:100%; height:100%; padding:0px; border-radius:10px;">
							<p style="font-size:small;height:100%;">Copy Trans
								<img type="image" src="img/copy.png" style="max-width: 50%; max-height: 80%; padding: 5px 0px;display: block; margin: auto;">
							</p>
						</a>
					</abbr>
					</div>
					<div class="col-md-3" style="height:100%;">
						<abbr title="Zip the folder on Job and delete the folder">
						<a href="#" id="zipJob" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
							<p style="font-size:small;height:100%;">Create Zip
								<img type="image" src="img/zip.png" style="max-width: 50%; max-height: 80%; padding: 5px 0px;display: block; margin: auto;">
							</p>
						</a>
					</abbr>
					</div>
					<div class="col-md-3" style="height:100%;">
						<abbr title="Flag the Job as Archived">
						<a href="#" id="archiveJob" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
							<p style="font-size:small;height:100%;">Archive ==> WIP, need archive ID
								<img type="image" src="img/archive.png" style="max-width: 50%; max-height: 80%; padding: 5px 0px;display: block; margin: auto;">
							</p>
						</a>
					</abbr>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<?php
require('views/login-view.php');
?>
