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
	<script src='js/functions.js'></script>
	<?php
	
	
		if(time() - $_SESSION['LAST_ACTIVITY']<1800){
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".student_detail").click(function(){
				 $.ajax({
					type: 'POST',
					url: 'php/last_activity.php',
					cache: false
				 });
			});
		});
		
		$(document).ready(function(){
			$("#lname_filter").on('change', function(){
				var val = $(this).val();
				if(val){
						$("#letter-container").show();
						$.ajax({
							type: 'POST',
							url: 'pages/options/letters.php',
							data:
							{
								val: val,
							},
							cache: false,
							success: function(data){
								$("#letter-container").html(data);
							}
						});
				}
				else{
					$("#letter-container").hide();
				}
			});
		});
		function remoke_access(this_button,e){
			var form = $(this_button).closest("form");
			var name = $(this_button).attr("name");
			var rqid = $(form).find(".rqid").val();
				$.ajax({
					type: 'POST',
					url: 'php/revoke_thesis_access.php',
					cache: false,
					data:
					{
						rqid: rqid,
						name: name,
					},
					success: function(data){
					$(form).html("<input type='hidden' value='" + rqid + "' readonly class='rqid'/><input type='submit' class='btn form-control' style='color:white;background-color:gray;' name='undo' value='Undo' onclick='undo_revoke(this,event);'>");
					},
				});
			e.preventDefault();
			
		}
		
		function undo_revoke(this_button,e){
			var form = $(this_button).closest("form");
			var rqid = $(form).find(".rqid").val();
			var name = $(this_button).attr("name");
			$.ajax({
					type: 'POST',
					url: 'php/revoke_thesis_access.php',
					cache: false,
					data:
					{
						rqid: rqid,
						name: name,
					},
					success: function(data){
						$(form).html("<input type='hidden' value='" + rqid + "' readonly class='rqid'/><input type='submit' value='Revoke Access' name='revoke' class='btn btn-info form-control' onclick='remoke_access(this,event);'>");
					},
				});
			e.preventDefault();
			
		}
	</script>
	
	<?php
		}
	?>
	<style>
		a.student-link{
			color: black;
		}
		a.student-link:hover{
			color: #0000EE;
			text-decoration:underline;
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
				  <div class="box">
					<div class="box-header">
					<h3 class="box-title" style="font-family:sans-serif;">
						<img src="image/sa.ico" title="LSPU" alt="logo" width="20" height="20" border="0" style="margin-bottom:5px;">
						Student Accounts</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
					<!--<form action='php/filter_student_by_lastname_2.php' method='POST' class='form-inline'> 
					  <label style='display:block;'>Select Lastname</label>
					  <select name='filter' id='lname_filter' class='form-control' required>
						<option value=''>Select</option>
						<option value='starting'>Starting With</option>
						<option value='from'>From</option>
					  </select>
						<span id='letter-container'>
						
						</span>
						<input type='submit' name='filter_expired_accounts' value='Filter' class='btn btn-info' style='display:block;margin-top:10px;margin-bottom:5px;'>
						
					</form>
					-->	
						<h4 style='margin-top:4px;margin-bottom:4px;'></h4>
					<!--Remove id="example one on table-->
					<table id="example1" class="table table-bordered table-striped">
						<thead>
						  <tr>
							<th>Student Name</th>
							<th>Campus/Dept</th>
							<th>Status</th>
							<th>Thesis</th>
							<th>Approved Date</th>
							<th>Action</th>
						  </tr>
						</thead>
						<tbody>
						<?php
					$query = $db -> prepare ("SELECT stud_bas.fname as fname, stud_bas.campus as stud_camp, stud_bas.department as stud_dept, stud_bas.lname as lname, request_thesis.status as status, request_thesis.request_approve as approved_date, request_thesis.request_id as rqid, thesis_arch.title as title, thesis_arch.thesis_id as thid FROM request_thesis_connect
					INNER JOIN request_thesis
					ON request_thesis.request_id = request_thesis_connect.request_id
					INNER JOIN stud_bas
					ON stud_bas.stud_id = request_thesis_connect.stud_id
					INNER JOIN thesis_arch
					ON thesis_arch.thesis_id = request_thesis_connect.thesis_id
					WHERE request_thesis.campus = :campus AND request_thesis.department = :dept AND request_thesis.status!='Deleted'");
					$query -> bindParam (":campus", $rcampus);
					$query -> bindParam (":dept", $rdepartment);
					$query -> execute();
						while($row = $query -> fetch(PDO::FETCH_ASSOC)){
							$fname = $row['fname'];
							$lname = $row['lname'];
							$rqid = $row['rqid'];
							$stud_camp = $row['stud_camp'];
							$stud_dept = $row['stud_dept'];
							$fullname = $lname.", ".$fname;
							$status = $row['status'];
							$approved_date = $row['approved_date'];
							$approved_date = date("F d, Y",strtotime($approved_date));
							$title = $row['title'];
							$thid = $row['thid'];
						?>
						<tr>
							<td style='width:200px;'><?php echo $fullname;?></td>
							<td style='width:260px;'><?php echo $stud_camp."/".$stud_dept;?></td>
							<td style='width:50px;'><?php echo $status;?></td>
							<td><a href='view_thesis?thid=<?php echo $thid;?>' class='thesis-link' target='_blank'><?php echo $title;?></a></td>
							<td style='width:100px;'><?php echo $approved_date;?></td>
							<td>
								<form action='#' method='POST' class='form-inline'>
									<input type='hidden' value='<?php echo $rqid;?>' readonly class='rqid'>
									<?php
									if($status=='Approved'){
									?>
									<input type='submit' value='Revoke Access' name='revoke' class='btn btn-info form-control' onclick='remoke_access(this,event);'>
									<?php
									}
									else if($status=='Revoked'){
									?>
									<input type='submit' class='btn form-control' style='color:white;background-color:gray;' name='undo' value='Undo' onclick='undo_revoke(this,event);'>
									<?php
									}
									?>
								</form>
							</td>
						</tr>
						<?php
						}
						?>
						</tbody>
						<tfoot>
						  <tr>
							<th>Student Name</th>
							<th>Campus/Dept</th>
							<th>Status</th>
							<th>Thesis</th>
							<th>Approved Date</th>
							<th>Action</th>
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
