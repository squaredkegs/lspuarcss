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

    <script src="js/jquery-ui.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#select_dept").bind('change click', function(){
				var dept = $(this).val();
					$.ajax({
						type: 'POST',
						url: 'php/select_course_2.php',
						data: 
						{
							dept:dept,
						},
						cache: false,
						success: function(data){
							$("#span_select_course").html(data);
						},
					});
			});
		});
		
		$(document).ready(function(){
			$("#thesis_file").on('change', function(){
				var file_name = $(this).val();
				var button = $("#thesis_button");
				var file_extension = file_name.split('.')[1].toUpperCase();
				if(file_extension=="PDF"){
					$(button).prop('disabled', false);
				}
				else{
					$(button).prop('disabled', true);
				}
			});
		});
	</script>
	<style>
	input[type='text']{
		margin-top:10px;
		margin-bottom:10px;
	}
	select{
		margin-top:10px;
		margin-bottom:10px;
		
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
      <div class="content-wrapper">
				  <div class="box">
					<div class="box-header">
					  <h3 class="box-title">Upload Thesis</h3>
					  
					</div><!-- /.box-header -->
					<div class="box-body">
						<form class='form-group' action='php/upload_thesis.php' method='POST' enctype='multipart/form-data'>
						<label>Title</label>
						<input type='text' name='title' class='form-control' required>
						<label>Campus</label>
						<select name='campus' class='form-control' required>
							<option value=''>Select Campus</option>
							<?php
							
								$camp_qr = $db -> prepare ("SELECT campus,campus_id FROM campus_tbl ORDER BY campus ASC");
								$camp_qr -> execute();
								while($r_camp_qr = $camp_qr -> fetch(PDO::FETCH_ASSOC)){
									$campus = $r_camp_qr['campus'];
							?>

								<option value='<?php echo $campus;?>'><?php echo $campus;?></option>
							<?php
								}
							
							?>
						</select>
						<label>Department</label>
						<select name='dept' id='select_dept' class='form-control' required>
							<option value=''>Select Department</option>
							<?php
								$dept_qr = $db -> prepare ("SELECT department,department_id FROM department_tbl");
								$dept_qr -> execute();
								while($r_dept_qr = $dept_qr -> fetch(PDO::FETCH_ASSOC)){
									$dept = $r_dept_qr['department'];
									$did = $r_dept_qr['department_id'];
							?>
							
							<option value='<?php echo $dept;?>'><?php echo $dept;?></option>
							
							<?php
								}
							?>
						</select>
						<span id='span_select_course'>
						<label>Course</label>
						<select class='form-control' id='select_course' required>
							<option value=''>Select Course</option>
						</select>
						</span>
						<label>Type</label>
						<select name='type' class='form-control' required>
							<option value=''>Select Type</option>
							<option value='Abstract'>Abstract</option>
							<option value='Thesis'>Complete Thesis</option>
						</select>
						<label>Year</label>
						<select name='year' class='form-control' required>
							<?php
								$year = date("Y");
								$limit_year = $year - 6;
							?>
								<option value='<?php echo $year;?>'><?php echo $year;?></option>
								<?php
									for($x = $year;$x >= $limit_year;$x--){
						
								?>
									<option value='<?php echo $x;?>'><?php echo $x;?></option>
								
								<?php
									}
								?>
						</select>
						<input class='form-contrl btn' id='thesis_file' type='file' name='thesis' accept="application/pdf" required>
						
						<input type='submit' name='submit_thesis' id='thesis_button' class='btn btn-info form-control' value='Upload'>
						</form>
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
