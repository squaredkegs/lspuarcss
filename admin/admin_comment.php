<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");
	//session_start();
	$adminid = adminLog();
	if(isset($_GET['cmmntdetail']))
	{
		$nid = $_GET['cmmntdetail'];
		$query = $db -> prepare ("
					SELECT campus,category,news_id,title,date_and_time,description,category, department, course 
					FROM newsfeed WHERE news_id = :nid");
		$query -> bindParam (":nid", $nid);
		$query -> execute();
		$numrow = $query -> rowCount();
		$getresult = $query -> fetch();
		$postdate = $getresult['date_and_time'];
		$postdate = date("F d, Y H:i (A)", strtotime($postdate));
		$description = $getresult['description'];
		$title = $getresult['title'];
		$department = $getresult['department'];
		$course = $getresult['course'];
		if($course==""){
			$course = "None";
		}
		$postcampus = $getresult['campus'];
		$postcategory = $getresult['category'];
		if($numrow>0)
		{
			$result = $query -> fetch();
			$get_sid = $db -> prepare ("SELECT stud_bas.stud_id as sid, stud_bas.fname as fname, stud_bas.lname as lname
			FROM post_connect 
							INNER JOIN newsfeed
							ON newsfeed.news_id = post_connect.news_id
							INNER JOIN stud_bas
							ON stud_bas.stud_id = post_connect.stud_id
							WHERE newsfeed.news_id = :nid");
			$get_sid -> bindParam(":nid", $nid);
			$get_sid -> execute();
			$resres = $get_sid -> fetch();
			$sid = $resres['sid'];
			$post_fname = $resres['fname'];
			$post_lname = $resres['lname'];
			$report_qr = $db -> prepare ("
						SELECT COUNT(news_id) as count_nid 
						FROM report_connect 
						LEFT JOIN report
						ON report.report_id = report_connect.report_id
						WHERE report_connect.news_id = :nid");
			$report_qr -> bindParam (":nid", $nid);
			$report_qr -> execute();
			$r_count = $report_qr -> rowCount();
			$res_report = $report_qr -> fetch();
			$no_report = $res_report['count_nid'];
			if($r_count==0){
				$no_report = 0;
			}
		}
		else
		{
			header("location:admin_newsfeed.php");
		}
	}
	else
	{
		header("location:admin_newsfeed.php");
	}
?>
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
      <div class="content-wrapper">
				  <div class="box">
					<div class="box-header">
					  <h1 style="margin-bottom:5px;" class="box-title">Title: <?php echo htmlspecialchars($title);?> by <?php echo $post_fname." ".$post_lname;?></h1>
					  	<form action="php/remove_post.php" method="POST" class="form-inline" style='float:right;'>
								<input type="hidden" value="<?php echo $nid;?>" name="nid">
								<input type="submit" name="remove_post" value="Remove Post" class="btn btn-danger form-control" style="margin-top:5px;">
						</form>
					
						<span style='display:block;'>Date:<?php echo $postdate;?></span>
						<span style='display:block;'>Department: <?php echo $department;?></span>
						<span style='display:block;'>Course: <?php echo $course;?></span>
						<span style='display:block;'>Reports: <?php echo $no_report;?></span>
					</div><!-- /.box-header -->
					<div class="box-body">
					  <!--Remove id="example one on table-->
						<label>Description</label>
						<p style="margin: 10px 0 0 5px;"><?php echo $description;?></p>
					  
					  <h4>Comments</h4>
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
						  <tr>
							<th>Student Name</th>
							<th>Comment</th>
							<th>Date/Time</th>
							<th>Score</th>
							<th>Report</th>
							<th>Action</th>
						  </tr>
						</thead>
						<tbody>
						<?php
						$comments = $db -> prepare ("
							SELECT stud_bas.fname as fname, stud_bas.lname as lname, cmmt_sect.content as content, cmmt_sect.cmmt_id as cid, stud_bas.stud_id as sid, cmmt_sect.media, cmmt_sect.date as `date`, cmmt_sect.type as type FROM comment_connect INNER JOIN stud_bas ON stud_bas.stud_id = comment_connect.stud_id INNER JOIN cmmt_sect ON cmmt_sect.cmmt_id = comment_connect.cmmt_id WHERE comment_connect.news_id = :nid
							ORDER BY cmmt_sect.date DESC
							");
						$comments -> bindParam (":nid", $nid);
						$comments -> execute();
							while($r_com = $comments -> fetch(PDO::FETCH_ASSOC)){
							$fname = $r_com['fname'];
							$lname = $r_com['lname'];
							$type = $r_com['type'];
							$comment = $r_com['content'];
							$cid = $r_com['cid'];
							$sid = $r_com['sid'];
							$media = $r_com['media'];
							$c_date = $r_com['date'];
							$c_date = date("F d, Y h:i (a)", strtotime($c_date));
							$fn = $fname." ".$lname;
								$score_qr = $db -> prepare ("SELECT SUM(score) as score FROM upvote_comment
								WHERE cmmt_id = :cid");
								$score_qr -> bindParam (":cid", $cid);
								$score_qr -> execute();
								$r_scr_qr = $score_qr -> rowCount();
								$scr_res = $score_qr -> fetch();
								$score = $scr_res['score'];
								if($score=="" or $score==null){
									$score = 0;
								}
								
								$c_report_qr = $db -> prepare ("
								SELECT COUNT(report_connect.report_id) as 	norep 
								FROM report_connect
								LEFT JOIN report
								ON report.report_id = report_connect.report_id
								WHERE report_connect.news_id = :cid");
								$c_report_qr -> bindParam (":cid", $cid);
								$c_report_qr -> execute();
								$count_c_report = $c_report_qr -> rowCount();
						?>
							<tr>
								<td><a href='student_account.php?studentaccount=<?php echo $sid;?>'><?php echo $fn;?></a></td>
								<td><?php echo htmlspecialchars($comment);?></td>
								<td><?php echo $c_date;?></td>
								<td><?php echo $score;?></td>
								<td><?php echo $count_c_report;?></td>
								<td>
									<?php if($type!='Removed'){
										 if($type!='Deleted'){
									?>
									<form action='php/remove_comment.php' method='POST'>
									<input type='hidden' name='nid' value='<?php echo $nid;?>'>
									<input type='hidden' name='cid' value='<?php echo $cid;?>' readonly required>
									<input type='submit' name='remove_comment' class='btn btn-danger form-control' value='Remove Comment'>
									</form>
									<?php
										}
									}
									?>
								</td>
							</tr>
						</tbody>
						<?php
						}
						?>
						<tfoot>
							<tr>
								<th>Student Name</th>
								<th>Comment</th>
								<th>Date/Time</th>
								<th>Score</th>
								<th>Report</th>
								<th>Action</th>
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
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "order": [[4, "desc"]]
		});
      });
		</script>
</body>
</html>
