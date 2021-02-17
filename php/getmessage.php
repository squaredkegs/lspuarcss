<?php
include_once ("connection.php");
include_once ("function.php");
include_once ("querydata.php");
?>


<script type="text/javascript">
	$(document).ready(function(){
		$("#my-message").keydown(function(e){
			var message = $(this).val();
			var frid = <?php echo $_POST['frid'];?>;
			if(!$.trim($(this).val())){
			
				 if(e.which==13){
					e.preventDefault();
					return false;
				 }
					
			}
				if(e.which==13){
						if(this.length>=5){
							alert('Worked');
						}
						$.ajax({
						type: 'POST',
						url: 'php/send_message.php',
						data:
						{
							msg: message,
							frid: frid,
						},
						cache:false,
							success: function(data){
							}
					});
					$.trim($(this).val(""));
				e.preventDefault();
				}
		
		});
	});
</script>	

<?php
	if(isset($_POST['frid'])){
		$sid = $getid;
		$frid = $_POST['frid'];
		/*$friend_qr = $db -> prepare ("SELECT fname, lname FROM stud_bas WHERE stud_id=:sid");
		$friend_qr -> bindParam (":sid",$frid);
		$friend_qr -> execute();
		$res = $friend_qr -> fetch();
		$fr_fname = $res['fname'];
		$fr_lname = $res['lname'];
		*/$query = $db -> prepare ("SELECT receiver_id,sender_id, chat.msg, chat.datetime,
			CASE WHEN sender_id = :sid AND receiver_id = :frid THEN chat.msg
			END as 
			user_session,
			CASE WHEN sender_id = :frid AND receiver_id = :sid THEN chat.msg
			END AS
			message_receiver
            FROM chat_connect            
            INNER JOIN chat
            ON chat_connect.chat_id = chat.chat_id
			ORDER BY chat.datetime ASC");			
		$query -> bindParam(":sid", $sid);
		$query -> bindParam(":frid", $frid);
		$query -> execute();
		?>
			
		
		
		<?php
		while($row = $query -> fetch(PDO::FETCH_ASSOC)){
			
			$my_msg = $row['user_session'];
			$friend_msg = $row['message_receiver'];	
			
			
			?>
			<div style="margin-top:5px;margin-bottom:10px;" id="chat-container">
				<div>
				<span style="color:red;" class="my-current-msg"><?php echo $my_msg; ?></span>
				</div>
				<div>
				<span style="color:black;" class="friend-msg"><?php echo $friend_msg; ?></span>
				</div>
			</div>
		<?php
		}
		?>
		<h1 style="color:black;"><?php echo $_POST['frid'];?></h1>
		<textarea id="my-message" cols="80" rows="4"></textarea>
		
		<?php
	}
	

?>
