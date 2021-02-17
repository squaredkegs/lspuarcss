<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");

	$adminid = adminLog();
	$year = '';
	$month = '';
	//if(!isset($_GET['year'])){
	
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SMNP - Admin | Student Log Information</title>
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
	</head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      
      	<?php 
				include ("navbar.php");
				include_once ("sidebar.php");
			?>
		
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
				  <div class="box">
					<div class="box-header">
					  <h3 class="box-title" style='margin-bottom:15px;'>Student Log</h3>
						<form action='php/filter_stud_logtime.php' method='post' class='form-inline'>
							<?php
									$get_max_year = $db -> prepare ("SELECT year(MAX(logging_in)) as max_year FROM stud_logtime");
									$get_max_year -> execute();
									$res_gt_mx_yr = $get_max_year -> fetch();
									$max_year = $res_gt_mx_yr ['max_year'];
									$get_min_year = $db -> prepare ("SELECT year(MIN(logging_in)) as min_year FROM stud_logtime");
									$get_min_year -> execute();
									$res_gt_mn_yr = $get_min_year -> fetch();
									$min_year = $res_gt_mn_yr ['min_year'];
								?>
							<select name='year' class='form-control' required>
								<option value=''>Year</option>
								<?php
								for($x = $max_year;$x>=$min_year;--$x){
										
									
								?>
								<option><?php echo $x;?></option>
								<?php
								}
								?>
							</select>
							<select name='month' class='form-control'>
								<option value=''>Select Month</option>
								<option value='01'>January</option>
								<option value='02'>February</option>
								<option value='03'>March</option>
								<option value='04'>April</option>
								<option value='05'>May</option>
								<option value='06'>June</option>
								<option value='07'>July</option>
								<option value='08'>August</option>
								<option value='09'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							</select>
							<input type='submit' name='filter_stud_logtime' value='Filter' class='form-control btn btn-info'>
						</form>
					</div><!-- /.box-header -->
					<div class="box-body">
					  <!--Remove id="example one on table-->
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
						  <tr>
							<th>Account Name</th>
							<th>Campus</th>
							<th>Log-In</th>
							<th>Log-out</th>
							<th>Total Hours</th>
							
						</tr>
						</thead>
						<tbody>
						
							<?php
							if(!isset($_GET['year'])){
							$query = $db -> prepare ("SELECT stud_bas.fname as fname, stud_bas.lname as lname, stud_bas.department as dept, stud_bas.campus as campus, stud_bas.stud_id as sid,stud_bas.course as course, stud_logtime.logging_in as login,  stud_logtime.logging_out as logout, time(stud_logtime.logging_in) as hour_login, time(stud_logtime.logging_out) as hour_logout FROM stud_logtime
							INNER JOIN
							stud_bas
							ON stud_bas.stud_id = stud_logtime.stud_id
							ORDER BY stud_logtime.logging_in DESC");
							}
							else if(isset($_GET['year']) && !isset($_GET['month'])){
							$year = $_GET['year'];
							$query = $db -> prepare ("SELECT stud_bas.fname as fname, stud_bas.lname as lname, stud_bas.department as dept, stud_bas.campus as campus, stud_bas.stud_id as sid,stud_bas.course as course, stud_logtime.logging_in as login,  stud_logtime.logging_out as logout, time(stud_logtime.logging_in) as hour_login, time(stud_logtime.logging_out) as hour_logout FROM stud_logtime
							INNER JOIN
							stud_bas
							ON stud_bas.stud_id = stud_logtime.stud_id
							WHERE year(stud_logtime.logging_in) = :year
                            ORDER BY stud_logtime.logging_in DESC
							");
							$query -> bindParam (":year", $year);	
							}
							else if(isset($_GET['month']) && isset($_GET['year'])){
							$year = $_GET['year'];
							$month = $_GET['month'];
							$query = $db -> prepare ("SELECT stud_bas.fname as fname, stud_bas.lname as lname, stud_bas.department as dept, stud_bas.campus as campus, stud_bas.stud_id as sid,stud_bas.course as course, stud_logtime.logging_in as login,  stud_logtime.logging_out as logout, time(stud_logtime.logging_in) as hour_login, time(stud_logtime.logging_out) as hour_logout FROM stud_logtime
							INNER JOIN
							stud_bas
							ON stud_bas.stud_id = stud_logtime.stud_id
							WHERE year(stud_logtime.logging_in) = :year
                            AND month(stud_logtime.logging_in) = :month
							ORDER BY stud_logtime.logging_in DESC
							");	
							$query -> bindParam (":year", $year);
							$query -> bindParam (":month", $month);
							}
							$query -> execute();
							$numrow = $query -> rowCount();
							while($row = $query -> fetch(PDO::FETCH_ASSOC)){
								$fname = $row['fname'];
								$lname = $row['lname'];
								$campus = $row['campus'];
								$dept = $row['dept'];
								$course = $row['course'];
								$login = $row['login'];
								$logout = $row['logout'];
								$hour_login = $row['hour_login'];
								$hour_logout = $row['hour_logout'];
								$login = date("F d, Y h:i (A)", strtotime($login));
								$display_logout = date("F d, Y h:i (A)", strtotime ($logout));
								$in = strtotime($hour_login);
								$out = strtotime($hour_logout);
								$timelog = ($out - $in)/3600;
								$hour = floor($timelog);
								$minute = floor(($timelog - floor($timelog)) * 60);
								$second = floor(($timelog - floor($timelog)) * 3600 );	
								$second = substr($second, 0, 2);

							?>		
						
						  <tr>
							<td>
								<a href="student_account?detail=<?php echo $sid;?>">
								<?php echo $fname." ".$lname;?></a>
							</td>
							<td><?php echo $campus;?></td>
							<td><?php echo $login;?></td>
							<td><?php
			
								if($logout!="0000-00-00 00:00:00"){
									echo $display_logout;
								}
								else{
									echo "Not recorded";
								}
								?>
							</td>
							<td>
								<?php 
								if($logout!="0000-00-00 00:00:00"){
									if($hour>0){
										echo $hour." Hour/s & ".$minute." Minute/s";
									}
									else if($minute>0)
									{
										echo $minute." Minute/s";
									}
									else if($second>0)
									{
										echo $second." Second/s";
									}
								}
								else{
									echo "N/A";
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
							<th>Account Name</th>
							<th>Campus</th>
							<th>Log-In</th>
							<th>Log-out</th>
							<th>Total Hours</th>
						</tr>
						</tfoot>
					  </table>
					</div><!-- /.box-body -->
				  </div>
		</div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.3.0
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved.
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
        //$("#example1").DataTable();
        $('#example1').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": false,
          "info": true,
          "autoWidth": false,
		  "order": [[3, "desc"]]
 		  
			});
      });
		</script>
</body>
</html>
