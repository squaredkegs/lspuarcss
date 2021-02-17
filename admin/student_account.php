<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");
	$adminid = adminLog();

	if(isset($_GET['studentaccount']))
	{
		$sid = $_GET['studentaccount'];
		$chid = $db -> prepare ("SELECT stud_id FROM stud_bas WHERE stud_id = :sid");
		$chid -> bindParam (":sid", $sid);
		$chid -> execute();
		$chid_numrow = $chid -> rowCount();
		if($chid_numrow>0)
		{
			$query = $db -> prepare ("SELECT * FROM stud_bas WHERE stud_id = :sid");
			$query -> bindParam (":sid", $sid);
			$query -> execute();
			$result = $query -> fetch();
			$stud_fname = $result ['fname'];
			$stud_lname = $result ['lname'];
			$campus = $result ['campus'];
			$sch_id = $result ['school_id'];
			$department = $result ['department'];
			$status = $result ['status'];
			$email = $result ['email'];	
			$username = $result ['username'];
			$gender = $result['gender'];
				if(empty($username))
				{
					$username = "No Username Yet";
				}
				$sec_query = $db -> prepare ("SELECT * FROM stud_info WHERE stud_id=:sid");
				$sec_query -> bindParam (":sid", $sid);
				$sec_query -> execute();
				$sec_res = $sec_query -> fetch();
				$connum = $sec_res['connum'];
				$hometown= $sec_res['hometown'];
				$hs = $sec_res['hsschol'];
				$elem = $sec_res['elemschol'];
				$aboutme = $sec_res['aboutme'];
				$picname = $sec_res['picture_name'];
				$picpath = $sec_res['picture_path'];
				

					$max_t = $db -> prepare("SELECT MAX(stat_date) as latest_date FROM admin_activity WHERE (object_id=:sid AND activity='Banned') OR (object_id=:sid AND activity='Remove Ban') AND object='Student'");
					$max_t -> bindParam(":aid", $admin_id);
					$max_t -> bindParam(":sid", $sid);
					$max_t -> execute();
					$max_res = $max_t -> fetch();
						$date_ban = $max_res['latest_date'];				
					$max_t_row_count = $max_t -> rowCount();
			if($max_t_row_count==1){
			$new_date_ban = strtotime ($date_ban);
			
			$allowed_date = date("Y-m-d H:i:s", strtotime('+48 Hours', $new_date_ban));
							//Ban + 48 Hours
			$allowed_date_str = strtotime($allowed_date);
				
			}
			else{
			$allowed_date_str = 0;	
			}
							
							
			$date_now = date("Y-m-d H:i:s");
			$date_now = strtotime($date_now);
			
							
		}
		else
		{
			header("location:existingaccounts.php");
		}
	
	
	}
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Details of <?php echo 
			$stud_fname." ".$stud_lname;?></title>
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

	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>

    <script src="js/jquery-ui.min.js"></script>
		
	
	<!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
	<script type="text/javascript">

	$(document).ready(function(){
		$("#select-info").on("change", function(){
			switch(this.value)
			{
				case "main-information":
					document.getElementById('main-info').style.display = 'inline';
					document.getElementById('post-information').style.display = 'none' ;
					document.getElementById('comment-info').style.display = 'none';
					document.getElementById('log-information').style.display = 'none';
				break;
				case "post-information":
					document.getElementById('main-info').style.display = 'none';
					document.getElementById('post-information').style.display = 'inline'; 
					document.getElementById('comment-info').style.display = 'none';
					document.getElementById('log-information').style.display = 'none';
				break;
				case "comment-information":
					document.getElementById('main-info').style.display = 'none';
					document.getElementById('post-information').style.display = 'none' 
					document.getElementById('comment-info').style.display = 'inline';
					document.getElementById('log-information').style.display = 'none';
				break;
				case "logtime-information":
					document.getElementById('main-info').style.display = 'none';
					document.getElementById('post-information').style.display = 'none' 
					document.getElementById('comment-info').style.display = 'none';
					document.getElementById('log-information').style.display = 'inline';
				
				break;
			}
		});
	});
	
		var datenow = <?php echo $date_now;?>;
		var date_till_ban = <?php echo $allowed_date_str;?>;
		var status = '<?php echo $status;?>';
	  $(document).ready(function(){
		$("#ban_button").click(function(){
			if(datenow>date_till_ban){
			$( "#ban-confirmation").dialog({
				resizable: false,
				height: "auto",
				width: 400,
				modal: true,
				buttons: {
					"Ban Student": function() {
						var reason = $("#ban_reason").val();
							$.ajax({
								type: 'POST',
								url: 'php/banning_exec.php',
								data: 
								{
									ban_sid: '<?php echo $sid;?>',
									status: '<?php echo $status;?>',
									reason: reason,
									
								},
								cache: false,
								
							});
						if(status=='Registered'){
						$("#ban_button").css("background-color", "blue");	
						$("#ban_button").attr("disabled", "disabled");
						$("#ban_button").html("Unban Student");
						}
						else{
						$("#ban_button").css("background-color", "red");	
						$("#ban_button").attr("disabled", "disabled");
						$("#ban_button").html("Ban Student");
						}
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
				}
			  }
			});
		$("#ban-confirmation").css("visibility", "visible");	
			}
			else{

				$("#ban-warning").dialog({
				resizable: false,
				height: "auto",
				width: 400,
				modal: true,
				buttons:{
					Close: function(){
						$(this).dialog("close");
					}
				}
				
				});
			$("#ban-warning").css("visibility", "visible");
			}
		});
	  });
	  
	  function reactivate_account(this_button,e){
			var div = $("#reactivate-account-div");
			var name = $(this_button).attr("name");
			var sid = "<?php echo $sid;?>";
			$(div).html("<button name='" + name + "'class='btn' style='background-color:grey;color:white' onclick='undo_button(this,event);'>Undo " + name + "</button>");
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'php/change_account_status.php',
				cache: false,
				data:
				{
					name: name,
					sid: sid,
				},
				success: function(data){
				//none;
				},
			});
		}
	  
	  function undo_button(this_button,e){
		  var div = $("#reactivate-account-div");
		  var name = $(this_button).attr("name");
		  var sid = "<?php echo $sid;?>";
		 	if(name=='Reactivation'){
				$(div).html("<button id='reactivate-account' class='btn btn-info' name='Reactivation'onclick='reactivate_account(this,event);'>Reactivate Account</button>");
			}
			else if(name=='Inactive'){
				$(div).html("<button id='expire-account' name='Inactive'class='btn btn-danger' onclick='reactivate_account(this,event);'>Expire Account</button>");
			
			}
				$.ajax({
					type: 'POST',
					url: 'php/undo_change_account_status.php',
					cache: false,
					data:
					{
						name: name,
						sid: sid,
					},
					success: function(data){
					//	alert(data);
					},
				});
			e.preventDefault();
	  }
	  </script>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
		
	<!-- Left side column. contains the logo and sidebar -->
      <!--NAVBAR START-->
			<?php 
			
				include_once ("sidebar.php");
				include_once ("navbar.php");
			
			?>
		
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
				  <div class="box">
					<div class="box-header">
					  <h3 class="box-title">Account Details of 
					  <?php echo $stud_fname." ".$stud_lname;?> (<?php echo $status;?>)</h3>
					  
						<div class="form-inline" style="margin-top:15px;">
						<select class="form-control" name="info-filter" id="select-info">
							<option value="">Select Information</option>
							<option value="main-information">Main Information</option>
							<option value="post-information">Post History</option>
							<option value="comment-information">Comment History</option>
							<option value="logtime-information">Log History</option>
							<!--<option value="">Pictures</option>
							<option value="">Friends</option>
							<option value="">Upvote</option>-->
						</select>
						
						
						 <button id="ban_button" class='btn'
						 style="display:block;margin-top:12px;margin-bottom:12px; color:white;font-size:16px;<?php
								if($status=='Registered' OR $status=='Expired' OR $status=='Inactive'){
									echo "background-color:red;";
								}
								else if($status=='Banned'){
									echo "background-color:blue;";
								}
								
								?>">
							<?php
								if($status=='Banned'){
								echo "Unban Student";
							
							}
							else if($status=='Registered' OR $status=='Expired' OR $status=='Inactive'){
								echo "Ban Student";
							}
							?>
						</button>
						
							<div id='reactivate-account-div'>
						<?php
							if($status=='Expired' OR $status=='Inactive'){
						?>	<button id='reactivate-account' class='btn btn-info' name='Reactivation'onclick='reactivate_account(this,event);'>Reactivate Account</button>
						
						<?php
							}
							else if($status=='Registered'){
						?>
							<button id='expire-account' name='Inactive'class='btn btn-danger' onclick='reactivate_account(this,event);'>Expire Account</button>
						<?php
							}
						?>
							</div>
						
						<div id="ban-confirmation" style="visibility:hidden;" 
						title="
						<?php 
						
							if($status=='Banned'){
								echo "Unban the Account?";
							}
							else if($status=='Registered'){
								echo "Ban the Account?";	
							}
						?>">
							<?php
							if($status=='Banned'){
							?>
							<input type='hidden' name='reason' id='ban_reason' class='form-control' placeholder='Reason'  value='Unbanned by Admin' readonly>
							<span style="font-size:14px;color:blue;">Are you sure? This cannot be undone for the next 48 hours!</span>
							
							<?php
							}
							else if($status=='Registered' OR $status=='Expired'OR $status=='Inactive'){	
							?>
							
							<label>Why Ban?</label>
							<input type="text" name="reason" id='ban_reason' class='form-control' placeholder='Reason'>
							
							<span style="font-size:14px;color:red;">Are you sure? This cannot be undone for the next 48 hours!</span>
						
							<?php
							}
							?>
						
						</div>
						<div id="ban-warning" style="visibility:hidden;" title="Action unavailable!">
						<?php
						?> 
							<span style="font-size:18px;color:blue;">Not yet 48 Hours since Banned/Unban</span>
						</div>
						
						</div>
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="col-md-12" id="info-container">
							
						<div  class="col-md-12 main-information" id="main-info" style="display:none;">
							<div class="col-md-6">
							<!--Nasa css/student_account.css ang class=student-detail-->
							<!--Nasa css/student_account.css ang class=student-detail-->
							
							<label class="student-detail">Name</label>
							<span class="form-control"><?php echo $stud_fname ." ". $stud_lname;?></span>
							<label class="student-detail">Contact Number</label>
							<span class="form-control"><?php echo $connum;?></span>
							<label class="student-detail">Username</label>
							<span class="form-control"><?php echo $username;?></span>
							<label class="student-detail">Elementary School</label>
							<span class="form-control"><?php echo $elem;?></span>
							<label class="student-detail">Campus</label>
							<span class="form-control"><?php echo $campus;?></span>
							
							<label class="student-detail">Hometown</label>
							<span class="form-control"><?php echo $hometown;?></span>
							
							
							</div>
							<div class="col-md-6">
							<label class="student-detail">Gender</label>
							<span class="form-control"><?php echo $gender;?></span>
							
							<label class="student-detail">School ID</label>
							<span class="form-control"><?php echo $sch_id;?></span>
							<label class="student-detail">Status</label>
							<span class="form-control"><?php echo $status;?></span>
							<label class="student-detail">High School</label>
							<span class="form-control"><?php echo $hs;?></span>
							<label class="student-detail">Department</label>
							<span class="form-control"><?php echo $department;?></span>
							
							</div>
						</div>
						
						
						
						<!--Others-->
				<div id="post-information" style="display:none;">
				<table id="example" class="table table-bordered table-striped unique-class-table1">
						<thead>
						  <tr>
							<th>Posts</th>
							<th>Title</th>
							<th>Description</th>
							<th>Date/Time</th>
						</tr>
						</thead>
						<tbody>
							<?php 
								$pst_quer = $db -> prepare ("SELECT newsfeed.title as title,
										newsfeed.description as descript, newsfeed.date_and_time as date_time,
										newsfeed.news_id as nid
										FROM post_connect
										INNER JOIN newsfeed
										ON post_connect.news_id = newsfeed.news_id
										INNER JOIN stud_bas
										ON post_connect.stud_id = stud_bas.stud_id
										WHERE stud_bas.stud_id = :sid");
								$pst_quer -> bindParam (":sid", $sid);
								$pst_quer -> execute();
								$pst_row = $pst_quer -> rowCount();
							if($pst_row>0)
							{
								while($row = $pst_quer -> fetch(PDO::FETCH_ASSOC))
								{
									$title = $row['title'];
									$desc = $row['descript'];
									$date = $row['date_time'];
									$nid = $row['nid'];
							?>
						  <tr>
							<td><?php echo $title;?></td>
							<td>Don't Know what to put yer</td>
							<td><?php echo $date;?></td>
							<td><button class="form-control btn btn-info"><a href="admin_comment.php?cmmntdetail=<?php echo $nid;?>" style="color:white;">Detail</a></button></td>
						  </tr>
						  <?php
								}
							}
						  ?>
						</tbody>
						<tfoot>
						  <tr>
							<th>Post</th>
							<th></th>
							<th>Date/Time</th>
							<th>Detail</th>
							</tr>
						</tfoot>
					  </table>
				
</div>  
					  <!--Comment-->
					 <div id="comment-info" style="display:none;"> 
					  <table class="table table-bordered table-striped unique-class-table">
						<thead>
						  <tr>
							<th>Content</th>
							<th>Post Commented On</th>
							<th>Date/Time</th>
							
						</tr>
						</thead>
						<tbody>
							<?php 
								$cmt_quer = $db -> prepare ("SELECT cmmt_sect.datetime as datentime,
										cmmt_sect.content as content, cmmt_sect.stat as stat, newsfeed.title as post_title FROM comment_connect
										INNER JOIN cmmt_sect
										ON comment_connect.cmmt_id = cmmt_sect.cmmt_id
										INNER JOIN newsfeed
										ON comment_connect.news_id = newsfeed.news_id
										INNER JOIN stud_bas
										ON comment_connect.stud_id = stud_bas.stud_id
										WHERE stud_bas.stud_id = :sid");
								$cmt_quer -> bindParam (":sid", $sid);
								$cmt_quer -> execute();
								$cmt_row = $cmt_quer -> rowCount();
							if($cmt_row>0)
							{
								while($sec_row = $cmt_quer -> fetch(PDO::FETCH_ASSOC))
								{
									$post_title = $sec_row['post_title'];
									$content = $sec_row['content'];
									$content = substr($content,0,50) .  "...";
									$cmt_stat = $sec_row['stat'];
									$datentime = $sec_row['datentime'];
							?>
						  <tr>
							<td><?php echo $content;?></td>
							<td><?php echo $post_title;?></td>
							<td><?php echo $datentime;?></td>
						<tr>
						  <?php
								}
							}
							else
							{
						  ?>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						  <?php
							}
						  ?>
						</tbody>
						<tfoot>
						  <tr>
							<th>Content</th>
							<th>Post Commented ON</th>
							<th>Date/Time</th>
							</tr>
						</tfoot>
					  </table>
					</div>
					
					
					
					
					<!--Log Time-->
					<div style="display:none" id="log-information">
					<table class="table table-bordered table-striped unique-class-table3">
						<thead>
						 <tr>
							<th>Date</th>
							<th>Log In</th>
							<th>Log Out</th>
							<th>Time Logged(Estimate)</th>
						</tr>
						</thead>
						<tbody>
								<?php
									$tquery = $db -> prepare ("SELECT time(logging_in) as timein, time(logging_out) as timeout, stud_logid as slid, date(logging_in) as date FROM stud_logtime WHERE stud_id=:sid");
									$tquery -> bindParam (":sid", $sid);
									$tquery -> execute();
									while($time_row = $tquery -> fetch(PDO::FETCH_ASSOC)){
											$in = $time_row['timein'];										
											$out = $time_row['timeout'];
											
											//$in_str = date("Y-m-d H:i:s", strtotime($in));
											//$out_str = date("Y-m-d H:i:s", strtotime($out));
											$in_str = strtotime($in);
											$out_str = strtotime($out);
											$date = $time_row['date'];
											$date = date("F d, Y (l)", strtotime($date));
											
											$time_log = ($out_str - $in_str)/3600;
											$hour = floor($time_log);
											$minute = floor(($time_log - floor($time_log)) * 60);
											$second = floor(($time_log - floor($time_log)) * 3600);
											$second = substr($second, 0, 2);
								?>
							<tr>
								<td><?php echo $date;?></td>
								<td><?php echo date("h:i:s A", strtotime($in));?></td>
								<td><?php 	
											if($out=='00:00:00'){
												echo "Unrecorded";
											}
											else{
												echo date("h:i:s A", strtotime($out));
											}
									?>
								</td>
								<td>
									<?php
											if($out=='00:00:00'){
												echo "Unrecorded";
											}
											else if($hour>0){
												echo $hour." Hours &".$minute." Minutes";
											}
											else if($minute>0){
												echo $hour." ".$minute." Minutes";
											}
											else if($second>0){
												echo $second." Seconds";
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
							<th>Date</th>
							<th>Log In</th>
							<th>Log Out</th>
						    <th>Time Logged (Estimate)</th>
						  </tr>
						</tfoot>
					  </table>
					 </div> 
					<!--//Log Time-->
						<!--Others-->						
						</div>
					  
					</div><!-- /.box-body -->
				  
				  
				  
						<!--Student Information-->
						
						
						<!--//Student Information-->
						
	
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
    <!-- jQuery UI 1.11.4 -->
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
        $(".unique-class-table2").DataTable();      
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
	  
	  
	    $(function () {
        $(".unique-class-table3").DataTable();
        $(".unique-class-table3").DataTable();      
		$('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
		  "order": [[2, "desc"]]
        });
      });
		</script>
</body>
</html>
