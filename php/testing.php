<?php
include "connection.php";
include "function.php";
include "querydata.php";

	$query = $db -> prepare("SELECT chat.msg, stud_bas.fname as fname, stud_bas.lname FROM chat_connect
						INNER JOIN stud_bas
						ON stud_bas.stud_id = chat_connect.sender_id
						INNER JOIN chat
						ON chat.chat_id = chat_connect.chat_id
						WHERE chat_connect.sender_id = :getid 
						AND seen_stat=0");
	$query -> bindParam (":getid", $getid);
	$query -> execute();
	while($row = $query -> fetch(PDO::FETCH_ASSOC)){
	$fname = $row['fname'];
?>
	<p><?php echo $fname;?></p>
<?php		
	}

?>
	