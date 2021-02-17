<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	
	if(isset($_SESSION['admin_log'])){
		header("location:index");
	}
?>
<html>
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>These | Admin</title>
	<link rel="shortcut icon"  href="image/login.ico" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	    <link rel="stylesheet" href="css/main.css">
	<!--For the table-->
	<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <script src="js/jquery-1.12.4.js"></script>
  <script type="text/javascript">
	
	
	function focusOnInput()
	{
		document.getElementById("admin").focus();
	}

	</script>
	

</head>

<body onload="focusOnInput()" style="background-color:white; 
background-repeat: no-repeat; background-size: 100% 100%;">
   
  
    
		<div class="col-md-8 col-md-offset-4" style="background-color:white; 
background-repeat: no-repeat; background-size: 100% 100%; margin-top:50px;
		width:500px;height:600px;margin-right:50px;margin-top:30px; border-radius: 4px;
		">
		   <center><p style="font-size:50px; font-family:sans-serif;">
		   <img src="image/adminlogo.ico" title="LSPU" alt="logo" width="100" height="100" border="0">These</p></center>
		   <center><p style="font-size:15px">Admin for Laguna State Polytechnic University</p></center>
		   
		  <div class="col-md-8 col-md-offset-2" style="background-color:#DCDCDC;
		  margin-top:5px; border-radius: 5px; border:1px;
		  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
				<?php
					if(isset($_GET['err'])){
						$err = $_GET['err'];
						if($err=='invlps'){
						?>
						<center><p style="margin-top:20px;border-radius:6px;padding:5px;" class=" btn-danger">Wrong Username/Password</p></center><?php
						}
						else if($err=='accountxpire'){
						?>
						<center><p style="margin-top:20px;border-radius:6px;padding:5px;" class=" btn-danger">Account Expired!</p></center>
						<?php
						}
					}
				?>
				<form action="php/admin_log_exec.php" method="POST" class=""
			  style="margin:10px 25px 25px 25px;">
				
						
					<center><img src="image/login2.ico" title="LSPU" alt="logo" width="150" height="150" border="0"
					style="margin-bottom:7px;"></center>
					
				<input type="text" name="admin" id="admin" class="form-control"  style="display:block;margin:auto;margin-bottom:10px;" placeholder="Input Your Username">
				<input type="password" name="adpassword" class="form-control"  style="display:block;margin:auto;margin-bottom:10px;" placeholder="Input Your Password">
						<center><input type="submit" name="log" class="btn btn-info form-control" style="margin-top:10px;" value="Log - In"></center>
			  </form>
			  
		  </div>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>&nbsp;</center>
	<center>Santa Cruz | Los Baños | San Pablo | Siniloan </center>
	
	
	
	
		</div>
 
		  </body>
</html>
