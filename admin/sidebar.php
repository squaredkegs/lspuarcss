<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");
	
	$adminid = adminLog();
	$datetime = date("Y-m-d");
	
	$expire_query = $db -> prepare ("SELECT campus, department, expire_date FROM admin_expire_passwords");
	$expire_query -> execute();
	$string_date = strtotime($datetime);
	while($expire_row = $expire_query -> fetch(PDO::FETCH_ASSOC)){
		$qqcampus = $expire_row['campus'];
		$qqdepartment = $expire_row['department'];
		$expire_date = $expire_row['expire_date'];
		$string_expire_date = strtotime($expire_date);
	$get_students = $db -> prepare ("SELECT fname, lname, stud_id FROM stud_bas WHERE campus = :campus AND department = :department");
	$get_students -> bindParam (":campus", $qqcampus);
	$get_students -> bindParam (":department", $qqdepartment);
	$get_students -> execute();
		while($results = $get_students -> fetch(PDO::FETCH_ASSOC)){
		$sid = $results['stud_id'];
			if($string_date>=$string_expire_date){
				$expire_accounts = $db -> prepare ("UPDATE stud_bas SET status = 'Expired' WHERE stud_id = :sid AND status = 'Registered'");
				$expire_accounts -> bindParam (":sid", $sid);
				$expire_accounts -> execute();
			}
		}
	}
	
	$day_date = date("d");
	if($day_date %5 ==0){
		$delete_remove_post; 
	}
	
		if(time() - $_SESSION['LAST_ACTIVITY']>1800){
	?>
		
		<script>
		var agreed = false;
		$(function() {
			$( "#session-expiration" ).dialog({
				modal: true,
			});
		$("#session-expiration").css("visibility", "visible");
		});
		
	
	$(document).ready(function(){
		$(".set-session-time").click(function(){
				return false
			});
		$(".dropdown").click(function(){
				return false
		});
	});	
	
	</script>
 	<style>
	.set-session-time{
		cursor:default;
	}
	.dropdown{
		cursor:default;
	}
	</style>
	
	
	<?php
	}
	else{
		$_SESSION['LAST_ACTIVITY'] = time();
	}
	?>
	<style>
	#circle{
		width: 100px;
		height: 100px;
		background-color: red;
		color:white;
		-moz-border-radius: 50px;
		-webkit-border-radius: 50px;
	}
	.red-circle{
		color:white !important;
		background-color:red !important;
		border-radius:60% !important;
		height:100px !important;
		width:100px !important;
	}
	</style>
