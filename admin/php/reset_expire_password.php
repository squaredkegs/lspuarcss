<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	if(isset($_POST['exid'])){
		$exid = $_POST['exid'];
		$query = $db -> prepare ("DELETE FROM admin_expire_passwords WHERE expire_id = :exid");
		$query -> bindParam (":exid", $exid);
		$query -> execute();
		echo $exid;
	}

?>