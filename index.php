<?php		//connection sql
Require("connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
	<meta name="description" content="Gestion de Production">
	<meta name="author" content="Pierrick Gonnet">
	<link rel="icon" type="image/png" href="logo.ico" />
	<?php if (isset($_GET['page'])) : ?>
<title>GPM : <?= ucfirst($_GET['page']) ?></title>
	<?php else : ?>
	<title>Gestion Production Metcut</title>
<?php endif ?>
	<!-- Bootstrap Core CSS -->



	<!-- Custom CSS -->
	<link href="DataTables\Bootstrap-3.3.7/css/simple-sidebar.css" rel="stylesheet">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->



	<link rel="stylesheet" type="text/css" href="DataTables/datatables.css"/>
	<link href="DataTables\Bootstrap-3.3.7/css/tuto.css" rel="stylesheet">
	<script type="text/javascript" charset="utf8" src="DataTables/dataTables.js"></script>
	<script src="lib/Circular-Countdown-Clock-Plugin-For-jQuery-CircularCountDownJs/circular-countdown.js"></script>
	<script src="DataTables/Select2-4.0.1/js/select2.min.js"></script>
		<link href="DataTables/Select2-4.0.1/css/select2.min.css" rel="stylesheet">
	<script type="text/javascript" src="js/index.js"></script>

	<!--CSS TEMPORAIRE-->
	<link href="csstemporaire.css" rel="stylesheet">
</head>
<body>

	<!--Navbar-->
	<?php	include('controller/navbar-controller.php');	?>

	<div id="wrapper" class="toggled">

		<!-- Sidebar -->
		<?php

		if(file_exists('views/sidebar'.(isset($_GET['page'])?'-'.$_GET['page']:"-main").'-view.php'))
		include('views/sidebar'.(isset($_GET['page'])?'-'.$_GET['page']:"-main").'-view.php');
		else
		include('views/sidebar-labo-view.php');

		?>

		<!-- Page Content -->
		<?php
		// si votre site n'est pas à la racine du serveur, vous pouvez avoir besoin de dire OU se trouve la page index.php
		$_chemin = '/';

		// la page par defaut, si les valeurs fournies sont incorrect :
		$page_defaut = 'pages/lab';

		// on recupere la valeur passé dans l'url :
		if(isset($_GET["page"]))
		$page=$_GET["page"];
		else
		$page=$page_defaut;


		//Enlevons les caractères html
		$page=htmlentities($page, ENT_QUOTES);

		//Si on a des répertoires que l'on ne veut pas accéder, un les liste ici :
		$repProteger=array('include', 'libs', 'admin', 'var');
		$temp=@split('/',$page);
		if(in_array($temp[0],$repProteger)){ $page=$page_defaut; }

		//Si jamais qq tente de penetre dans le serveur en utilisant des ./ ou :/
		if(@eregi("(:/)|(./)",$page)){ $page=$page_defaut; }

		//pagesons si la page demandé existe bien en local
		if(file_exists('pages/'.$page.'-page.php'))
		include('pages/'.$page.'-page.php');
		elseif(file_exists($page_defaut.'-page.php'))
		include($page_defaut.'-page.php');
		else
		exit("Erreur : La page par defaut n'existe pas.");
		?>

		<?php	//include('pages/page-content-wrapper.php');	?>


	</div>
	<!-- /#wrapper -->
	<link href="lib/bootstrap-toggle-master/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="lib/bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>

	<?php //creation de User si le cookie est présent et correct
	if (isset($_COOKIE['id_user']))	{
		//$user->shortlogin($_COOKIE['id_user'],$_COOKIE['password'],'true');

		echo '
		<script>
		$("#login_username").val("'.$_COOKIE['id_user'].'");
		$("#login_password").val("'.$_COOKIE['password'].'");

		shortLoginScript();
		</script>';
	}
	?>
</body>
</html>
