<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.php?page=labo">MRSAS</a>
		</div>
		<ul class="nav navbar-nav">
			<!--<li class="active"><a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Menu</a></li>-->
			<li><a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><acronym title="Tool-Box"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></acronym></a></li>
			<li><a href="controller/copyJob.php?id_info_job=13463" onClick="return confirm('Are you sure you want to create a new Job?');" style="padding : 0px; padding-left : 10px;padding-right:10px;" ><img src="img/newjob.png" class="img-responsive" alt="New Job" width="40" height="40"></a></li>
			<li><a href="#" style="padding : 0px; padding-left : 10px;padding-right:10px;"><img src="img/onenote-icone.png" class="img-responsive" alt="Onenote" width="40" height="40"></a></li>
			<li><a href="index.php?page=main"><acronym title="Main View">ALL</acronym></a></li>
			<li><a href="index.php?page=labo"><acronym title="LAB View">LAB</acronym></a></li>
			<li><a href="index.php?page=qualite"><acronym title="QUALITY View">Quality</acronym></a></li>
			<li><a href="index.php?page=administrative"><acronym title="Administrative View">Administrative</acronym></a></li>
			<li><a href="index.php?page=followupJob&filtreFollowup=100"><acronym title="Follow UP JOB View : Job">Follow UP JOB</acronym></a></li>
			<li><a href="index.php?page=followup"><acronym title="Follow UP LAB View : LAB">F.UP LAB</acronym></a></li>
			<li><a href="index.php?page=followupSubC&filtreFollowup=SubC"><acronym title="Follow UP SubC View : SubC">F.UP SubC</acronym></a></li>
			<li><a href="index.php?page=IT"><acronym title="IT Managment">IT</acronym></a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li>
				<form class="navbar-form" role="search">
					<div class="input-group">
						<input type="hidden" class="form-control"name="page" value="searchInfo">
						<input type="text" class="form-control" placeholder="Search Info" name="searchInfo" style="width:100px;">
						<div class="input-group-btn">
							<button class="glyphicon glyphicon-search btn btn-default" type="submit"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</form>
			</li>
			<!--<li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>-->

			<li><a href="#" id="login"><span class="glyphicon glyphicon-log-in"></span> <span id="user">Login</span></a><div id="iduser" style="display:none;"><?= (isset($_COOKIE['id_user'])?$_COOKIE['id_user'].'<script>iduser='.$_COOKIE['id_user'].';</script>':0)	?></div></li>
			<li><div class="timer" style="margin-right:50px;"></div></li>

		</ul>
	</div>
</nav>
