<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	
	if(isset($_GET['aid'])){
		$fraid = $_GET['aid'];
		$query = $db -> prepare("
		SELECT receiver_id,admin_id, admin_message.message as msg, admin_message.datetime as datetime, admin_message.type as type,
		CASE WHEN admin_id = :sid AND receiver_id = :fraid THEN admin_message.message END as user_message, 
		CASE WHEN admin_id = :fraid AND receiver_id = :sid THEN admin_message.message END AS receiver_message 
		FROM admin_message_connect 
		INNER JOIN admin_message
		ON admin_message.msg_id = admin_message_connect.msg_id 
		ORDER BY admin_message.datetime ASC
		");
	$query -> bindParam (":sid", $aid);
	$query -> bindParam (":fraid", $fraid);
	$query -> execute();
		while($row = $query -> fetch(PDO::FETCH_ASSOC)){
			$msg = $row['msg'];
			$my_msg = $row['user_message'];
			$admin_msg = $row['receiver_message'];
			$type = $row['type'];
		?>
		
			<div id='chat'>
				<span style='color:blue'>
					<?php
						if($my_msg != null or $my_msg != ""){
							if($type=='text'){
							echo $my_msg;
							}
							else{
							?>
							<a href='files/<?php echo $my_msg;?>' style='text-decoration:underline;' download ><?php echo $my_msg;?></a>
							<?php
							}
						}
						
					?>
				</span>
				<span style='color:red'>
					<?php
						if($admin_msg != null or $admin_msg != ""){
							echo $admin_msg;
						}
					?>
				</span>
			</div>
		
		<?php
		}
	}

?>