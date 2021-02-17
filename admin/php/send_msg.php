<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['fraid']) && isset($_POST['msg'])){
		$date = date("Y-m-d H:i:s");
		$msg = $_POST['msg'];
		$fraid = $_POST['fraid'];
		$media = 0;
		
		$msg_id = createUniqueId('msg_id','admin_message');
			$send_msg = $db -> prepare 
					("
					START TRANSACTION;
					INSERT INTO admin_message (msg_id,message,datetime)
					VALUES (:msg_id,:msg,:datetime);
					INSERT INTO admin_message_connect (msg_id,admin_id,receiver_id)
					VALUES (:msg_id,:aid,:fraid);
					COMMIT;
					");
			$send_msg -> execute(array(
					"msg_id" => $msg_id,
					"msg" => $msg,
					"datetime" => $date,
					"aid" => $aid,
					"fraid" => $fraid
					));		
	
			$update_seen = $db -> prepare ("UPDATE admin_message_connect SET seen = 1 WHERE admin_id = :fraid AND receiver_id = :aid");
			$update_seen -> bindParam (":fraid", $fraid);
			$update_seen -> bindParam (":aid", $aid);
			$update_seen -> execute();
	
	}
	else{
		die();
	}

?>