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
		$(".session_time").click(function(){
			<?php
				$_SESSION['LAST_ACTIVITY'] = time();
			?>
		});
	});
		
	</script>
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
					  <h3 class="box-title"style="font-family:sans-serif;">
						<img src="image/act.ico" title="LSPU" alt="logo" width="20" height="20" border="0" style="margin-bottom:5px;">
						Waiting For Verification </h3>
					</div><!-- /.box-header -->
					<div class="box-body">
					  <!--Remove id="example one on table-->
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
						  <tr>
							<?php 
								$query = $db -> prepare ("SELECT admin_tbl.campus as campus, admin_tbl.department as dept, admin_tbl.fname as fname, admin_tbl.lname as lname, admin_activity.object_id as oid, admin_activity.activity as activity, admin_activity.stat_date as datetime, admin_activity.object as object, admin_activity.admin_id as aid FROM
								admin_tbl
								RIGHT JOIN admin_activity
								ON admin_activity.admin_id = admin_tbl.admin_id
								ORDER BY admin_activity.stat_date DESC;
								");
								$query -> execute();
									
							?>
							<th>Admin Name</th>
							<th>Campus/Department</th>
							<th>Action</th>
							<th>Object</th>
							<th>Nothing Yet</th>
							<th id="example2">Date/Time Performed</th>
							
							</tr>
						</thead>
						<tbody>
						
							<?php
							
							while($row = $query -> fetch(PDO::FETCH_ASSOC)){
								$fname = $row['fname'];
								$lname = $row['lname'];
								$campus = $row['campus'];
								$dept = $row['dept'];
								$activity = $row['activity'];
								$object = $row['object'];
								$datetime_activity = $row['datetime'];
								$oid = $row['oid'];
								$datetime_activity = date ("F d, Y (h:i A)", strtotime ($datetime_activity));
								$aid = $row['aid'];
							?>		
						
						  <tr>
							<td>
								<a class="session_time"href="admin_detail.php?detail=<?php echo $aid;?>">
								<?php echo $fname." ".$lname;?></a>
							</td>
							<td><?php echo $campus." ".$dept;?></td>
							<td><?php echo $activity;?></td>
							<td><?php echo $object;?></td>
							<td>
								<?php
								if($object=='Student'){
									if($activity=='Banned' || $activity=='Remove Ban'){
										$banned_query = $db -> prepare ("SELECT stud_bas.fname as fname, stud_bas.lname as lname
										FROM ban_connect
										LEFT JOIN banned_tbl
										ON
										banned_tbl.banned_id = ban_connect.banned_id
										LEFT JOIN
										stud_bas
										ON stud_bas.stud_id = ban_connect.stud_id
										WHERE banned_tbl.banned_id=:oid");
										$banned_query -> bindParam (":oid", $oid);
										$banned_query -> execute();
										$banned_query_fetch = $banned_query -> fetch();
										$ban_fname = $banned_query_fetch['fname'];
										$ban_lname = $banned_query_fetch['lname'];
										$full_ban_name = $ban_fname." ".$ban_lname;
										echo $full_ban_name;
										
										
									}
									else{
										$get_object = $db -> prepare ("SELECT fname, lname FROM stud_bas WHERE stud_id=:oid");
										$get_object -> bindParam (":oid", $oid);
										$get_object -> execute();
										$get_numrow = $get_object -> rowCount();
										if($get_numrow>0){
										$result = $get_object -> fetch();
										$fname = $result['fname'];
										$lname = $result['lname'];
										$fullname = $fname." ".$lname;
										echo $fullname;
										}
										else{
										echo "<b>[Data no longer exists]</b>";
										}
									}
								}
								else if($object=='Post'){
									$get_object = $db -> prepare ("SELECT title FROM newsfeed WHERE news_id=:oid");
									$get_object -> bindParam (":oid", $oid);
									$get_object -> execute();
									$get_numrow = $get_object -> rowCount();
									if($get_numrow>0){
									$result = $get_object -> fetch();
									$title = $result['title'];
									echo $title;
									}
									else{
									echo "<b>[Data no longer exists]</b>";
									}
									
								}
								if($object=='Course'){
									$get_object = $db -> prepare ("SELECT course FROM course_tbl WHERE course_id=:oid");
									$get_object -> bindParam (":oid", $oid);
									$get_object -> execute();
									$get_numrow = $get_object -> rowCount();
									if($get_numrow>0){
									$result = $get_object -> fetch();
									$course = $result['course'];
									echo $course;
									}
									else{
									echo "<b>[Data no longer exists]</b>";
									}
								}
								if($object=='Department'){
									$get_object = $db -> prepare ("SELECT department FROM department_tbl WHERE department_id=:oid");
									$get_object -> bindParam (":oid", $oid);
									$get_object -> execute();
									$get_numrow = $get_object -> rowCount();
									if($get_numrow>0){
									$result = $get_object -> fetch();
									$department = $result['department'];
									echo $department;
									}
									else{
									echo "<b>[Data no longer exists]</b>";
									}
								}
								if($object=='Admin' && $activity=='Create New Admin'){
									$get_object = $db -> prepare ("SELECT fname,lname FROM admin_tbl WHERE admin_id=:oid");
									$get_object -> bindParam (":oid", $oid);
									$get_object -> execute();
									$get_numrow = $get_object -> rowCount();
									
									if($get_numrow>0){
									$result = $get_object -> fetch();
									$fname = $result['fname'];
									$lname = $result['lname'];
									$fullname = $fname." ".$lname;
									echo $fullname;
									}
									else{
									echo "<b>[Data no longer exists]</b>";
									}
									echo "<br/>";
								}
								if($object=='Admin'){
									$result = $db -> prepare ("SELECT fname,lname,accnt_expire FROM admin_tbl WHERE admin_id=:oid");
									$result -> bindParam (":oid", $oid);
									$result -> execute();
									$fetch = $result -> fetch();
									$numrow = $result -> rowCount();
									if($get_numrow>0){
									$adfname = $fetch['fname'];
									$adlname = $fetch['lname'];
									$account_expire = $fetch['accnt_expire'];
									$adfullname = $adfname." ".$adlname;
										if($activity=='Create New Admin'){
										//echo $adfullname;
										}
										else if($activity=='Change Account Date Expiration' || $activity=='Extended Account Date'){
										$account_expire = date("F d, Y", strtotime($account_expire));	
										echo $fullname;
										//echo $oid;
										echo "<br/>";
										echo $account_expire;
										
										}
									}
									else{
									echo "<b>[<b>[Data no longer exists]</b>]</b>";
									}
								}
								?>
							</td>
							<td><?php echo $datetime_activity;?></td>
						  </tr>
						  <?php
						  }
						  ?>
						</tbody>
						<tfoot>
						  <tr>
							<th>Admin Name</th>
							<th>Campus/Department</th>
							<th>Action</th>
							<th>Object</th>
							<th>Nothing Yet</th>
							<th id="example2">Date/Time Performed</th>
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
        //$("#example1").DataTable();
        $('#example1').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          //"ordering": true,
          "info": true,
          "autoWidth": false,
		  //"order": [[4, "desc"]]
 		  
			});
      });
		</script>
</body>
</html>
