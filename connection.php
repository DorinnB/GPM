<?php
	session_start();

	//$db = mysqli_connect('localhost', 'root', '', 'Metcut');

	include_once('models/db.class.php'); // call db.class.php
	$db = new db(); // create a new object, class db()


	include_once 'models/user.class.php';
	$user = new USER($db);








function envoilog($table,$nom,$id,$update)	{

	$db = mysqli_connect('localhost', 'root', '', 'Metcut');

	$req_av = $db->query('SELECT * FROM '.$table.' WHERE '.$nom.'='.$id.';');


	$tbl_av = mysqli_fetch_array($req_av);

	$av = mysqli_real_escape_string($db, implode(";", $tbl_av));

	$instruction = mysqli_real_escape_string($db, $update);

	$a = $db->query($update);

	$req_ap = $db->query('SELECT * FROM '.$table.' WHERE '.$nom.'='.$id.';');
	$tbl_ap = mysqli_fetch_array($req_ap);
	$ap = mysqli_real_escape_string($db, implode(";", $tbl_ap));

	$modif = $db->query('INSERT INTO modifications (tbl, id_table, avant, instruction, apres) VALUES ("'.$table.'",'.$id.',"'.$av.'","'.$instruction.'","'.$ap.'");') or die (mysql_error());

}
?>
