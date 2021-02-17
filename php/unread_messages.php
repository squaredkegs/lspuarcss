<?php
	include_once "connection.php";
	include_once "querydata.php";
	$frid = $_POST['frid'];
	$datetime = date("Y-m-d H:i:s");
	$query = $db -> prepare("UPDATE chat 
							JOIN chat_connect 
							ON chat_connect.chat_id = chat.chat_id
							SET seen_stat = 1, date_seen = :datetime
							WHERE chat_connect.receiver_id = :getid AND chat_connect.sender_id = :frid AND seen_stat=0");
	$query -> execute(array(
				"datetime" => $datetime,
				"getid" => $getid,
				"frid" => $frid
				));
	$rowcount = $query -> rowCount();
	echo $rowcount;
	
?>