<?php

	include "connection.php";
	include "querydata.php";
	$frid = $_GET['frid'];
?>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="../js/jquery-1.12.4.js"></script>
	<script src="../js/jquery-ui.js"></script>

		<script>
		function realTimeChat(){
		var req = new XMLHttpRequest();
		var frid = "<?php echo $frid;?>";
		req.onreadystatechange = function(){
		if(req.readyState == 4 && req.status==200){
			document.getElementById('chat_id').innerHTML = req.responseText;
		}
		}
		req.open('GET','trychat.php?frid=' + frid,true);
		req.send()
		}
		setInterval(function(){realTimeChat()},1000);
		
		
		$(document).ready(function(){
			$("#my_message").keydown(function(e){
				var message = $(this).val();
				var frid = "<?php echo $frid;?>";
				if(!$.trim($(this).val())){
					if(e.which==13){
						e.preventDefault();
						return false;
		
					}
				}
				else if(e.which==13){
					$.ajax({
						type:'POST',
						url: 'test.php',
						data: 
						{
							message: message,
							frid: frid
						},
						cache: false,
					});
					$.trim($(this).val(""));
				e.preventDefault();
				}
			});	
		});

		</script>

<?php
	/*$query = $db -> prepare("SELECT receiver_id,sender_id, chat.msg, chat.datetime, CASE WHEN sender_id = :sid AND receiver_id = :frid THEN chat.msg END as user_session, CASE WHEN sender_id = :frid AND receiver_id = :sid THEN chat.msg END AS message_receiver FROM chat_connect INNER JOIN chat ON chat_connect.chat_id = chat.chat_id ORDER BY chat.datetime ASC
	");
	$query -> bindParam (":sid",$getid);
	$query -> bindParam (":frid",$frid);
	$query -> execute();
	while($row = $query -> fetch(PDO::FETCH_ASSOC)){
		$msg = $row['msg'];
		$my_msg = $row['user_session'];
		$friend_msg = $row['message_receiver'];	
			
	?>
	
		<div id="chat_id">
			<span style="color:red;"><?php echo $my_msg;?>
			<span style="color:blue;"><?php echo $friend_msg;?>
		</div>
	
	<?php
	}*/
	?>
	
	
	<textarea style="resize:none;height:60px;width:400px;" id="my_message"></textarea>

	<?php
	if(isset($_POST['submit'])){
	$msg = $_POST['msg'];
	$query = $db -> prepare ("INSERT INTO chat (chat_id,msg,datetime) VALUES (:cid,:msg,:datetime)");
	$query -> bindParam (":cid",$cid);
	$query -> bindParam (":msg", $msg);
	$query -> bindParam (":datetime",$datetime);
	$query -> execute();
			$receiver_id = $frid;
			$join_query = $db -> prepare ("INSERT INTO chat_connect (chat_id,sender_id,receiver_id) VALUES (:cid,:sender_id,:receiver_id)");
			$join_query -> bindParam (":cid", $cid);
			$join_query -> bindParam (":sender_id", $getid);
			$join_query -> bindParam (":receiver_id", $receiver_id);
			$join_query -> execute();
	if($join_query){
		echo "<embed loop='false' src='chat.wav' hidden='true' autoplay='true'/>";
	}	
	}
	?>

			<div id="chat_id">
			</div>
			<p><?php echo $frid;?></p>
