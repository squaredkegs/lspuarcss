<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	if(isset($_POST['rqid']) && isset($_POST['name'])){
		$rqid = $_POST['rqid'];
		$name = $_POST['name'];
		if($name=='revoke'){
		$query = $db -> prepare ("UPDATE request_thesis SET status='Revoked' WHERE request_id = :rqid");
		}
		else if($name=='undo'){
		$query = $db -> prepare ("UPDATE request_thesis SET status='Approved' WHERE request_id = :rqid");
		}
		$query -> bindParam (":rqid", $rqid);
		$query -> execute();
		
	}

?>