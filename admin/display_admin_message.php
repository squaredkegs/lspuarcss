<?php

	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");
	if(isset($_GET['aid'])){
		$fraid = $_GET['aid'];
		$query = $db -> prepare ("SELECT fname, lname, admin_id as fraid,campus, position, department FROM admin_tbl WHERE admin_id = :aid");
		$query -> bindParam (":aid", $fraid);
		$query -> execute();
		$result = $query -> fetch();
		$fname = $result['fname'];
		$fraid = $result['fraid'];
		$lname = $result['lname'];
		$campus = $result['campus'];
		$position = $result['position'];
		$department = $result['department'];
		$fullname = $fname." ".$lname;
		if($position == 'School Admin'){
			$new_position = 'Admin';
		}
		else{
			$new_position = $position;
		}
			$update_seen = $db -> prepare ("UPDATE admin_message_connect SET seen = 1 WHERE admin_id = :fraid AND receiver_id = :aid");
			$update_seen -> bindParam (":fraid", $fraid);
			$update_seen -> bindParam (":aid", $aid);
			$update_seen -> execute();
	}
?>

	<link rel="stylesheet" href='css/jquery-ui.css'>

	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script>
	$(document).ready(function(){
		$("#my_message").on('keydown', function(e){
			var msgForm = new FormData();
			var key = e.which || e.keyCode;
			var msg = $(this).val();
			var fraid = $("#fraid").val();
			msgForm.append('fraid', fraid);
			msgForm.append('msg', msg);
				if(!$.trim($(this).val())){
				if(e.which==13){
					e.preventDefault();
					return false;
				}
			}
			else if(key === 13){
				$.ajax({
					type: 'POST',
					url: 'php/send_msg.php',
					data: msgForm,
					cache: false,
					success: function(data){
					$("#my_message").val("");
				
					},
					processData: false,
					contentType: false,
				});
				
			}
		});
	});
	
	function adminRealTimeChat(){
		var req = new XMLHttpRequest();
		var fraid = $("#fraid").val();
		req.onreadystatechange = function(){
			if(req.readyState == 4 && req.status == 200){
				document.getElementById('msg_display').innerHTML = req.responseText;
			}
		}
		req.open('GET', 'php/get_admin_chat.php?aid=' + fraid,true);
		req.send();
		}
		setInterval(function(){adminRealTimeChat()},500);
	
	$("#my_message").focus();
	$(document).ready(function(){
		var fraid = $("#fraid").val();
		$("#my_message").on('click', function(){
			$.ajax({
				type: 'POST',
				url: 'php/unseen_to_seen.php',
				data: 
				{
					fraid: fraid,
				},
				cache: false,
			});
		});
	});
	$(document).ready(function(){
		$("#msg_file").on('change', function(){
			var raw_file_name = $(this).val();
			var cut_name = raw_file_name.lastIndexOf("\\");
			var file_name = raw_file_name.substr(cut_name + 1);
			var get_extension = raw_file_name.lastIndexOf(".");
	
			var extension = raw_file_name.substr(get_extension + 1).toUpperCase();
			
			if(raw_file_name){
				$("#dummy_name").html(file_name);
				$("#clear_media").css("display", "inline");
				$("#send_file").css("display", "inline");
				if(
					extension == "DOCX" ||
					extension == "DOC"  ||
					extension == "PDF"  ||
					extension == "XLDX" ||
					extension == "XLS"  ||
					extension == "PPT"  ||
					extension == "PPTX" ||
					extension == "TXT" 
					){
					$("#send_file").prop("disabled", false);
				}
				else{
					$("#send_file").prop("disabled", true);
				}
			}
			else{
				$("#dummy_name").html("");
				$("#clear_media").css("display", "none");
				$("#send_file").prop("disabled", false);
				$("#send_file").css("display", "none");
			}
		});
	});
	
	$(document).ready(function(){
		$("#clear_media").on('click', function(){
			$("#msg_file").val("");
			$("#dummy_name").html("");
			$(this).css("display", "none");
			$("#send_file").prop("disabled", false);
			$("#send_file").css("display", "none");
		});
	});
	
	$(document).ready(function(){
		$("#send_file").on('click', function(){
			var fileForm = new FormData();
			var file = document.getElementById('msg_file');
			var fraid = $("#fraid").val();
			fileForm.append('msg_file', file.files[0]);
			fileForm.append('fraid', fraid);	
			
			
			$.ajax({
				type: 'POST',
				url: 'php/send_msg_file.php',				
				data: fileForm,
				cache: false,
				success: function (data){
					alert(data);
					$("#msg_file").val("");
					$("#dummy_name").html("");
					$("#clear_media").css("display", "none");
					$("#send_file").prop("disabled", false);
					$("#send_file").css("display", "none");
				},
				processData: false,
				contentType: false,
				
			});
			
		
		});
		
	});
	</script>
	<style>
		#message-scrollable	
		{
			overflow-y:scroll;
			height:230px;
			margin-bottom:5px;
			border: solid;

		}
	</style>
	<body>
	<?php
	
	?>		
		<h5><a href='#'><?php echo $fullname;?></a></h5>
		<h6 style='cursor:pointer;'>
			<?php
				if($position=='School Admin'){
					echo $new_position." of ".$department.", ".$campus;
				}
				else{
					echo $position;
				}
			?>
		</h6>
		<div id="message-scrollable" class="mousescroll">
			<div id="msg_display">
			</div>
		</div>
		<input type='hidden' value='<?php echo $fraid;?>' id='fraid' readonly>
		<textarea autofocus style="resize:none;height:150px;width:700px;" id="my_message" class='form-control' placeholder='Your Message'></textarea>
		<label for='msg_file' id='file_logo' style='cursor:pointer;'>Add File: <img src='image/file.png' style='height:35px;width:35px;margin-top:15px;'/></label>
		<div>	
			<span id='dummy_name'></span>&nbsp;<span id='clear_media' style='color:red;font-size:14px;display:none;cursor:pointer;'>X</span>
		</div>
		<input type='submit' id='send_file' class='btn btn-info' style='cursor:pointer;display:none;margin-top:5px;' value='Send File'>
		<input type='file' id='msg_file' name='media' style='display:none;' accept='.xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf'>

</body>
