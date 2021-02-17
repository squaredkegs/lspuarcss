	<!DOCTYPE html>
<?php
	//session_start();
	include_once ("php/connection.php");
	include_once ("php/function.php");
	include_once ("php/querydata.php");
	$check_user_login = checkLogIn();

	$password_error = "gdfgrdwte6rtiyjhggfdsfertyjht";
	$lct = "fdskjfdshjfdbjhgfdoejitf";
	$chngml = "";
	$password_status = "unknowgvn";
	if(isset($_GET['lct'])){
		$lct = $_GET['lct'];
		
		if($lct=="pass"){
			if(isset($_GET['stat'])){
			$password_error = $_GET['stat'];
			$password_status = "ready";
			}
		}
		else if($lct=="account"){
			if(isset($_GET['chngml'])){
				$chngml = $_GET['chngml'];
			}
		}
		
	}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home | <?php echo $lct;?></title>
	
	<!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
	<link href="css/newsfeed.css" rel="stylesheet">
    
	<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="image/thesis.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
	<link rel="stylesheet" href="css/jquery-ui.css">
	
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
		
	<script type="text/javascript">
		
		
		
		var settings = "<?php echo $lct; ?>";
		var error = "<?php echo $password_error;?>";
		var change_email = "<?php echo $chngml;?>";
		var passwordstatus = "<?php echo $password_status;?>";
		$(document).ready(function(){
			if(settings=="pass"){
				if(passwordstatus=="ready"){
				$("#account-info-contain").load("accounts/password-change.php?err=" + error);
				}
				else{
				$("#account-info-contain").load("accounts/password-change.php");
					
				}
			}
			else if(settings="account"){
				if(change_email!=""){
					$("#account-info-contain").load("accounts/account-settings.php?chngml=" + change_email);
				}
				else{
				$("#account-info-contain").load("accounts/account-settings.php");
				}
				
			}
			
		});	
		$(document).ready(function(){
			$(".account-settings").click(function(){
				var id = $(this).attr("id");
				switch(id){
					case "change-password":
					$("#account-info-contain").load("accounts/password-change.php");
					window.history.pushState("account", "account", "/smnp/settings?lct=pass");
					break;
					case "user-account":
					$("#account-info-contain").load("accounts/account-settings.php");
					window.history.pushState("account", "account", "/smnp/settings?lct=account");
				}
			});
		});
		
		
		
	</script>
</head><!--/head-->
<body class="homepage">

	<?php
		include_once ("header.php");
	?>
    
		<div class="container" style="margin-left:0px;">
			<div class="col-md-3" style="height:500px;margin-top:40px;background-color:white;">
				<ul style="list-style-type:none;">
					<li class="account-settings" id="change-password"><a href="#" id="change_ password">Change Password</a></li>
					<li class="account-settings" id="user-account"><a href="#" id="sec_nd_prv">Account Settings</a></li>
				
				</ul>
			</div>

			<div id="account-info-contain" class="col-md-6">
			</div>			
		</div>
  <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
               <div class="col-sm-6" style="color:white;">
                   &copy; 2017 <a target="_blank" href="#" title="arvin is awesome">Suicide Squad</a>. All Rights Reserved.
                </div>

            </div>
        </div>
    </footer><!--/#footer-->

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/wow.min.js"></script>
</body>
</html>