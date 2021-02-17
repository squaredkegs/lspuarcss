<!DOCTYPE html>
<?php
session_start();
include ("php/connection.php");

if(isset($_SESSION['log_user']))
{
	header ("location:index.php");
}

if(isset($_GET['reg'])){
	$occupation_status = $_GET['reg'];
	
}
else{
	$occupation_status = "none";
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>LSPU-ARCSS | Sign Up</title>
	
	<!-- core CSS -->
	<link rel="shortcut icon" href="image/thesis.png">
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
</head><!--/head-->
    <script type="text/javascript" src="js/jquery.js"></script>

	<script type="text/javascript">
		
		function checkOccupation(){
			var status = "<?php echo $occupation_status;?>";
			if(status=='stud'){
				$("#registration-container").load("pages/sign-up/student_reg.php");
			}
			else if(status=='teach'){
				$("#registration-container").load("pages/sign-up/teacher_reg.php");
			}
			
		}
		
		$(document).ready(function(){
			$("#select_reg_but").click(function(){
			var select = $("#choice").val();
			if(select=='Student'){
				$("#registration-container").load("pages/sign-up/student_reg.php");
				window.history.pushState ("test", "Student Register", "/smnp/signup?reg=stud");
				}
			else if(select=='Teacher'){
				$("#registration-container").load("pages/sign-up/teacher_reg.php");
				window.history.pushState ("test", "Student Register", "/smnp/signup?reg=teach");
				
				}
							
			});
		});
		
		
</script>

<body onload="checkOccupation()" style="background: linear-gradient(to bottom, #F0F8FF 0%, 	#F5F5F5 100%);">
	<?php
		include_once ("header.php");
	?>

    <!--/header-->
	<!--BANNER-->	<img src="image/banner.png" title="Laguna State Polytechnic University" alt="logo" style="margin-top:4px;" width="100%" height="100%" border="0"> <!--BANNER-->
    <section id="about-us">
        <div class="container">
			<div id="registration-container">
				<div class="col-md-12" id="register_select" style="height:350px;">
				<?php
				if(!isset($_GET['reg'])){
				?>

					<center>
					<label style="margin-top:50px; color:black;">Choose your occupation</label>
					<select id="choice" class="form-control" style="width:250px;margin-bottom:20px;">
						<option value="Student">Student</option>
						<option value="Teacher">Teacher</option>
					</select>
						<button type="button" class="btn btn-info"  style="width:250px;" id="select_reg_but">Select</button>
					<span id="test"></span>
					</center>
				<?php
				}
				?>

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
      //  $('.carousel').carousel()
    </script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/wow.min.js"></script>
</body>
</html>