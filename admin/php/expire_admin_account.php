<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	if(isset($_POST['aid'])){
		$aid = $_POST['aid'];
		$query = $db -> prepare ("UPDATE admin_tbl SET status='Expired' WHERE admin_id = :aid");
		$query -> bindParam (":aid", $aid);
		$query -> execute();
		
	echo $aid;
	}

?>