<?php
	
	include_once ("connection.php");
	include_once ("adminfunction.php");
	include_once ("queryadmindata.php");
	$datetime = date ("Y-m-d H:i:s");
		$msg_stat = $_POST['stat'];
		$title = $_POST['title'];
		$email = $_POST['email'];
		$message = $_POST['message'];
		$get_adminid = $db -> prepare ("SELECT admin_id FROM admin_tbl WHERE email=:email");
		$get_adminid -> bindParam (":email", $email);
		$get_adminid -> execute();
		$emailrowcount = $get_adminid -> rowCount();
		$result = $get_adminid -> fetch();
		$adminid = $result['admin_id'];
		$mid = createUniqueId ('mail_id','admin_message');
		if($emailrowcount==1){
			if($msg_stat=="send_msg"){
			$status = "Send";	
			}
			else if($msg_stat=="draft_msg"){
			$status = "Draft";	
			}
			$query = $db -> prepare ("
									START TRANSACTION;
									INSERT INTO admin_message (mail_id,message,media,status,title,datetime)
									VALUES (:mid,:msg,:media,:status,:title,:datetime);
									INSERT INTO admin_message_connect (mail_id,admin_id,receiver_id) VALUES(:mid2,:aid,:rid);
									COMMIT;
									");
			$query -> execute(array(
								"mid" => $mid,
								"msg" => $message,
								"media" => 0,
								"status" => $status,
								"title" => $title,
								"datetime" => $datetime,
								"mid2" => $mid,
								"aid" => $aid,
								"rid" => $adminid
								));												
			if($query){
				echo 	"
						<script>
						alert('Success');
						window.location.href='../compose.php';
						</script>
						";
			}	
			else{
				echo 	"
						<script>
						alert('Error!');
						window.location.href='../compose.php';
						</script>
						";
			}
	}
	else{
				echo 	"
						<script>
						alert('Email don't belong to anybody!');
						window.location.href='../compose.php';
						</script>
						";
	}
?>
