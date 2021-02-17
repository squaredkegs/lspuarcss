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
					<form action='php/filter_student_by_lastname_2.php' method='POST' class='form-inline'> 
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
						
						<?php
							if(isset($_GET['flname'])){
						?>
							<a href='existingaccounts' style='margin-top:10px;margin-bottom:10px;'><button class='btn btn-info'>Clear Filter</button></a>
						<?php
							}
						?>
						<h4 style='margin-top:4px;margin-bottom:4px;'></h4>
					<!--Remove id="example one on table-->
					<table id="example1" class="table table-bordered table-striped">
						<thead>
						  <tr>
							<th>Student Name</th>
							<th>School I.D.</th>
							<th>Campus</th>
							<th>Department</th>
							<th>Course</th>
							<th>Status</th>
							<th>Detail</th>
						  </tr>
						</thead>
						<tbody>
						  <?php 
					if($rposition=="Main Admin")
					{
					$result = $db -> query ("SELECT * FROM stud_bas WHERE status!='Pending'");	
					}
					else if($rposition="School Admin")
					{
						if(!isset($_GET['flname'])){
							
						
						$result = $db -> prepare("SELECT * FROM stud_bas WHERE campus=:campus AND department = :department AND status!='Pending'");
						}
						else if(isset($_GET['flname'])){
							$first_letter = $_GET['flname'];
							$second_letter = $first_letter;
							if(isset($_GET['slname'])){
							$second_letter = $_GET['slname'];
							}
						$result = $db -> prepare("SELECT * FROM stud_bas WHERE campus=:campus AND department = :department AND status!='Pending' AND (lname >= :first_letter AND lname < char(ascii(:second_letter) + 1)) ORDER BY lname ASC");
						$result -> bindParam (":first_letter", $first_letter);
						$result -> bindParam (":second_letter", $second_letter);
						}
						$result -> bindParam (":campus", $rcampus);
						$result -> bindParam (":department", $rdepartment);
						$result -> execute();

					}
							while ($row = $result->fetch(PDO::FETCH_ASSOC))
							{								
								$fname = $row['fname'];
								$lname = $row['lname'];
								$sc_id = $row['school_id'];
								$sid = $row['stud_id'];
								$campus = $row['campus'];
								$stat = $row['status'];
								$department = $row['department'];
								$course = $row['course'];

						  ?>
						  <tr>
							<td><a href='student_account?studentaccount=<?php echo $sid;?>' style='' class='student-link'><?php echo $lname.",  ".$fname; ?></a></td>
							<td><?php echo $sc_id; ?></td>
							<td><?php echo $campus;?></td>
							<td><?php echo $department;?></td>
							<td><?php echo $course;?></td>
							<td><?php echo $stat; ?></td>
							<td>
							<a  href="student_account?studentaccount=<?php echo $sid;?>">
							<button class="btn btn-info form-group student_detail">View Detail</button>
							</a>
							
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
							<th>Status</th>
							<th>Campus</th>
							<th>Department</th>
							<th>Course</th>
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
