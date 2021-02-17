<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");
	$adminid = adminLog();
				?>
<html>
  <head>
  
    <meta charset="utf-8"-body>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Homepage</title>
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
	
	<link rel="stylesheet" href="css/jquery-ui.css">

	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>

    <script src="js/jquery-ui.min.js"></script>
	<script>
		
		$(document).ready(function(){
			$(".admin_id").on('click', function(){
				var id = $(this).attr("id");
				$("#message_container").load('display_admin_message.php?aid=' + id);
					
			});
		});
		//
	
		//
	</script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
		ul
		{
			list-style-type: none;
		}
		.admin-link
		{
			color:black;
		}
		.admin-link:hover
		{
			color:blue;
			font-size:19px;
			margin-left:4px;
		}
		#admin_list
		{
			overflow:hidden;
		}
		#admin_list:hover
		{
			overflow-y:scroll;
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
		<div class="content-wrapper" >
			<div style="background-color:#DCDCDC;" class="box">
				<div class="box-header">
					<h3 class="box-title"><b><img src="image/lspulogo.PNG" title="LSPU" alt="logo" width="25" height="25" border="0">
					 Admin Message Page</b>
					</h3>
				</div><!-- /.box-header -->
					<div style="background-color:#DCDCDC;height:640px;" class="box-body">
						<div id='whole_container'>
							<div id='admin_list' style='height:200px;' class='col-md-3'>
								<ul>
								<?php
									$get_admin_list = $db -> prepare ("
										SELECT admin_id as aid, campus, department, position, email, fname, lname 
										FROM admin_tbl
										WHERE admin_id != :aid 
										AND status='Active' ORDER BY lname ASC");
									$get_admin_list -> bindParam (":aid", $aid);
									$get_admin_list -> execute();
									while($admin_row = $get_admin_list -> fetch(PDO::FETCH_ASSOC)){
										$aid = $admin_row['aid'];
										$fname = $admin_row['fname'];
										$lname = $admin_row['lname'];
										
								?>
									<li style='color:black;font-size:16px;margin-top:10px;margin-bottom:5px;' id='<?php echo $aid;?>' class='admin_id'><a href='#' class='admin-link'><?php echo $fname." ".$lname;?></a></li>
								<?php
									}
								?>
								</ul>
							</div>
							<div id='message_container' class='col-md-8' style='margin-left:90px;height:300px;'>
							</div>
						</div>
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

    <!-- jQuery 2.1.4 -->
    <!-- jQuery UI 1.11.4 
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
