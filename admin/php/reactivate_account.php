<?php

	include_once ("connection.php");
	include_once ("adminfunction.php");
	include_once ("queryadmindata.php");
	if(isset($_POST['sid'])){
		$sid = $_POST['sid'];
		$query = $db -> prepare ("UPDATE stud_bas SET status = 'Registered' WHERE stud_id = :sid");
		$query -> bindParam (":sid", $sid);
		$query -> execute();
	}

?>