
<?php
session_start();
include_once ("php/connection.php");
if(isset($_SESSION['log_user']))
{
	header("location:index.php");

}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>LSPU-ARCSS | Log In</title>
	
	<!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
	<link href="css/login.css" rel="stylesheet">
	
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="image/thesis.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">

	<script src="js/jquery-1.12.4.js"></script>
	
	<script type="text/javascript">
	
	
	function focusOnInput()
	{
		document.getElementById("username").focus();
	}

	</script>

	</head><!--/head-->
	
	

<body onload="focusOnInput()" style="background: linear-gradient(to bottom, #F0F8FF 0%, 	#F5F5F5 100%);">

    <header id="header">
<!--/.top-bar-->

        <nav class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index" style="font-family: Trajan Pro; text-shadow: 3px 3px black;">
					ARCSS <img src="image/lspulogo.png" title="Laguna State Polytechnic University" width="35" height="35" alt="logo"></a>
                </div>
					<!--Place of Navbar -->
              
			  
				
					<!--Place of Navbar-->
            </div><!--/.container-->
        </nav><!--/nav-->
	</header><!--/header-->
	<!--BANNER-->	<img src="image/banner.png" title="Laguna State Polytechnic University" alt="logo" style="margin-top:4px;" width="100%" height="100%" border="0"> <!--BANNER-->
    <section id="about-us">
        <div class="container">
			
				<div class="col-md-8 col-md-offset-2" style="background-color:white; border-style:solid; border-color:#B0E0E6; border-radius:3px;">
					<center>
					<label class="maintitle logform">Log-In</label>
					<?php
						if(isset($_GET['err'])){
							$err = $_GET['err'];
							if($err=='invlusr'){
					?>
						<button class="btn-danger btn" disabled style="display:block;margin-top:0px;margin-bottom:5px;cursor:auto;color:white;">Invalid Username or Password</button>
					<?php	
							}
							else if($err=='ntaprvd'){
					?>
						<button class="btn-danger btn" disabled style="display:block;margin-top:0px;margin-bottom:5px;cursor:auto;color:white;">Account Not Yet Approved by Admin</button>
					
					<?php
							}
							else if($err=='accntbn'){
					?>
						<button class="btn-danger btn" disabled style="display:block;margin-top:0px;margin-bottom:5px;cursor:auto;color:white;">Account Banned by Admin</button>
					<?php			
							}
							else if($err=='ntlog'){
					?>			
							<button class="btn-warning btn" disabled style="display:block;margin-top:0px;margin-bottom:5px;cursor:auto;color:white;">Login First</button>
					<?php
							}
						}
						
					?>
					</center>
				<div class='field' >	
					<div class="col-md-6 col-md-offset-3 logform" >
						<center>
						<form action="php/login_exec.php" method="POST" class="form-inline"
						name="form">
						<span style="display:block;margin-bottom:10px;">Email/Username</span>
						<input style="margin-bottom:10px;" name="username" type="text" class="form-group" id="username">
						<span style="display:block;margin-bottom:10px;">Password</span>
						<input style="margin-bottom:10px;" name="password" type="password" class="form-group">
						<input type="submit" name="login" class="btn submitlog"
						style="display:block;margin-bottom:10px; background-color:	#1E90FF;">
						<span class="forgotmain" style="margin-right:10px;"><a href="#">Forgot Password/Username</a></span>-
						<span class="forgotmain" style="margin-right:10px;"><a href="signup.php"> Sign Up</a></span>
						</form>
						</center>
					</div>
					
				</div>
					
				
				</div>
			
			
		</div><!--/.container-->
    </section><!--/about-us-->
	
<!--/#bottom-->

    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6" style="color:white;">
                   &copy; 2017 <a target="_blank" href="#" title="arvin is awesome">Suicide Squad</a>. All Rights Reserved.
                </div>
           
            </div>
        </div>
    </footer><!--/#footer-->
    


    <script type="text/javascript">
        $('.carousel').carousel()
    </script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/wow.min.js"></script>
</html>
</body>