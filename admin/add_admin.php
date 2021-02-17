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
 
	<script type="text/javascript">
	$(document).ready(function(){
		$('#accept_button').click(function(){
			$.ajax ({
				url: php/pending_exec.php,
				type: 'POST',
				data:
				{
					accept: accept,
					studid: studid
				},
				success: function(msg)
				{
					alert('Accepted Sent');	
				}
			});
		});
	});
	
		$(document).ready(function(){
			var num = 0;
			var pass = document.getElementById('password');
			$("#showpass").click(function(){
				if(num==0){
					num = 1;
					pass.type = 'text';
				}
				else{
					num = 0;
					pass.type = 'password';
				}
			});
		});

		$(document).ready(function(){
			$("#password").blur(function(){
				if($(this).val().length <= 0)
				{
				$.post('php/signcheck.php', 	{ password: form.password.value },
					function(result){
						$('#passwordstat').html(result).show();
					});
				}
				else if($(this	).val().length>=7){
					$("#passwordstat").css("color", "green");
					$("#passwordstat").html("")
				}
				else
				{
					$("#passwordstat").css("color","red");
					$("#passwordstat").html("Password is too short!");
				}
			});
		});

		$(document).ready(function(){
			$('#emailstat').load('php/check_register.php').show();
			$('#email').blur(function(){
				
				$.post('php/check_register.php', { email:  admin_register.email.value }, 
				function(result){
					$('#emailstat').html(result).show();
				});
			});
			
		});	
		
		$(document).ready(function(){
			$('#userstat').load('php/check_register.php').show();
			$('#username').blur(function(){
				
				$.post('php/check_register.php', { username:  admin_register.username.value }, 
				function(result){
					$('#userstat').html(result).show();
				});
			});
			
		});	
	</script>
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
						Add Another Admin Account</h3>
					</div><!-- /.box-header -->
					<div class="box-body" style="background-color:#DCDCDC;">
					  <!--Remove id="example one on table-->
						<form method="POST" action="php/create_admin_acc.php" name='admin_register'>
						<div class="col-md-12">
							<div class="col-md-6">
								<label style="margin-bottom:10px;margin-top:15px;">First Name</label>
								<input autocomplete="off" type="text" name="fname" class="form-control" required>
								<label style="margin-bottom:10px;margin-top:15px;">Last Name</label>
								<input autocomplete="off" type="text" name="lname" class="form-control" required>
								<label style="margin-bottom:10px;margin-top:15px;">Username <span style='font-size:13px;'>(Minimun 8)</span></label>
								<label id='userstat'></label>
								<input autocomplete="off" type="text" id='username'name="username" class="form-control" required>
								<label style="margin-bottom:10px;margin-top:15px;">Email</label>
								<label id='emailstat'></label>
								<input autocomplete="off" type="email" name="email" class="form-control" required id='email'>
																
							</div>
							<div class="col-md-6">
								<label style="margin-bottom:10px;margin-top:15px;">Password  <span style='font-size:13px;'>(Minimun 8)</span></label>
								</label>
								<span id="passwordstat"></span>
								<input autocomplete="off" type="password" name="password" class="form-control" required id="password">
								
								<img src="image/eye.png" id="showpass" style="height:27px;width:20px;cursor:pointer;position:absolute;top:45px;right:20px;">
								<label style="margin-bottom:10px;margin-top:15px;">Position</label>
								<select name="position" class="form-control" required>
									<option value="">Select</option>
									<option value="Main Admin">Main Admin</option>
									<option value="School Admin">School Admin</option>
								</select>
								
								<label style="margin-bottom:10px;margin-top:15px;">Campus</label>
								<select name="campus" class="form-control" required>
									<option value="">Select</option>
									<option value="Los Banos">Los Banos</option>
									<option value="Siniloan">Siniloan</option>
									<option value="San Pablo">San Pablo</option>
									<option value="Sta. Cruz">Sta. Cruz</option>
								</select>
								<label style='margin-bottom:10px;margin-top:15px;'>
								Department
								</label>
								<select name='department' class='form-control' required>
									<option value=''>Select Department</option>
									<?php
									
										$qr_dept = $db -> prepare ("SELECT department FROM department_tbl");
										$qr_dept -> execute();
										while($r_qr_dept = $qr_dept -> fetch(PDO::FETCH_ASSOC)){
										$dept = $r_qr_dept['department'];
									?>
									<option value='<?php echo $dept;?>'><?php echo $dept;?></option>
									<?php
										}
										
									?>
								</select>
							</div>
							<input type="submit" name="submit" style="margin-top:25px;" class="btn btn-info form-control">
							
							
						</div>
						</form>
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
		<script>
      $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });
		</script>
</body>
</html>
