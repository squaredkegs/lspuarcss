<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	if(isset($_POST['aid']) && isset($_POST['new_date'])){
		$aid = $_POST['aid'];
		$new_date = $_POST['new_date'];
		$query = $db -> prepare ("UPDATE admin_tbl SET status='Active', request_account_extension='0000-00-00 00:00:00', accnt_expire = :new_date WHERE admin_id = :aid");
		$query -> bindParam (":new_date", $new_date);
		$query -> bindParam (":aid", $aid);
		$query -> execute();
	
	}

?>