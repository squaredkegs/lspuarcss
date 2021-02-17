<script>

		var pending  = setInterval(get_total_pending,1000);
		function get_total_pending(){
			var aid = "<?php echo $aid;?>";
			$.ajax({
				type: 'POST',
				url: 'php/display_number_of_pending.php',
				data:
				{
					aid: aid,
				}, 
				cache: false,
				success: function(data){
					$("#pending_notification").html(data);
				}
			});
		}
		var msg = setInterval(get_no_msg, 1000); 
		function get_no_msg(){
			var aid = "<?php echo $aid;?>";
			$.ajax({
				type: 'POST',
				url: 'php/display_no_msg.php',
				data:
				{
					aid: aid,
				},
				cache: false,
				success: function(data){
					$("#pending_messages").html(data);
				}
			});
		}
		
		
		$(document).ready(function(){
			$("#show_pending").on('click', function(){
				$("#display_notification").load('php/show_notification');
					
			});
		});

		$(document).ready(function(){
			$("#show_msg").on('click', function(){
				$("#display_msg_notification").load('php/show_msg_notification');
			});
		});
</script>

<?php
	include_once ("php/adminfunction.php");
	include_once ("php/connection.php");
	include_once ("php/queryadmindata.php");
	$adminid = adminLog();
		if($rposition=="School Admin"){
		$query = $db -> prepare ("SELECT campus,status,department FROM stud_bas WHERE
									campus=:campus AND status='Pending'");
		$query -> bindParam(":campus", $rcampus);
		$query -> execute();
		$pending = $query -> rowCount();
		}
		else{
			$pending = 0;
		}
		$notifications = $pending;
	  
				   
	  ?>
		<header class="main-header">
		<!-- Logo -->
        <a href="#"  style="cursor:default;" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>These</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>These</b> Qu'il</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu" id='show_msg'>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success" id='pending_messages'>
				  </span>
                </a>
                <ul class="dropdown-menu" style='height:80px;' id='display_msg_notification'>
				</ul>
              </li>
              <?php
				if($rposition=='School Admin'){
			  ?>
			  <li class="dropdown notifications-menu" id='show_pending'>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning" id='pending_notification'>	
				  </span>
                </a>
                <ul class="dropdown-menu" id='display_notification'style='height:80px;'>
                
                </ul>
              </li>
				<?php
				}
				?>
              <!-- Tasks: style can be found in dropdown.less -->
             
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                  <span class="hidden-xs"></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    <p>
                      <?php	$date = date("Y-m-d");
							echo $rfname." ".$rlname;?> - <?php echo $rposition;?>
                      
					  <small><?php echo $date;?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="php/logout_exec.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
