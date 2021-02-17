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
    <title>Admins | Students</title>
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

      <!-- Left side column. contains the logo and sidebar -->
			<?php 
				include_once ("sidebar.php");
				include_once ("navbar.php");
			?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
				  <div class="box">
					<div class="box-header">
					  <h3 class="box-title" style="font-family:sans-serif;">
						<img src="image/news.ico" title="LSPU" alt="logo" width="20" height="20" border="0" style="margin-bottom:5px;">
						Social Media Newsfeed<h3>
					</div><!-- /.box-header -->
					<div class="box-body">
					  <!--Remove id="example one on table-->
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
						  <tr>
						  <?php 
								$result = $db -> query ("SELECT stud_bas.fname as fname, stud_bas.lname as lname, newsfeed.title as title, newsfeed.news_id as nid, stud_bas.stud_id as sid, stud_bas.campus as campus, stud_bas.email as email,
								newsfeed.date_and_time as datentime
								FROM post_connect
								INNER JOIN stud_bas
								ON post_connect.stud_id = stud_bas.stud_id
								INNER JOIN newsfeed
								ON post_connect.news_id = newsfeed.news_id
								WHERE newsfeed.status != 'Remove by Admin'
								ORDER BY newsfeed.date_and_time DESC
								
								");	
							$result -> bindParam (":camp", $admin_campus);
							$result -> execute();
							?>
							<th>Student Name</th>
							<th>Posts</th>
							<th>Reports</th>
							<th>Comments</th>
							<th>Score</th>
							<th id="example2">Date/Time</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($row = $result->fetch(PDO::FETCH_ASSOC))
							{								
								$fname = $row['fname'];
								$lname = $row['lname'];
								$title = $row['title'];
								$nid = $row['nid'];
								$campus = $row['campus'];
								$email = $row['email'];
								$sid = $row['sid'];
								$datentime = $row['datentime'];
								$datentime = date("F d, Y h:i:s A", strtotime($datentime));
									$cmt_qr = $db -> prepare ("SELECT COUNT(comment_connect.cmmt_id) as newcount FROM comment_connect 
									INNER JOIN newsfeed
                                    ON comment_connect.news_id = newsfeed.news_id 
                                    INNER JOIN stud_bas
                                    ON comment_connect.stud_id = stud_bas.stud_id 
                                    INNER JOIN cmmt_sect
									ON comment_connect.cmmt_id = cmmt_sect.cmmt_id
                                    WHERE comment_connect.news_id = :nid
									");
									$cmt_qr -> bindParam(":nid", $nid);
									$cmt_qr -> execute();
									$cmt_res = $cmt_qr -> fetch();
									$numcom = $cmt_res['newcount'];
									$scr_qr = $db -> prepare ("SELECT SUM(score) as realscore FROM upvote_post 
									INNER JOIN newsfeed
									ON newsfeed.news_id = upvote_post.news_id
									INNER JOIN stud_bas
									ON stud_bas.stud_id = upvote_post.stud_id
									WHERE newsfeed.news_id=:nid");
									$scr_qr -> bindParam(":nid", $nid);
									$scr_qr -> execute();
									$scr_res = $scr_qr -> fetch();
									$scrpos = $scr_res['realscore'];
									if(empty($scrpos) || $scrpos == null)
									{
										$scrpos = 0;
									}
						  
									$report_qr = $db -> prepare ("SELECT COUNT(news_id) as report_number FROM report_connect WHERE news_id = :nid");
									$report_qr -> bindParam (":nid", $nid);
									$report_qr -> execute();
									$report_res = $report_qr -> fetch();
									$report_num = $report_res['report_number'];
									
						  
						  ?>
						  
						  <tr>
							<td><a href="student_account.php?studentaccount=<?php echo $sid;?>"><?php echo $fname." ".$lname; ?></a></td>
							<td><?php echo htmlspecialchars($title); ?></td>
							<td>
								
								<?php
								if($report_num>0){
								?>
								<a href='view_users_report?rid=<?php echo $nid;?>'><?php echo $report_num;?></a>
								<?php
								}
								else{
								echo $report_num;
								
								}
								?>
								</a>
							</td>
							<td><?php echo $numcom;?></td>
							<td><?php echo $scrpos;?></td>
							<td><?php echo $datentime;?></td>
							<td>
								<a href="admin_comment.php?cmmntdetail=<?php echo $nid;?>">
								<button class="btn btn-info form-control">Details</button>
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
							<th>Posts</th>
							<th>Reports</th>
							<th>Comments</th>
							<th>Score</th>
							<th>Date/Time</th>
							<th>View Thread</th>
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
          "searching": false,
		 //"ordering": false,
          "info": true,
          "autoWidth": false,
		  "order": [[6, "desc"]]
        });
      });
		</script>
</body>
</html>
