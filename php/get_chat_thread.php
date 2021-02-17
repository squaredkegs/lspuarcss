	<head>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<link href="css/message.css" rel="stylesheet">
	</head>
	<body>
<?php
include "connection.php";
include "querydata.php";
	$frid = $_GET['frid'];
	$query = $db -> prepare("SELECT receiver_id,sender_id, type as type, chat.msg, chat.datetime, CASE WHEN sender_id = :sid AND receiver_id = :frid THEN chat.msg END as user_session, CASE WHEN sender_id = :frid AND receiver_id = :sid THEN chat.msg END AS message_receiver FROM chat_connect INNER JOIN chat ON chat_connect.chat_id = chat.chat_id ORDER BY chat.datetime ASC
	");
	$query -> bindParam (":sid",$getid);
	$query -> bindParam (":frid",$frid);
	$query -> execute();
	while($row = $query -> fetch(PDO::FETCH_ASSOC)){
		$msg = $row['msg'];
		$my_msg = $row['user_session'];
		$friend_msg = $row['message_receiver'];	
		$type = $row['type'];
			
	?>
	
		<div id="chat_id_2">
			<span class="chatme"><?php 
						if($my_msg!=null||$my_msg!=""){
							if($type=='Text'){
							echo $my_msg;
							}
							else{
							?>
						<a style='text-decoration:underline;color:red;' href='files/chat_files/<?php echo $my_msg;?>' download>
						 <?php echo $my_msg;?>
							</a>
							<?php
							}
						}?></span>
			<span class="chatfrnd"><?php 
						if($friend_msg!=null||$friend_msg!=""){
							if($type=='Text'){
							
							echo $friend_msg;
							}
							else{
							?>
							<a style='text-decoration:underline;' href='files/chat_files/<?php echo $friend_msg;?>' download>
							<?php
							echo $friend_msg;
							?>
							</a>
							<?php
							}
						}?></span>
		</div>
		
	<?php
	}
	?>
	<script>
	$('#chat_id_2').scrollTop($('#chat_id_2')[0].scrollHeight);
	
	</script>
</body>