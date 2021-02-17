<?php
include ("connection.php");
include ("querydata.php");
include ("function.php");

$cid = createRandomId('chat_id','chat');
$datetime = date("Y-m-d H:i:s");
if(isset($_POST['message'])){
	$msg = $_POST['message'];
	$frid = $_POST['frid']; 
	$query = $db -> prepare("
						START TRANSACTION;
						INSERT INTO chat (chat_id,msg,datetime)
						VALUES(:cid,:msg,:datetime);
						INSERT INTO chat_connect (chat_id,sender_id,receiver_id) VALUES(:cid2,:sender_id,:receiver_id);
						COMMIT;
						");
	
	$query -> execute(array(
					"cid" => $cid,
					"msg" => $msg,
					"datetime" => $datetime,
					"sender_id" => $getid,
					"receiver_id" => $frid,
					"cid2" => $cid
					));
		/*if($query){
			$join_query = $db -> prepare ("INSERT INTO chat_connect (chat_id,sender_id,receiver_id) VALUES(:cid,:sender_id,:receiver_id)");
			$join_query -> execute(array(
					"cid" => $cid,
					"sender_id" => $getid,
					"receiver_id" => $frid
					));
		}
		else{
			echo "<script>alert('It did not worked');</script>";
			
		}*/
}
else{
		echo "<script>alert('Website Error! Contact Administrator');</script>";
		
}

?>