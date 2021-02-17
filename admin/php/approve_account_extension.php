<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	if(isset($_POST['rqst_date']) && isset($_POST['aid'])){
		$new_date = $_POST['rqst_date'];
		$aid = $_POST['aid'];
		$query = $db -> prepare ("UPDATE admin_tbl SET accnt_expire = :new_date, request_account_extension='0000-00-00 00:00:00', status='Active' WHERE admin_id = :aid");
		$query -> bindParam (":aid", $aid);
		$query -> bindParam (":new_date", $new_date);
		$query -> execute();
	}

?>