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
    <title>Admin | Students</title>
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
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
 
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
	

		
	</script>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      
      	<?php 
			include_once ("sidebar.php");
			include_once ("navbar.php");
		?>
		
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" style="background-color:#DCDCDC;">
				  <div class="box"  >
					<div class="box-header">
					  <h3 class="box-title" style="font-family:sans-serif;">
						<img src="image/pending.ico" title="LSPU" alt="logo" width="20" height="20" border="0" style="margin-bottom:5px;">
						Waiting For Verification 
					  (<?php if($rposition!="Main Admin"){echo ($rcampus);}?> Campus)</h3>
					</div><!-- /.box-header -->
					<div class="box-body" >
					  <!--Remove id="example one on table-->
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
						  <tr >
							<th>Student Name</th>
							<th>School I.D.</th>
							<?php if($rposition=="Main Admin"){?>
							<th>Campus</th>
							<?php } ?><th>Department</th>
							<th id="example2">Date/Time</th>
							<th>Detail</th>
						  </tr>
						</thead>
						<tbody >
						  <?php 
							
							
							if($rposition=="Main Admin")
							{
							$result = $db -> query("SELECT * FROM stud_bas WHERE status = 'Pending' ORDER BY timereg ASC");
							}
							else
							{
							$result = $db -> prepare("SELECT * FROM stud_bas WHERE status = 'Pending' AND campus=:campus AND department=:dept ORDER BY timereg ASC");
							$result -> bindParam (":campus", $admin_campus);
							$result -> bindParam (":dept", $rdepartment);

							$result -> execute();
							}
							
							
						
							while ($row = $result->fetch(PDO::FETCH_ASSOC))
							{								
								$fname = $row['fname'];
								$date = $row['timereg'];
								$date = date("F d, Y h:i:s", strtotime($date));
								$lname = $row['lname'];
								$sc_id = $row['school_id'];
								$campus = $row['campus'];
								$stat = $row['status'];
								$studid = $row['stud_id'];

						  ?>
						  <tr>
							<td><?php echo $fname." ".$lname; ?></td>
							<td><?php echo $sc_id; ?></td>
							
							<?php 
								if($rposition=="Main Admin"){?>
							
							<td><?php echo $campus; ?></td>
							<?php } ?>
							<td>Empty Muna</td>
							<td><?php echo $date; ?></td>
							<td>
								<?php
								if($rposition==="School Admin"){
								?>
									<form class="" method="POST" action="php/pending_exec.php">
									<input type="hidden" name="studid" value="<?php echo $studid; ?>">
									<input type="submit" name="accept" id="accept_button" value="Accept" class="btn btn-info form-control"
									style="width:75px;">
									<input type="submit" name="reject" value="Reject" class="btn btn-danger form-control"
									style="width:75px;">
									</form>
								<?php
								}
								else if($rposition=="Main Admin"){
									echo "Requesting Confirmation";
								}
								?>
							</td>
							
						  </tr>
						  <?php
							}

						  ?>
						</tbody>
						<tfoot>
						  <tr>
							<th>Student Name</th>
							<th>School I.D.</th>
							<?php if($rposition=="Main Admin"){?>
							<th>Campus</th>
							<?php } ?>
							<th>Department</th>
							<th>Date/Time</th>
							<th>Detail</th>
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

    <!-- jQuery 2.1.4 -->
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
        //$("#example1").DataTable();
        $('#example1').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          //"ordering": true,
          "info": true,
          "autoWidth": false,
		  "order": [[4, "desc"]]
 		  
			});
      });
		</script>
</body>
</html>
