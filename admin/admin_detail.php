<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");

	$adminid = adminLog();
	if(isset($_GET['detail']))
	{
		$schid = $_GET['detail'];
		$query = $db -> prepare ("SELECT * FROM admin_tbl WHERE admin_id = :aid");
		$query -> bindParam (":aid", $schid);
		$query -> execute();
		$numrow = $query -> rowCount();
		if($numrow==1)
		{
			$result = $query -> fetch();
			$aaid = $result['admin_id'];
			$aposition = $result ['position'];
			$apassword = $result ['password'];
			$acampus = $result ['campus'];
			$aadmin_account = $result['admin_account'];
			$aemail = $result ['email'];
			$afname = $result['fname'];
			$alname = $result['lname'];
			$aaccnt_expire = $result['accnt_expire'];
		}
		else
		{
			header("location:school_admin.php");
		}
	}
	else
	{
		header("location:school_admin.php");
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SMNP | Homepage</title>
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
    <link rel="stylesheet" href="css/student_account.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
	
	<link rel="stylesheet" href="css/jquery-ui.css">

	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>

    <script src="js/jquery-ui.min.js"></script>

	<script type="text/javascript">

	$(document).ready(function(){
		$("#select-info").on("change", function(){
			switch( this.value)
			{
				case "main-information":
				document.getElementById("admin-information").style.display = 'inline';
				document.getElementById("timelog-information").style.display = 'none';
				document.getElementById("account-information").style.display = 'none';
				break;
				case "log-information":
				document.getElementById("admin-information").style.display = 'none';
				document.getElementById("timelog-information").style.display = 'inline';
				document.getElementById("account-information").style.display = 'none';
				break;
				case "account-settings":
				document.getElementById("account-information").style.display = 'inline';
				document.getElementById("admin-information").style.display = 'none';
				document.getElementById("timelog-information").style.display = 'none';
				break;
			}
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
      <div class="content-wrapper">
				  <div class="box">
					<div class="box-header">
					  <h3 class="box-title">Information of Admin <?php echo $afname." ".$alname;?></h3>
						<div class="form-inline" style="margin-top:15px;">
						<select class="form-control" name="info-filter" id="select-info">
							<option value="">Select Information</option>
							<option value="main-information">Main Information</option>
							<?php
							
								if($rposition=="Main Admin"){
							
							?>
								
							<option value="log-information">Log-Time</option>
							<option value="comment-information">Ban List</option>
							<option value="account-settings">Account Settings</option>
							<?php
								}
							?>
							<!--<option value="">Pictures</option>
							<option value="">Friends</option>
							<option value="">Upvote</option>-->
						</select>
						
						
						</div>
					</div><!-- /.box-header -->
					<div class="box-body">
						
				<div id="admin-information" style="display:none;">
				<?php
					if($rposition=="Main Admin"){
				?>
				<label style="font-size:20px;display:block;">Email</label>
				<span style="margin-top:12px;font-size:17px;" class="form-control"><?php echo $aemail;?></span>
				<label style="font-size:20px;display:block;margin-top:10px;">Account</label>
				
				<span style="margin-top:7px;font-size:17px;" class="form-control"><?php echo $aadmin_account;?></span>
				<form action="php/admin_change.php" method="POST">
					<input type='hidden' name='aid' value='<?php echo $aaid;?>' readonly>
					<label>Campus</label>
					<select class="form-control" style="margin-top:20px;margin-bottom:10px;" name='campus'>
						<option value="<?php echo $acampus;?>"><?php echo $acampus;?></option>
						<?php
							$camp_quer = $db -> prepare ("SELECT campus as campus FROM campus_tbl WHERE campus_id NOT IN (campus = :campus) ORDER BY campus ASC");
							$camp_quer -> bindParam (":campus", $acampus);
							$camp_quer -> execute();
							while($cam_row = $camp_quer -> fetch(PDO::FETCH_ASSOC))
							{
								$newcamp = $cam_row['campus'];
						?>	
								<option value="<?php echo $newcamp;?>"><?php echo $newcamp;?></option>
						<?php
							}
			
						?>
					</select>
					<label>Position</label>
					<select class="form-control" style="margin-top:20px;margin-bottom:10px;" name='position'>
							<option value="<?php echo $aposition;?>"><?php echo $aposition;?></option>
							<?php
								if($aposition=='School Admin'){
							?>
							<option value="Main Admin">Main Admin</option>
							<?php
							}
							else{
							?>
							<option value="School Admin">School Admin</option>
							
							<?php
							}
							?>
					</select>
					<input type="submit" name="submit_admin_change" value="Save Changes" class="btn btn-info form-control">
				</form>
				
					<?php
					}
					else {
				?>
				<label style="font-size:20px;display:block;">Email</label>
				<span style="margin-top:12px;font-size:17px;" class="form-control"><?php echo $aemail;?></span>
				<label style="font-size:20px;display:block;margin-top:10px;">Account</label>
				
				<span style="margin-top:7px;font-size:17px;" class="form-control"><?php echo $aadmin_account;?></span>
				<label style="font-size:20px;display:block;">Campus</label>
				<span style="margin-top:12px;font-size:17px;" class="form-control"><?php echo $acampus;?></span>
				<label style="font-size:20px;display:block;">Position</label>
				<span style="margin-top:12px;font-size:17px;" class="form-control"><?php echo $aposition;?></span>
					
					<?php
					}
					?>
					
				</div>
								
						<!--Others-->
				<div id="timelog-information" style="display:none;">
				<table id="example" class="table table-bordered table-striped unique-class-table1">
						<thead>
						  <tr>
							<th>Date</th>
							<th>Time-In</th>
							<th>Time-Out</th>
							<th>Time-Logged (Estimate)</th>
						</tr>
						</thead>
						<tbody>
							<?php
								$t_query = $db -> prepare ("SELECT date(datetime_in) as date,time(datetime_in) as time_in, time(datetime_out) as time_out,log_id as lid FROM admin_logtime WHERE admin_id=:aid");
								$t_query -> bindParam (":aid", $schid);
								$t_query -> execute();
								while($trow = $t_query -> fetch(PDO::FETCH_ASSOC))
								{
									$datelog = $trow['date'];
									$datelog = date("F d(l), Y", strtotime($datelog));
									$time_in = $trow['time_in'];
									$time_out = $trow['time_out'];
									$lid = $trow['lid'];
									$in = strtotime($time_in);
									$out = strtotime($time_out);
									$new_in = date("h:i:s A", strtotime($time_in));
									$new_out = date("h:i:s A", strtotime($time_out));
									$timelog = ($out - $in)/3600;
									$hour = floor($timelog);
									$minute = floor(($timelog - floor($timelog)) * 60);
									$second = floor(($timelog - floor($timelog)) * 3600 );		
									$second = substr($second, 0, 2);
							?>
						  <tr>
							<td><?php echo $datelog;?></td>
							<td><?php echo $new_in;?></td>
							<td><?php echo $new_out;?></td>
							<td>
								<?php 
								
									if($hour>0){
										echo $hour." Hours & ".$minute." Minutes";
									}
									else if($minute>0)
									{
										echo $minute." Minutes";
									}
									else if($second>0)
									{
										echo $second." Seconds";
									}
								?></td>
							</tr>
						<?php
								
								}
							?>
						</tbody>
						<tfoot>
						  <tr>
							<th>Date</th>
							<th>Time-In</th>
							<th>Time-Out</th>
							<th>Time-Logged</th>
							</tr>
						</tfoot>
					  </table>
				
					</div>  
						<!--Others-->						
					<div id="account-information" style="display:none;">
						<form action="php/account_expire" method="POST">
							<label style="display:block;">Account Expiration Date</label>
							<?php
							$date_format_accnt_expire = date("F d, Y (h:i) A", strtotime($aaccnt_expire));
							
							?>
							<span><?php echo $date_format_accnt_expire;?></span>
							<input type="hidden" name="old_expire" value="<?php echo $aaccnt_expire
							;?>" class="form-control">
							
							<input type="hidden" name="schid" value="<?php echo $schid;?>">
							<input type="date" class="form-control" name="new_expire">
							<input type="submit" class="form-control btn btn-info" name="submit" value="Save New Date" style="margin-top:20px;">
							
						</form>
					</div>
						</div><!-- Box Body-->
					  
					</div>
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
		<script>
      $(function () {
        $(".unique-class-table1").DataTable();
        $(".unique-class-table1").DataTable();      
		$('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
		  "order": [[3, "desc"]]
        });
      });
		</script>
</body>
</html>
