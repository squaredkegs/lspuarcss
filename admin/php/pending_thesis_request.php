<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['approve_thesis_request'])){
		$rqid = $_POST['rqid'];
		//$date = date("Y-m-d H:i:s", strtotime("+3 Months"));
		$date = date("Y-m-d H:i:s");
		$query = $db -> prepare ("UPDATE request_thesis SET status='Approved', request_approve = :date WHERE request_id=:rqid");
		$query -> bindParam (":rqid", $rqid);
		$query -> bindParam (":date", $date);
		$query -> execute();
	}
	else if(isset($_POST['reject_thesis_request'])){
		$rqid = $_POST['rqid'];
		$query = $db -> prepare ("UPDATE request_thesis SET status='Rejected' WHERE request_id=:rqid");
		$query -> bindParam (":rqid", $rqid);
		$query -> execute();
		
	}
		if($query){
			echo 
				"
				<script>
				alert('Action Completed');
				window.location.href='../pending_thesis.php';
				</script>
				";
		}
?>