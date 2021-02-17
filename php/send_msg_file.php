<?php

	include_once ("connection.php");
	include_once ("function.php");
	include_once ("querydata.php");	
	
	if(isset($_FILES['userfile']['tmp_name']) && isset($_POST['frid'])){
		
		$date = date("Y-m-d H:i:s");
		if($_FILES['userfile']['error'] != UPLOAD_ERR_OK){
				die("Upload Failed with error code". $_FILES['userfile']['error']);
		}
		$frid = $_POST['frid'];
		$file = $_FILES['userfile']['tmp_name'];
		$name = $_FILES['userfile']['name'];
		$cut_here = strrpos($name,".");
		$chid = createRandomId('chat_id','chat');
		$addstr = createRandomId('chat_id','chat');
		$new_name = substr($name, 0, $cut_here);
		$temp = explode (".", $_FILES['userfile']['name']);
		$type = end ($temp);
		$new_file_name = $new_name .$addstr."." . end ($temp);	
		$path = "../files/chat_files/";
		$msg_type = "File";
		if(!is_dir($path)){
					mkdir("../files/chat_files", 0777, true);
		}
		
			if(move_uploaded_file($file,$path.$new_file_name)){
				$send_to_chat = $db -> prepare ("
				START TRANSACTION;
				INSERT INTO chat (chat_id,datetime,msg,type) 
				VALUES
				(:chid,:date,:msg,:type);
				INSERT INTO chat_connect (chat_id,sender_id,receiver_id)
				VALUES 
				(:chid,:sid,:frid);
				COMMIT;
				
				");
				$send_to_chat -> execute(array(
						"chid" => $chid,
						"date" => $date,
						"msg" => $new_file_name,
						"type" => $msg_type,
						"sid" => $getid,
						"frid" => $frid
						)); 		
			}
			else{
				die();
			}
		
	}
	else{
	echo "Error!";
	die();	
	}
?>