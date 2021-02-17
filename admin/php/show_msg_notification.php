 <?php
 	
	include_once ("connection.php");
	include_once ("queryadmindata.php");
	
	
	$msg_count = 0;
	$message_count = $db -> prepare ("SELECT DISTINCT(admin_id) FROM admin_message_connect WHERE receiver_id = :aid AND seen = 0");
	$message_count -> bindParam (":aid", $aid);
	$message_count -> execute();
	$msg_count = $message_count -> rowCount();
		
 ?>
	<li>
		<ul class="menu">
			<li>
				<a href='message.php'>
					<i class="fa fa-users text-aqua"></i> 
					<?php
					if($msg_count>0){
					?>
					<span>You have <?php echo $msg_count;?> new messages</span>
					<?php
					}
					else{
					?>
					<span>No new message</span>
					<?php
					}
					?>
				</a>
			</li>
			<li class="footer"><a href="message.php">See All Messages</a></li>

		</ul>
	</li>
	