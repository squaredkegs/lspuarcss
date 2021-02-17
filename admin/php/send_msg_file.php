<?php
	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_FILES['msg_file']['tmp_name'])){
		$date = date("Y-m-d H:i:s");
		$rawfilename  = $_FILES['msg_file']['name'];
		$file = $_FILES['msg_file']['tmp_name'];
		$filesize = $_FILES['msg_file']['size'];
		$fraid = $_POST['fraid'];
		$cut_name = strrpos ($rawfilename, ".");
		$file_name = substr($rawfilename, 0, $cut_name);
		$extension = substr($rawfilename, $cut_name + 1);
		$addstr = createUniqueId('filename','admin_media');
		$mid = createUniqueId('media_id','admin_media');
		$msg_id = createUniqueId('msg_id','admin_message');
		$newname = $file_name.$addstr.".".$extension;
		$msg_type = 'media';
		
		$filepath = "../files/";
			if(!is_dir($filepath)){
				mkdir($filepath, 0777, true);
				}
		if(move_uploaded_file($file,$filepath.$newname)){
				$insert_media = $db -> prepare ("
						START TRANSACTION;
						INSERT INTO admin_media (media_id,filename,filepath,filesize,filetype)
						VALUES (:mid,:filename,:filepath,:filesize,:type);
						INSERT INTO admin_media_connect (media_id,msg_id)
						VALUES (:mid,:msg_id);
						INSERT INTO admin_message_connect (msg_id,admin_id,receiver_id)
						VALUES (:msg_id,:aid,:fraid);
						INSERT INTO admin_message (msg_id,message,datetime,type)
						VALUES (:msg_id,:filename,:datetime,:msg_type);
						COMMIT;
						");
				$insert_media -> execute(array(
						"mid" => $mid,
						"filename" => $newname,
						"filepath" => $filepath,
						"filesize" => $filesize,
						"type" => $extension,
						"msg_id" => $msg_id,
						"aid" => $aid,
						"fraid" => $fraid,
						"datetime" => $date,
						"msg_type" => $msg_type,
						
						));		
		}
	}
?>
