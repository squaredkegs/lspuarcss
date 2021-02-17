<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
			
			if(isset($_POST['fraid'])){
			$fraid = $_POST['fraid'];
			$update_seen = $db -> prepare ("UPDATE admin_message_connect SET seen = 1 WHERE admin_id = :fraid AND receiver_id = :aid");
			$update_seen -> bindParam (":fraid", $fraid);
			$update_seen -> bindParam (":aid", $aid);
			$update_seen -> execute();
			}
?>