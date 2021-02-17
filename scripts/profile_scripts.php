	<script type="text/javascript">
	<?php 
		if(isset($_GET['sect'])){
			$_GET['sect'];
			$section = $_GET['sect'];
		}
		else{
			$section = "main";
		}
		if(isset($_GET['user'])){
			if($profile_num_row==1){
				if($getid==$user_id || !isset($_GET['user'])){
				$real_id = $getid;
				}
				else if(isset($_GET['user']) && $getid!=$user_id){
				$real_id = $user_id;
				}
			}
		}
		else{
			$real_id = $user_id;
		}
	?>
	
	var section = "<?php echo $section;?>";
	var number = "<?php echo $real_id;?>";
	$(document).ready(function(){
		if(section=="main"){
			$("#information-container").load("profilepages/main-section?user=" + number);
		}
		else if(section=="oth"){
			$("#information-container").load("profilepages/other-section?user=" + number);	
		}
		else if(section=="post"){
			$("#information-container").load("profilepages/post-section?user=" + number);
		}
		else if(section=="friends"){
			$("#information-container").load("profilepages/myfriends-section?user=" + number);
		}
		else if(section=="saves"){
			$("#information-container").load("profilepages/save-section?user=" + number);
		}
	});

	var getid = 4;
	$(document).ready(function(){
		$(".profile-click").click(function(e){
		e.preventDefault();
	
	id = $(this).attr("id");
			switch(id){
				case "main-click":
				
				$("#information-container").load("profilepages/main-section?user=" + number);
				window.history.pushState ("Main", "Main", "/smnp/myprofile?user=" + number);
				break;
				case "other-click":
				$("#information-container").load("profilepages/other-section?user=" + number);
				window.history.pushState ("Main", "Main", "/smnp/myprofile?user=" + number + "&sect=oth");
				break;
				case "post-click":
				$("#information-container").load("profilepages/post-section?user=" + number + "&sect=post");
				window.history.pushState ("Main", "Main", "/smnp/myprofile?user=" + number +"&sect=post");
				break;
				case "friend-click":
				$("#information-container").load("profilepages/myfriends-section?user=" + number);
				window.history.pushState ("Main", "Main", "/smnp/myprofile?user=" + number +"&sect=friends");
				break;
				case "save-click":
				$("#information-container").load("profilepages/save-section?user=" + number + "&sect=saves");
				window.history.pushState ("Main", "Main", "/smnp/myprofile?user=" + number + "&sect=saves");
				break;
			}
		});
	});
	
	
	

							
	//Create username
	
	
	
	$(document).ready(function(){
		$("#camera_icon").click(function(){
			$("#change_profile_pic").dialog({
				resizable: false,
				height: 300,
				width: 400,
				modal: true,
			});
		});
	});
	
	$(document).ready(function(){
		$("#choose-from-current").click(function(){
			$("#choose_from_current_photos").dialog({
				resizable: false,
				height: 480,
				width: 710,
				modal: true,
			});
		});
	});
</script>

	