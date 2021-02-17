<?php

	include_once "connection.php";
	include_once "function.php";
	include_once "querydata.php";
	$frid = $_GET['frid'];
	$cid = createRandomId('chat_id','chat');
	$datetime = date("Y-m-d H:i:s");
?>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link href="css/message.css" rel="stylesheet">

		<script>
		function realTimeChat(){
		var req = new XMLHttpRequest();
		var frid = "<?php echo $frid;?>";
		req.onreadystatechange = function(){
		if(req.readyState == 4 && req.status==200){
			document.getElementById('chat_id').innerHTML = req.responseText;
		}
		}
		req.open('GET','php/get_chat_thread.php?frid=' + frid,true);
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
						url: 'php/send_message.php',
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
		
		$(document).ready(function(){
			$("#my_message").click(function(){
				frid = "<?php echo $frid;?>";
					$.ajax({
						type: 'POST',
						url: 'php/unread_messages.php',
						data: {frid: frid},
						cache: false,
						
					});
						
			});
		});
		
		
		
		$(document).ready(function(){
			$("#file_file").on("change", function(){
				var file_name = $(this).val();
				
				var c_index = file_name.lastIndexOf(".");
				var file_cut_name = file_name.substr(c_index + 1).toUpperCase();
				var cut_name = file_name.lastIndexOf("\\");
				var new_name = file_name.substr(cut_name + 1);
				var new_file_name = file_name.substr(cut_name + 1);
				var cut_new_file_name = new_file_name.lastIndexOf(".");
				var real_new_file_name = new_file_name.substr(0, cut_new_file_name);//The filename (e.g C://desktop/Hahaha.jpg to hahaha only)
				
				if(file_name==""){
					$("#send_file").hide();
					$("#show_file_name").hide();
					$("#show_file_name").html("");
					$("#clear_file").hide();
					$("#send_file").prop('disabled', true);
				}
				else{
					if(
					file_cut_name=="PDF"  || 
					file_cut_name=="DOCX" || 
					file_cut_name=="PPT"  || 
					file_cut_name=="PPTM" || 
					file_cut_name=="PPTX" || 
					file_cut_name=="PPSX" || 
					file_cut_name=="XLSM" ||
					file_cut_name=="XLSX" ||
					file_cut_name=="XLT"  ||
					file_cut_name=="DOC"  ||
					file_cut_name=="DOT"  ||
					file_cut_name=="DOC"  ||
					file_cut_name=="DOCX" ||
					file_cut_name=="DOTX" ||
					file_cut_name=="DOTM" ||
					file_cut_name=="DOCB" ||
					file_cut_name=="TXT"
					){
					
					
						$("#send_file").prop('disabled', false);
					}
					else{
						$("#send_file").prop('disabled', true);
						
					}
					$("#hidden_file_name").val(real_new_file_name);
					$("#show_file_name").css("display", "inline");
					$("#clear_file").css("display", "inline");
					$("#clear_file").show();
					$("#show_file_name").html(new_name);
					$("#send_file").show();
					
				}
			});
		});
		
		$(document).ready(function(){
			$("#clear_file").on('click', function(){
				$("#show_file_name").html("");
				$(this).hide();
				$("#file_file").val("");
				$("#send_file").hide();
				$("#send_file").prop('disabled', true);
			});
		});
		
		
		$(document).ready(function(){
			$("#send_file").on('click', function(){
				var chatFile = new FormData();
				var frid = "<?php echo $frid;?>";
				var userFile = document.getElementById("file_file");
				chatFile.append('frid', frid);
				chatFile.append('userfile', userFile.files[0]);
					$.ajax({
						type: 'POST',
						url: 'php/send_msg_file.php',
						data: chatFile,
						
						cache: false,
						success: function(data){
							alert(data);
							$("#send_file").hide();
							$("#show_file_name").html("");
							$("#show_file_name").hide();
							$("#clear_file").hide();
							$("#file_file").val("");
							$("#file_file").val("");
						},
						processData: false,
						contentType: false,
					});
			});
		});
		</script>
		
		<script type="text/javascript">
		window.onload = function(){
			var textarea = document.getElementById('my_message')
			textarea.focus()	
		
			window.onhashchange = function(){
				textarea.focus()
			}
		}
		</script>
	<style>
	div#message-scrollable
	{
		width:750px;
		padding-bottom:3px;
		height:350px;
		border: 1px solid black;
		border-radius:7px;
		overflow:hidden;
		overflow-y: scroll;
		background-color:white;
		box-shadow: 10px 10px 2px black;
	}
	</style>
	<body>
	

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

		<div id="message-scrollable" class="mousescroll">
			<div id="chat_id">
			</div>
		</div>
		<textarea autofocus style="margin-top:10px; resize:none;height:60px;width:750px;" id="my_message" class='form-control'></textarea>
		<label for='file_file' style='display:block' title='For Sending Files'>&nbsp;<img src='image/extra/fileaa.png' style='margin-top:2px;cursor:pointer;height:25px;width:30px;;'/></label>
		</span>
		<div>
		<span id='show_file_name' style='display:none;'></span>
		&nbsp;
		<span id='clear_file' style='cursor:pointer;color:black;font-weight:bold;display:none;'>X</span>
		</div>
		<input type='hidden' readonly value='' id='hidden_file_name'>
		<button id='send_file' class='btn btn-info' style='display:none;'>Send File</button>
		<input type='file' id='file_file' name='media' style='display:none;' accept='.xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf'>
		
		<script>
		$('#chat_id').scrollTop($('#chat_id')[0].scrollHeight);

		//var d = $('#chat_id');
		//d.scrollTop(d.prop("scrollHeight"));
		</script>
</body>
