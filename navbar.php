<?php
include ("php/connection.php");
include_once ("php/querydata.php");
include_once ("php/function.php");
?>


<html>
<head>
	<link href="css/jquery.dropdown.css" rel="stylesheet">
	<link href="css/navbar.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.dropdown.js"></script>
	
	<!--Comment
	<script src="js/jquery-1.12.4.js"></script>
	-->

	<script type="text/javascript">
	
		function checkUnreadMessage(){
			var unread = new XMLHttpRequest();
			unread.onreadystatechange = function(){
				if(unread.status == 200 && unread.readyState==4){
					document.getElementById('new-messages').innerHTML = unread.responseText;
				}
			}
			unread.open('GET','php/newmessages.php',true);
			unread.send()
		}
		
		function checkFriendRequest(){
			var friend_rqst = new XMLHttpRequest();
			friend_rqst.onreadystatechange = function(){
				if(friend_rqst.status==200 && friend_rqst.readyState==4){
					document.getElementById('friend-request-notification').innerHTML = friend_rqst.responseText;	
				}
			}
			friend_rqst.open('GET','php/friend_request_notification.php',true);
			friend_rqst.send()
		}
		
		setInterval(function(){checkFriendRequest()},1000);
		setInterval(function(){checkUnreadMessage()},1000);

		
	$(document).ready(function(){
		$("#notification-count").click(function(){
			$("#unseen-notification").hide();
			var sid = "<?php echo $_SESSION['log_user'];?>";
				$.ajax({
					type: 'POST',
					url: 'php/friend_request.php',
					data:
					{
						unseen_sid: sid
					
					},
					cache: false,
					
					
				});
		});
	});
	
	
	
	$(document).ready(function(){
		$("#notification-count").click(function(){
			//$(this).hide();
			$("#show-friend-request").load("navbar-friend-rqst.php");
		});
	});
	
	$(document).ready(function(){
		$("#search-box").keydown(function(e){
				if(!$.trim($(this).val())){
					if(e.which==13){
						e.preventDefault();
						return false;
					}
				}	
		});
	});
	</script>
	<?php if(!isset($_SESSION['log_user'])){
		?>
	<script type="text/javascript">
	function focusOnInput()
	{
		document.getElementById("username").focus();
	}

	</script>

</head>
<body onload="focusOnInput()">
	<?php
	}else{
	?>
</head>
<body>
<?php	
	}
	?>
	
		<div class="collapse navbar-collapse navbar-right">
          <ul class="nav navbar-nav">
			<?php
		
				if(isset($_SESSION['log_user']))
				{
			
			?>
					<li>
						<form method="GET" action="result" class="form-inline">
							<input type="text" name="var" class="form-control"
							style="width:300px;" id="search-box" placeholder="Search">		
						</form>
					</li>
					<li><a href="index" style="font-size:13px;">Home</a></li>
					<li><a href="myprofile?user=<?php echo $getid;?>"  style="font-size:13px;">Profile</a></li>
					<!--<li><a href="services.html">Friends</a></li>
					<li><a href="portfolio.html">Portfolio</a></li>-->
					<li class="dropdown">
					   <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="image/TA.png" title="Thesis Archive" alt="logo" width="28" height="28" border="0"><i class="fa fa-angle-down"></i></a>
						 <ul class="dropdown-menu">
							<li><a href="thesis_archive"><img src="image/adminlogo.ico" alt="logo" width="30" height="30" border="0">&nbsp;Thesis Archive</a></li>
						   <li><a href="student-accessed-thesis"><img src="image/eye.png" alt="logo" width="30" height="30" border="0"> &nbsp;Accessed Thesis</a></li>
						  </ul>
					</li>
					<li><a href="messages" class="notification-image"><img src="image/Sms2.png" title="Messages" alt="logo" width="32" height="32" border="0"></a>
					<span id="new-messages" class=""></span>
					
					</li>
					<li>
					<!--
					<a href="#" data-jq-dropdown="#friend_request" class="friend-requests notification-image" id="notification-count"><img src="image/Notif.PNG" title="Notifications" alt="logo" width="28" height="28" border="0"></a>
					-->
					<a href="#" data-jq-dropdown="#friend_request" id="notification-count"><img src="image/Notif.PNG" title="Notifications" alt="logo" width="28" height="28" border="0"></a>
					
					<!--
					<span id="frndrqst" class="notification-number" id="unseen-notification"><?php// echo //$real_count; ?></span>
					-->
					<span id="friend-request-notification"></span>
					</li>
					<!--
					<li><a href='php/log_out.php'><img src="image/LG1.PNG" title="Log-out" alt="logo" width="28" height="28" border="0"></a></li>
					<li>
					-->
						<a href="#" data-jq-dropdown="#account_settings"> 
						<img src="image/extra/dropdown_arrow.png" width="14" height="14" border="0"/>
						</a>
					</li>
					<span id="account_settings" class="jq-dropdown jq-dropdown-tip"
					style="background-color:white;border-color:black;border:2px;">
						<ul style="list-style-type:none;padding:5px 20px 10px;border: 2px solid black;">
							<li><a href="settings">Settings</a></li>
							<li><a href='help'>Help</a></li>
							<li><a href='php/log_out.php'>Log-Out</a></li>
							
						</ul>
					</span>
					<span id="friend_request" class="jq-dropdown jq-dropdown-tip">
					

					<div id="show-friend-request">
					</div>
					</span>
				<?php
				
				}
					else
					{
				?>
						
						<li>
						<form action="php/login_exec.php" method="POST" style="display:block;">
						
							<input type="text" placeholder="Username/Email" name="username" style="display:block;" id="username">
							<b><a href="signup.php" style="color:white;
							margin-left:0px;margin-top:5px;margin-bottom:-15px;font-size:10px;">Register?</b></a>
							
						</li>
						<li>
						
							<input type="password" placeholder="Password" name="password" style="display:block;">
							

						</li>
						<li>
							<b><input type="submit" name="login" value="Log-In" class="btn btn-info form-inline" style="background-color:	#1E90FF; display:block; font-size:10px; border:1px solid black;"></b>
						
						</li>
					</form>
						
				<?php
					
					}
				

				?>	
				<li></li>
				<?php 
				
				?>
			</ul>
   </div>
</body>
</html>