<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="image/lspulogo.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $rfname." ". $rlname;?></p>
				<!--
			  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
				-->
			</div>
          </div>
		<!--NAVBAR START-->
	

 <ul class="sidebar-menu">
            <li class="header">Social Media Network</li>
            <li class="active treeview">
              <a href="index" class="set-session-time">
                <span><img src="image/h.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
				Home</span> <i class="fa pull-right"></i>
              </a>
            </li>
			<li class="treeview">
              <a href="#" class="set-session-time">
                 <span><img src="image/adminlogo.ico" title="LSPU" alt="logo" width="25" height="25" border="0">Site Information</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
            <ul class="treeview-menu">
						<li><a href="admin_newsfeed" class="set-session-time"><img src="image/news.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
						Newsfeed </a></li>
						<li><a href="student_log"><img src="image/masterlist.ico" title="LSPU" alt="logo" width="25" height="25" border="0">Student Logs </a></li>
						
			</ul>
            </li>
			<li class="treeview">
              <a href="#" class="set-session-time">
               <span><img src="image/s.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
				Student & Site Info.</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			
						
					
						<li><a href="existingaccounts" class="set-session-time"><img src="image/sa.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
						Student Accounts </a></li>
					<?php
						if($rposition=='School Admin'){
							$result = $db -> prepare("SELECT * FROM stud_bas WHERE status = 'Pending' AND campus=:campus AND department=:dept ORDER BY timereg ASC");
							$result -> bindParam (":campus", $admin_campus);
							$result -> bindParam (":dept", $rdepartment);
							$result -> execute();
							$pending_num = $result -> rowCount();
							$result_request = $db -> prepare("SELECT * FROM request_thesis WHERE campus=:campus AND department=:dept AND status='Pending' ORDER BY request_date ASC");
							$result_request -> bindParam (":campus", $admin_campus);
							$result_request -> bindParam (":dept", $rdepartment);
							$result_request -> execute();
							$pending_rqst = $result_request -> rowCount();

					?>
							
						<li><a href="pending" class="set-session-time"><img src="image/pending.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
						Pending Registration 
						<span id='circle'>
							<?php 
								if($pending_num>0){
								echo $pending_num;
								}
							?>
						</span></a></li>
						<li><a href="list-expired-accounts" class="set-session-time"><img src="image/expire.png" title="LSPU" alt="logo" width="25" height="25" border="0">
						Expired Accounts <span id='circle'></span></a></li>
						<li><a href="banned_list" class="set-session-time"><img src="image/ban.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
						Student Ban List</a></li>
						<li><a href="Dept & Course"></a></li>
					<?php
						}
					?>
			</ul>
            </li>
					<?php
				
					if($rposition=='Main Admin')
					{
					?>
		
			<li class="treeview">
              <a href="#" class="set-session-time">
                <span><img src="image/admin.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
				Admin Accounts</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
				<li><a href="add_admin" class="set-session-time"><img src="image/new.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
				Create Admin Account</a></li>
				<li><a href="school_admin" class="set-session-time"><img src="image/list.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
				School Admin List </a></li>
                <li><a href="admin_activity" class="set-session-time"><img src="image/act.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
				Admin's Activities </a></li>
                   
			</ul>
            </li>
					<?php
				   }
				   else{
					?>
			<li class="treeview">
              <a href="school_admin" class="set-session-time">
                <img src="image/login2.ico" title="LSPU" alt="logo" width="25" height="25" border="0"> <span>School Admin List</span> <i class="fa"></i>
              </a>
            </li>
				<?php
				   }
				   ?>
					<?php
						if($rposition=='School Admin'){
					?>
			<li class="treeview">
				<a href="#"  class="set-session-time">
                 <span><img src="image/ta.ico" title="LSPU" alt="logo" width="25" height="25" border="0"> Thesis Archive</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
				<ul class="treeview-menu">
					<li><a href="thesis_archive" class="set-session-time"><img src="image/list.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
					Thesis Archive</a></li>
					<li><a href="pending_thesis" class="set-session-time"><img src="image/pending.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
						
						Pending Thesis Request 
							<span id='circle'>
							<?php 
								if($pending_rqst>0){
								echo $pending_rqst;
								}
							?>
							</span></a>
					</li>
					<li><a href="accessed-thesis.php"><img src="image/mata.png" title="LSPU" alt="logo" width="25" height="25" border="0">
					Accessed Thesis by Students </a></li>
					
					<li><a href="upload_thesis.php"><img src="image/upload.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
					Upload Thesis </a></li>
					<!--<li><a href="pages/layout/fixed.html"><img src="image/pri.ico" title="LSPU" alt="logo" width="25" height="25" border="0">
					Student Privilege </a></li>
					<li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i></a></li>-->
				</ul>
            </li>
					<?php
						}
					?>
			<li class="treeview">
              <a href="#">
               <img src="image/others.png" title="LSPU" alt="logo" width="25" height="25" border="0">
						<span>Others
						<?php
						if($rposition == 'Main Admin'){
						$query_account_ext = $db -> prepare ("SELECT admin_id FROM admin_tbl WHERE request_account_extension != '0000-00-00 00:00:00'");
							$query_account_ext -> execute();
							$numrow_query_account_ext = $query_account_ext -> rowCount();
							if($numrow_query_account_ext>0){
						?>
								
								<img src='image/alert.png' style='height:15px;width:15px;margin-left:10px;'/>
						<?php
							}
						}
						
						?>
						</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
				<ul class="treeview-menu">
					
					<li><a href="deptandcourse"><img src="image/dept.png" title="LSPU" alt="logo" width="25" height="25" border="0"> Department & Courses </a></li>
					<?php
						if($rposition=='School Admin'){
					?>
					<li><a href="filterwords"><img src="image/word.png" title="LSPU" alt="logo" width="25" height="25" border="0"> Filtered Words </a></li>
					<li><a href="expire_accounts"><img src="image/date.png" title="LSPU" alt="logo" width="25" height="25" border="0"> Expiration Date Settings</a></li>					
					<?php
						}
						else if($rposition == 'Main Admin'){
							$query_account_ext = $db -> prepare ("SELECT admin_id FROM admin_tbl WHERE request_account_extension != '0000-00-00 00:00:00'");
							$query_account_ext -> execute();
							$numrow_query_account_ext = $query_account_ext -> rowCount();
					?>
						<li><a href="admin_expire_settings"><img src="image/date.png" title="LSPU" alt="logo" width="25" height="25" border="0">Admin Expiration Setting
							<?php
								if($numrow_query_account_ext>0){
							?>
								<img src='image/alert.png' style='height:15px;width:15px;margin-left:10px;'/>
							<?php
								}
							?>
							</a></li>
						<!--<li><a href="department_expire_settings"><i class="fa fa-circle-o"></i>Department Expiration Setting</a></li>-->
					<?php	
						}
					?>
					<li><a href="edit_account"><img src="image/admin.ico" title="LSPU" alt="logo" width="25" height="25" border="0">	My Account Settings</a></li><!--<li><a href="existingaccounts"><i class="fa fa-circle-o"></i> Bans, Comment, etc. </a></li>-->
				</ul>
            </li>
			<!--
            <li>
              <a href="pages/widgets.html">
                <i class="fa fa-th"></i> <span>Posts</span>
              </a>
            </li>
			
            <li class="treeview">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>Posts</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
              </ul>
            </li>
			-->
		  </ul>
		  
		  		<!--NAVBAR END-->
        </section>
        <!-- /.sidebar -->
      </aside>
	  

<div id="session-expiration" title="Session Expiration" style="visibility:hidden;display:none;">
  <p>Your session has expired! Please Log In Again</p>
  <hr/>
	<a href="php/session_expire" class="btn btn-info" style="color:white;float:right;">Okay</a>
</div>
	