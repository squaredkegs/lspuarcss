<?php
include "connection.php";
include "function.php";

$cid = createRandomId('chat_id','chat');


if(isset($_POST['submit'])){
	$msg = $_POST['msg'];
	$query = $db -> prepare ("INSERT INTO chat (chat_id,msg) VALUES (:cid,:msg)");
	$query -> bindParam (":cid",$cid);
	$query -> bindParam (":msg", $msg);
	$query -> execute();
	if($query){
		echo "test";
	}
	else{
		echo "no";
	}
}
else{
	echo "seriously";
}	
	
?>