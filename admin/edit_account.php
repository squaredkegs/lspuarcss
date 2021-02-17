<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");

	$adminid = adminLog();

?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Admin Accounts</title>
	<link rel="shortcut icon"  href="image/adminlogo.ico" />
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

	<!--For the table-->
	<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<link rel="stylesheet" href="css/jquery-ui.css">

	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>

    <script src="js/jquery-ui.min.js"></script>
	<script>
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1;
		var yyyy = today.getFullYear();
		
		if(dd<10){
			dd = '0' + dd;
		}
		if(mm<10){
			mm = '0' + mm;
		}
		today = yyyy + '-' + mm + '-' + dd;
		$(document).ready(function(){
			$("#date_extension_submit").on('click', function(e){
				var today_string = Date.parse(today);
				var date = $("#extension_date").val();
				var date_string = Date.parse(date);
				var cur_expire = $("#current-expire-date").val();
				var cur_xpire_string = Date.parse(cur_expire);
				var aid = $("#aid").val();
				if(date){
					if(today_string>=date_string){
						$("#date-error-container").css("color", "red");
						$("#date-error-container").text("Invalid Date Input");
						document.getElementById("extension_date").focus();
					}
					else{
						if(cur_xpire_string>=date_string){ 
						$("#date-error-container").css("color", "red");
						$("#date-error-container").text("Date must be bigger than current expiration date");
						document.getElementById("extension_date").focus();
						}
						else{
						$("#date-error-container").css("color", "green");
						$("#date-error-container").text("Please Wait");
							$.ajax({
								type: 'POST',
								url: 'php/request_account_extension.php',
								data:
								{
									date: date,
									aid: aid,
								},
								success: function(data){
									$("#date-error-container").css("color", "green");
									$("#date-error-container").text("Request sent. Wait for confirmation");
								},
							});
						}
					}
				}
				else{
					$("#date-error-container").css("color", "red");
					$("#date-error-container").text("Date Empty"); 
					document.getElementById("extension_date").focus();
				}
				e.preventDefault();
			});
		});
	</script>
	<style>
	input[type=date]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    display: none;
	}
	</style>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

 			<?php 
			
				include_once ("navbar.php");
				include_once ("sidebar.php");
			?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" style="background-color:#DCDCDC;">
				  <div class="box">
					<div class="box-header">
					  <h3 class="box-title"style="font-family:sans-serif;">
						<img src="image/new.ico" title="LSPU" alt="logo" width="20" height="20" border="0" style="margin-bottom:5px;">
						Edit Account</h3>
					</div><!-- /.box-header -->
					<div class="box-body" style="background-color:#DCDCDC;">
						<div class='col-md-12'>
						<label style='font-size:22px;'><?php echo $rfname." ".$rlname;?></label>
						<?php 
						$read_raccount_expire = date("F d, Y", strtotime ($raccount_expire));
						$raccount_expire = date("Y-m-d", strtotime($raccount_expire));
						?>
						</div>
						<div class='col-md-6'>
						<form action='php/change_email.php' method='POST'>
							<label>Email</label>
							<input type='text' name='new_email' class='form-control' value='<?php echo $remail;?>' required>
							<input type='submit' name='save_edit' class='btn btn-info form-control' value='Change Email' style='margin-top:10px;'>
						</form>
						<form action='#' method='POST'>
						<label style='display:block;margin-top:15px;'>Account Expiration: 
							<?php
								if($rstatus=='Requesting Extension'){
									echo "(<span style='font-size:13px;'>Waiting account extension)</span>";
								}
							?>
						</label>
						<input type='hidden' id='aid' value='<?php echo $aid;?>' readonly>
						<input type='hidden' id='current-expire-date' class='form-control' value='<?php echo $raccount_expire;?>' />
						<input type='text' class='form-control' value='<?php echo $read_raccount_expire;?>' readonly style='margin-top:10px;'>
						<input id='extension_date' type='date' class='form-control' style='margin-top:10px;margin-bottom:5px;'>
						<span style='height:20px;margin-top:4px;color:red;margin-left:15px;margin-bottom:4px;display:block;' id='date-error-container'></span>
						<input id='date_extension_submit' type='submit' class='btn btn-info' name='Request Date Extension' value='Request Account Extension'>
						</form>
						</div>
						<div class='col-md-6'>
						<form action='php/change_password.php' method='POST'>
							<label>Old Password</label>
							<input type='password' name='old_password' class='form-control' required>
							<label>New Password</label>
							<input type='password' name='new_password' class='form-control' required>
							<label>Retype New Password</label>
							<input type='password' name='renew_password' class='form-control' required>
							<input type='submit' name='change_password' value='Change Password' class='form-control btn btn-info' style='margin-top:10px;'>
						</form>
						</div>
					</div><!-- /.box-body -->
				  </div>
		</div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
      
        </div>
        <strong>Copyright &copy; 2016-2017 <a href="http://almsaeedstudio.com">Suicide Squad</a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar -->
      <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<!--DataTables-->
	<script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

    <!-- Slimscroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
	<!--Ito Yung Problema-->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
   <!-- <script src="dist/js/pages/dashboard.js"></script>
    -->
	<!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
	
	<!-- For Table Database-->
</body>
</html>
