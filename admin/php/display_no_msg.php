<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	
				$msg_count = 0;
				$message_count = $db -> prepare ("SELECT DISTINCT(admin_id) FROM admin_message_connect WHERE receiver_id = :aid AND seen = 0");
				$message_count -> bindParam (":aid", $aid);
				$message_count -> execute();
				$msg_count = $message_count -> rowCount();
				if($msg_count>0){
					echo $msg_count;
				}	
?>