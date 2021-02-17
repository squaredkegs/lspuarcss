<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	if(isset($_POST['frid'])){
		$frid = $_POST['frid'];
		$query = $db -> prepare ("
					SELECT chat.seen_stat as unseen_msg FROM chat_connect
					LEFT JOIN chat 
					ON chat.chat_id = chat_connect.chat_id
					WHERE chat_connect.sender_id = :frid
					AND receiver_id = :getid AND chat.seen_stat = 0
					");
		$query -> bindParam (":frid", $frid);
		$query -> bindParam (":getid", $getid);
		$query -> execute();
		$res = $query -> fetch();
		$numrow = $query -> rowCount();
		if($numrow>0){
			echo $numrow;
		}
		else{
		
		}
	}
?>