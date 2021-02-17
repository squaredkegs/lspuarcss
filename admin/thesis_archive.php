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
    <title>Admin | Thesis Archive</title>
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
		
	
	</script>
	
	</head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

		<?php
			
			include_once ("navbar.php");
			include_once ("sidebar.php");
		
		?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" >
				  <div class="box">
					<div class="box-header">
					<h3 class="box-title" style="font-family:sans-serif;">
						<img src="image/sa.ico" title="LSPU" alt="logo" width="20" height="20" border="0" style="margin-bottom:5px;">
						Thesis Archive for <?php echo $rdepartment;?> of <?php echo $rcampus;?></h3>
					</div><!-- /.box-header -->
					<div class="box-body">
					  <!--Remove id="example one on table-->
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
						  <tr>
							<th>Thesis Title</th>
							<th>Campus</th>
							<th>Department</th>
							<th>Course</th>
							<th>Year</th>
							<th>Type</th>
						  </tr>
						</thead>
							<?php
								$th_qr = $db -> prepare ("SELECT * FROM thesis_arch WHERE campus = :campus AND department = :dept");
								$th_qr -> bindParam (":campus", $rcampus);
								$th_qr -> bindParam (":dept", $rdepartment);
								$th_qr -> execute();
								while($r_th_qr = $th_qr -> fetch(PDO::FETCH_ASSOC)){
									$course = $r_th_qr['course'];
									$department = $r_th_qr['department'];
									$year = $r_th_qr['year'];
									$title = $r_th_qr['title'];
									$thid = $r_th_qr['thesis_id'];
									$upload_date = $r_th_qr['upload_date'];
									$campus = $r_th_qr['campus'];
									$filepath = $r_th_qr['filepath'];
									$abstract_filename = $r_th_qr['abstract_filename'];
									$complete_filename = $r_th_qr['complete_filename'];
									if($complete_filename==""){
										$type = "Abstract Only";
									}
									else if($abstract_filename==""){
										$type = "Complete Only";
									}
									else{
										$type = "Both";
										
									}
									
							?>
						<tbody>
							<td><a href='view_thesis?thid=<?php echo $thid;?>'><button class='btn btn-info'><?php echo $title;?></button></a></td>
							<td><?php echo $campus;?></td>
							<td><?php echo $department;?></td>
							<td><?php echo $course;?></td>
							<td><?php echo $year;?></td>
							<td><?php echo $type;?></td>
							<td><a href=''></a></td>
						</tbody>
							<?php
								}
							?>
						<tfoot>
						  <tr>
							<th>Thesis Title</th>
							<th>Campus</th>
							<th>Department</th>
							<th>Course</th>
							<th>Year</th>
							<th>Type</th>
						 </tr>
						</tfoot>
					  </table>
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
