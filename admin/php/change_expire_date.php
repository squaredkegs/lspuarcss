<?php

	include_once ("connection.php");
	include_once ("adminfunction.php");
	include_once ("queryadmindata.php");
	$today = date ("Y-m-d");
	if(isset($_POST['new_expire_date'])){
		$new_date = $_POST['new_expire_date'];
		$query = $db -> prepare ("UPDATE admin_expire_passwords SET expire_date = :new_expire, change_date = :today WHERE campus = :campus AND department = :department");
		$query -> bindParam (":new_expire", $new_date);
		$query -> bindParam (":today", $today);
		$query -> bindParam (":campus", $rcampus);
		$query -> bindParam (":department", $rdepartment);
		$query -> execute();
	}
	
	else{
		die();
	}
?>