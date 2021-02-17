<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");
	$adminid = adminLog();
	$check_pass = $db -> prepare ("SELECT password,expire_date FROM admin_expire_passwords WHERE campus = :campus and department = :dept");
	$check_pass -> bindParam (":campus", $rcampus);
	$check_pass -> bindParam (":dept", $rdepartment);
	$check_pass -> execute();
	$numrow = $check_pass -> rowCount();
	$result = $check_pass -> fetch();
	$expire_date = $result['expire_date'];
	if($numrow>0){
		if($expire_date == '0000-00-00 00:00:00'){
			$expire_date = 'No expiration date yet';
		}
		else{
		$expire_date = date("F d, Y", strtotime($expire_date));
		}
	}
	else{
		$expire_date = 'No expiration date yet';
	}	
													
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Homepage</title>
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
	<!--<script src="js/jquery-ui.min.js"></script>-->
	
	<script>
	
	//var today = new Date();
	var one_week = new Date();
	one_week.setDate(one_week.getDate() + 7);
	var dd = one_week.getDate();
	var mm = one_week.getMonth() + 1;
	var yyyy = one_week.getFullYear();
	
	if(dd<10){
		dd = '0' + dd;
	}
	if(mm<10){
		mm = '0' + mm;
	}
	one_week = yyyy + '-' + mm + '-' + dd;
	var one_week_string = Date.parse(one_week);
	
	function change_date_expiration (this_form,e){
		var new_date = $("#new_date").val();
		var formData = new FormData();
		formData.append('new_expire_date', new_date);
		if(new_date){
			var new_date_string = Date.parse(new_date);
			var dateObject = new Date(Date.parse(new_date));
			var dateReadable = dateObject.toDateString();
		
			
			if(one_week_string>new_date_string){
				$("#change_date_error").css("color", "red");
				$("#change_date_error").text("Date must be one week ahead of today");
			}
			else{
				$("#change_expire").prop("disabled", "disabled");
				$("#change_date_error").css("color", "green");
				$("#change_date_error").text("Updating. Please Wait");
					$.ajax({
						type: 'POST',
						url: 'php/change_expire_date.php',
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						success: function (data){
							$("#current_expire_date").val(dateReadable);
							$("#change_expire").prop("disabled", false);
							$("#change_date_error").text("Date Expiration Updated");
						},
					});
				
				
			}
		}
		else{
			$("#change_date_error").css("color", "red");
			$("#change_date_error").text("No Date Input");
			
		}	e.preventDefault();
	}
	$(document).ready(function(){
		$("#create_password").on('click', function(e){
			var expirepass = new FormData();
			var password = $("#password").val();
			var repass = $("#repassword").val();
			expirepass.append('password', password);
			expirepass.append('repass', repass);
			if(password == "" || repass == ""){
				$("#show_error").text("Fill Password ");
			}
			else{
				if(password.length>7){
					if(password === repass){
						$.ajax({
							type: 'POST',
							url: 'php/create_expire_password.php',
							dataType: 'json',
							data: expirepass,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data){
								if(data.response == 'success'){
								$("#create_password_div").html("<form action='#' method='POST' style='margin-top:150px;' class='form-inline' onsubmit='change_date_expiration(this,event);'><center><label>Change Expiration Date</label><input id='current_expire_date' type='text' value='0000-00-00' class='form-control' style='display:block;margin-bottom:10px;' name='current_expire_date' readonly/><input type='date' class='form-control' style='display:block;' id='new_date' name='new_date'/><span id='change_date_error' style='color:red;display:block;margin-top:5px;'></span><input type='submit' class='btn btn-info form-control' id='change_expire' value='Change Date' style='margin-top:10px;'/></center></form>");
								}
							},
						});
					}
					else{
					$("#show_error").text("Password doesn't Match");
					}			
				}
				else{
					$("#show_error").text("Password too short");
				}
			}
			e.preventDefault();
			
		});
	});		
	
	function check_expire_password(this_form,e){
		var expire_pass = $("#expire_password").val();
		var expire_error = $("#expire_password_error");
		var passwordForm = new FormData();
		var current_date = '<?php echo $expire_date;?>';
		passwordForm.append ('expire_password', expire_pass);
		if(expire_pass){
			$.ajax({
				type: 'POST',
				url: 'php/verify_expire_password.php',
				dataType: 'json',
				data: passwordForm,
				processData: false,
				contentType: false,
				async: true,
				cache: false,
				success: function(data){
					if(data.response == 'false'){
						$(expire_error).css("color", "red");
						$(expire_error).text("Password incorrect!");		
					}
					else if(data.response == 'true'){
						$(expire_error).css("color", "green");
						$(expire_error).text("Please Wait");
						$("#form_expire_div").html("<form action='#' method='POST' style='margin-top:150px;' class='form-inline' onsubmit='change_date_expiration(this,event);'><center><label>Change Expiration Date</label><label>Current Expire Date</label><input id='current_expire_date' type='text' value='" + current_date + "' class='form-control' style='display:block;margin-bottom:10px;' name='current_expire_date' readonly/><input type='date' class='form-control' style='display:block;' id='new_date' name='new_date'/><span id='change_date_error' style='color:red;display:block;margin-top:5px;'></span><input type='submit' class='btn btn-info form-control' id='change_expire' value='Change Date' style='margin-top:10px;'/></center></form>");
					}
				},
			});
		}
		else{
			$(expire_error).css("color", "red");
			$(expire_error).text("Password Empty!");
		}
		e.preventDefault();
	}
	</script>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

			<?php 
				include_once ("navbar.php");
				include_once ("sidebar.php");
			?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
				  <div class="box">
					<div class="box-header">
					
					  <h3 class="box-title">Expire Student Accounts</h3>
						<label style='display:block;'>Current expiration date</label>
						<span style='display:block;'><?php echo $expire_date;?></span>
					</div><!-- /.box-header -->
					<div class="box-body">
						<?php
							if($numrow>0){
						?>
						<div id='form_expire_div'>
						<form action='#' method='POST' style='margin-top:150px;' class='form-inline' onsubmit='check_expire_password(this,event);'>		
							<center>
							<label>Expire-Account Password</label>
							<input type='password' id='expire_password' class='form-control' placeholder='Expire-Account Password'style='display:block;'>
							<span id='expire_password_error' style='color:red;display:block;margin-top:3px;margin-bottom:2px;'></span>
							<input type='submit' class='btn btn-info form-control'value='Log' style='display:block;margin-top:12px;'>
							</center>
						</form>
						</div>
						<?php
							}
							else{
						?>
						<div id='create_password_div'>
						<center>
						<form action='#' method='POST' style='margin-top:150px;' class='form-inline' id='create_password_form'>
							<label style='display:block;'>Create Expire Password 
								<span style='font-size:8.5px;cursor:pointer;text-decoration' title='Password before user can expire accounts from your department'>
								[?]
								</span></label>
							<input type='password' name='password' class='form-control' style='display:block;margin-bottom:12px;' placeholder='Password' id='password'>
							<input type='password' name='repassword' class='form-control' style='display:block;' placeholder='Re-type Password' id='repassword'>
							<span style='display:block;margin-top:5px;color:red' id='show_error'></span>
							<button class='btn btn-info form-control' style='margin-top:14px' id='create_password'>Create Password</button>
						</form>
						</center>
						</div>
						<?php
							}
						?>
					</div><!-- /.box-body -->
				  </div>
		</div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.3.0
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
