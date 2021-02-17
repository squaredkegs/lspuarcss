<?php
	
	
	include_once ("connection.php");
	include_once ("querydata.php");
	$query = $db -> prepare ("SELECT sender_id FROM chat_connect
							INNER JOIN chat
							ON chat.chat_id = chat_connect.chat_id
							INNER JOIN stud_bas
							ON stud_bas.stud_id = chat_connect.receiver_id
							WHERE chat.seen_stat = 0 AND  chat_connect.receiver_id = :getid
                            GROUP BY sender_id");
	$query -> bindParam (":getid", $getid);
	$query -> execute();
	$new_messages = $query -> rowCount();
	?>
	<link href="css/navbar.css" rel="stylesheet">


<body>

	<?php
	if($new_messages>0){
	?>
	<span class="notification-number"><?php echo $new_messages;?></span>
	<?php
	}	
	?>
</body>