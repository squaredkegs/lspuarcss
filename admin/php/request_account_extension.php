<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	if(isset($_POST['date']) && isset($_POST['aid'])){
		$extend_date = $_POST['date'];
		$aid = $_POST['aid'];
		$query = $db -> prepare ("UPDATE admin_tbl SET status='Requesting Extension', request_account_extension = :extend_date WHERE admin_id = :aid");
		$query -> bindParam (":extend_date", $extend_date);
		$query -> bindParam (":aid", $aid);
		$query -> execute();
	}

?>