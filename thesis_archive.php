<!DOCTYPE html>
<!--Yes the code is bullshit I'll try to fix this motherfucker when I have time-->
			
<?php
	include_once ("php/connection.php");
	include_once ("php/querydata.php");
	include_once ("php/function.php");
	$check_user_login = checkLogIn();
	
	$record_limit = 15;
	$offset = 0;
	

	if(!isset($_GET['department'])){
	$query = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department ORDER BY year DESC LIMIT :offset, :record_limit");
	$query -> bindParam (":department", $rdepartment);
	$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department");
	$filter_department = $rdepartment;
	$numquery -> bindParam (":department", $filter_department);
	$numquery -> execute();
	$numrow = $numquery -> rowCount();
	}
	else if(isset($_GET['department']) && !isset($_GET['course']) && !isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['other_year'])){
	$filter_department = $_GET['department'];
	$query = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department ORDER BY year DESC LIMIT :offset, :record_limit");
	$query -> bindParam (":department", $filter_department);
		$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department");
		$numquery -> bindParam (":department", $filter_department);
		$numquery -> execute();
		$numrow = $numquery -> rowCount(); 
	}
	//dept and course
	else if(isset($_GET['department']) && isset($_GET['course']) && !isset($_GET['campus']) && !isset($_GET['year'])&& !isset($_GET['other_year'])){
	$filter_department = $_GET['department'];
	$filter_course = $_GET['course'];
	$query = $db -> prepare ("SELECT * FROM thesis_arch 
	WHERE department = :dept AND course = :course ORDER BY year DESC LIMIT :offset, :record_limit");
	$query -> bindParam (":dept", $filter_department);
	$query -> bindParam (":course", $filter_course);
		$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department AND course = :course");
		$numquery -> bindParam (":department", $filter_department);
		$numquery -> bindParam (":course", $filter_course);
		$numquery -> execute();
		$numrow = $numquery -> rowCount(); 
	}		
	//dept and year
	else if(isset($_GET['department']) && isset($_GET['year']) && isset($_GET['other_year']) && !isset($_GET['campus']) && !isset($_GET['course'])){
	$filter_department = $_GET['department'];
	$filter_year = $_GET['year'];
	$filter_other_year = $_GET['other_year'];
	$query = $db -> prepare ("SELECT * FROM thesis_arch 
	WHERE department = :dept AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year) ORDER BY year DESC LIMIT :offset, :record_limit");
	$query -> bindParam (":dept", $filter_department);
	$query -> bindParam (":other_year", $filter_other_year);
	$query -> bindParam (":year", $filter_year);
		$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department AND year = :year AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
		$numquery -> bindParam (":department", $filter_department);
		$numquery -> bindParam (":year", $filter_year);
		$numquery -> bindParam (":other_year", $filter_other_year);
		$numquery -> execute();
		$numrow = $numquery -> rowCount(); 	 
	}		
	//dept and campus
	else if(isset($_GET['department']) && isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['course']) && !isset($_GET['other_year'])){
	$filter_department = $_GET['department'];
	$filter_campus = $_GET['campus'];
	$query = $db -> prepare ("SELECT * FROM thesis_arch 
	WHERE department = :dept AND campus = :campus ORDER BY year DESC LIMIT :offset, :record_limit");
	$query -> bindParam (":dept", $filter_department);
	$query -> bindParam (":campus", $filter_campus);
		$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department AND campus = :campus");
		$numquery -> bindParam (":department", $filter_department);
		$numquery -> bindParam (":campus", $filter_campus);
		$numquery -> execute();
		$numrow = $numquery -> rowCount(); 	
	}		
	//dept and course and campus
	else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['other_year'])){
	$filter_department = $_GET['department'];
	$filter_course = $_GET['course'];
	$filter_campus = $_GET['campus'];
	$query = $db -> prepare ("SELECT * FROM thesis_arch 
	WHERE department = :dept AND course = :course AND campus = :campus ORDER BY year DESC LIMIT :offset, :record_limit");
	$query -> bindParam (":dept", $filter_department);
	$query -> bindParam (":course", $filter_course);
	$query -> bindParam (":campus", $filter_campus);
		$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department AND campus = :campus AND course = :course");
		$numquery -> bindParam (":department", $filter_department);
		$numquery -> bindParam (":campus", $filter_campus);
		$numquery -> bindParam (":course", $filter_course);
		$numquery -> execute();
		$numrow = $numquery -> rowCount(); 		 
	}
	//dept and course and year
	else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['year'])&& !isset($_GET['campus']) && isset($_GET['other_year'])){
	$filter_department = $_GET['department'];
	$filter_course = $_GET['course'];
	$filter_year = $_GET['year'];
	$filter_other_year = $_GET['other_year'];
	$query = $db -> prepare ("SELECT * FROM thesis_arch 
	WHERE department = :dept AND course = :course AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year) ORDER BY year DESC LIMIT :offset, :record_limit");
	$query -> bindParam (":dept", $filter_department);
	$query -> bindParam (":course", $filter_course);
	$query -> bindParam (":other_year", $filter_other_year);
	$query -> bindParam (":year", $filter_year);
		$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department AND course = :course AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
		$numquery -> bindParam (":department", $filter_department);
		$numquery -> bindParam (":course", $filter_course);
		$numquery -> bindParam (":other_year", $filter_other_year);
		$numquery -> bindParam (":year", $filter_year);
		$numquery -> execute();
		$numrow = $numquery -> rowCount(); 		 
	}
	//dept and campus and year
	else if(isset($_GET['department']) && isset($_GET['year']) && isset($_GET['campus']) && !isset($_GET['course']) && isset($_GET['other_year'])){
	$filter_department = $_GET['department'];
	$filter_campus = $_GET['campus'];
	$filter_year = $_GET['year'];
	$filter_other_year = $_GET['other_year'];
	$query = $db -> prepare ("SELECT * FROM thesis_arch 
	WHERE department = :dept AND campus = :campus AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year) ORDER BY year DESC LIMIT :offset, :record_limit");
	$query -> bindParam (":dept", $filter_department);
	$query -> bindParam (":year", $filter_year);
	$query -> bindParam (":other_year", $filter_other_year);
	$query -> bindParam (":campus", $filter_campus);
		$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE department = :department AND campus = :campus AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
		$numquery -> bindParam (":department", $filter_department);
		$numquery -> bindParam (":campus", $filter_campus);
		$numquery -> bindParam (":other_year", $filter_other_year);
		$numquery -> bindParam (":year", $filter_year);
		$numquery -> execute();
		$numrow = $numquery -> rowCount(); 		 
	}		
	
	//all
	else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['campus']) && isset($_GET['year']) && isset($_GET['other_year'])){
	$filter_department = $_GET['department'];
	$filter_course = $_GET['course'];
	$filter_campus = $_GET['campus'];
	$filter_year = $_GET['year'];	
	$filter_other_year = $_GET['other_year'];
	$query = $db -> prepare ("SELECT * FROM thesis_arch 
	WHERE department = :dept AND course = :course AND campus = :campus AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
	$query -> bindParam (":dept", $filter_department);
	$query -> bindParam (":course", $filter_course);
	$query -> bindParam (":campus", $filter_campus);
	$query -> bindParam (":year", $filter_year);
	$query -> bindParam (":other_year", $filter_other_year);
		$numquery = $db -> prepare ("SELECT * FROM thesis_arch WHERE campus = :campus AND department = :department AND campus = :campus AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
		$numquery -> bindParam (":campus", $filter_campus);
		$numquery -> bindParam (":department", $filter_department);
		$numquery -> bindParam (":course", $filter_course);
		$numquery -> bindParam (":other_year", $filter_other_year);
		$numquery -> bindParam (":year", $filter_year);
		$numquery -> execute();
		$numrow = $numquery -> rowCount(); 		 
	 
	}
		if(isset($_GET['page'])){
			$page = $_GET['page'];
			$offset = $record_limit * $page;
			$offset = ($record_limit * $page) - $record_limit;
			
		}
	
	$upper_limit = ceil($numrow/$record_limit);
	$query -> bindValue (":offset", (int) trim ($offset), PDO::PARAM_INT);
	$query -> bindValue (":record_limit", (int) trim($record_limit), PDO::PARAM_INT);
	$query -> execute();
			
	//End of that bullshit if
	
		if(isset($_GET['page'])){
			$page = $_GET['page'];
			$offset = $record_limit * $page;
			$offset = ($record_limit * $page) - $record_limit;
		
			
		}
	
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Thesis Archive</title>
	
	<!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
	<link href="css/newsfeed.css" rel="stylesheet">
    
	<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="image/thesis.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/jquery-click-outside.js"></script>
	<script>
	$(document).ready(function(){
		$("#select_department").on('change', function(){
			var dept = $(this).val();
			$.ajax({
				type: 'POST',
				url: 'php/get_course.php',
				cache: false,
				data:
				{
					department: dept,
				},
				success: function(data){
					$("#select_course").html(data);
				},
			});
		});
	});
		
	$(document).ready(function(){
		$("#year_option").on('change', function(){
			var val = $(this).val();
			if(val=='from'){
				$.ajax({
					type: 'POST',
					url: 'php/show_other_year.php',
					data:
					{
						val: val,
					},
					cache: false,
					success: function(data){
						$("#second_year").html(data);
						$("#until").css("display", "block");
						$("#until").show();
					}
				});
			}
			else{
				
				$("#second_year").html("");
				$("#until").hide();
					
			}	
		});
	});
	
	function request_thesis (this_button,e){

				var id = $(this_button).attr("id");
				var container = $(this_button).closest(".request-container");
				var cut_id = id.lastIndexOf("_");
				var real_id = id.substr(cut_id + 1);
					$.ajax({
						type: 'POST',
						url: 'php/request_thesis_access.php',
						data:
						{
							thid: real_id,
						},
						cache: false,
						success: function(data){
							$(container).html(data);
						},
					});
				e.preventDefault();
				
		}
		
			function cancel_request (this_button,e){
				var id = $(this_button).attr("id");
				var container = $(this_button).closest(".request-container");
				var cut_id = id.lastIndexOf("_");
				var real_id = id.substr(cut_id + 1);
					$.ajax({
						type: 'POST',
						url: 'php/cancel_request_thesis.php',
						data:
						{
							thid: real_id,
						},
						cache: false,
						success: function(data){
							$(container).html(data);
						},
					});
				e.preventDefault();
		}
	
	</script>
</head>
</script>
<body class="homepage" style="background: linear-gradient(to bottom, #F0F8FF 0%, #F5F5F5 100%);">

	<?php
		include_once ("header.php");
	?>
		<!--Content-->
	<!--BANNER-->	
	<img src="image/banner.png" title="Laguna State Polytechnic University" alt="logo" style="margin-top:4px;" width="100%" height="100%" border="0"> 
	<!--BANNER-->
	
	<div class="container" style="margin-top:50px;">
		<!--Start of Filter Form-->
		<div class="col-md-5" style='margin-left:40px; background: linear-gradient(to bottom, #F5F5DC 0%, #ADFF2F 100%); padding-bottom:10px; padding-top:10px; border-radius:5px;box-shadow:0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);margin-bottom:30px;'>
			
			<span style="margin-top:10px;font-family:arial;font-size:14px; margin-left:37px;">Search</span>
			<form action='php/filter_thesis_archive.php' method='POST' class='form-group' style='width:400px;'>
				<select name='campus' class='form-control' style='margin-bottom:3px;'>
					<option value=''>Select Campus</option>
					<?php
						$get_campus = $db -> prepare ("SELECT campus FROM campus_tbl");
						$get_campus -> execute();
						while($campus_row = $get_campus -> fetch(PDO::FETCH_ASSOC)){
							$new_campus = $campus_row['campus'];
					?>
					<option value='<?php echo $new_campus;?>'><?php echo $new_campus;?></option>
					<?php
						}
					?>
				</select>
				<select id='select_department' class='form-control' name='department'style='margin-bottom:3px;' required>
					<option value='<?php echo $rdepartment;?>'><?php echo $rdepartment;?></option>
					<?php
						$get_dept = $db -> prepare ("SELECT department as dept, department_id as did FROM department_tbl WHERE department != :dept ORDER BY department ASC");
						$get_dept -> bindParam (":dept", $rdepartment);
						$get_dept -> execute();
						while($dept_row = $get_dept -> fetch(PDO::FETCH_ASSOC)){
						$new_department = $dept_row['dept'];
						$did = $dept_row['did'];
					?>
						
						<option value='<?php echo $new_department;?>'><?php echo $new_department;?></option>
					<?php
						}
					?>
					
				</select>
				<span id='select_course'>
				<select name='course' class='form-control'>
					<option value=''>Select Course</option>
					<?php
							
									$get_dept_id = $db -> prepare ("SELECT department_id as did FROM department_tbl WHERE department=:dept");
		$get_dept_id -> bindParam (":dept", $department);
		$get_dept_id -> execute();
		$res_did = $get_dept_id -> fetch();
		$did = $res_did['did'];
							$get_course = $db -> prepare ("SELECT course_tbl.course as course FROM course_connect
							LEFT JOIN course_tbl
							ON course_tbl.course_id = course_connect.course_id
							WHERE course_connect.department_id = :did
							");
							$get_course -> bindParam (":did", $did);
							$get_course -> execute();
							while($r_get_course = $get_course -> fetch(PDO::FETCH_ASSOC)){
								$new_course = $r_get_course['course'];
					?>
							<option value='<?php echo $course;?>'><?php echo $new_course;?></option>
						<?php
							}
						?>	
				</select>
				</span>
				<label>Year</label>
				<select id='year_option' class='form-control' style='margin-bottom:10px;'>
					<option value='in'>In</option>
					<option value='from'>From</option>
				</select>
				<select name='year' class='form-control' style='margin-bottom:5px;'>
					<option value=''>Select Year</option>
					<?php
						$year = date("Y");
						$limit_year = $year - 6;
						for($x = $year; $x>=$limit_year;$x--){
					?>
					<option value='<?php echo $x;?>'><?php echo $x;?></option>
					<?php
						}
					?>
				</select>
				
				<span id='until' style='display:none;margin-left:3px;'>Until</span>
				<span id='second_year' style='margin-bottom:5px;'>
				</span>
				<input type='submit' name='submit' value='Search' class='btn btn-info' style='margin-top:5px;'>
				<?php
				if(isset($_GET['department'])){
				?>
				<a href='thesis_archive'><button class='btn btn-info' type='button' style='margin-top:5px;'>Default Search</button></a>
				<?php
				}
				?>
			</form>
		</div>
		<!--End of Filter Form-->
		
		<!--Start of Thesis Archive Display-->
		<div class="col-md-12" style="margin-bottom:25px;margin-left:20px; background-color:white; border-radius:8px; padding:4px; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);" id="newsfeed">
		<?php
	if($numrow>0){
		while($row = $query -> fetch (PDO::FETCH_ASSOC)){
		$thid = $row['thesis_id'];
		$title = $row['title'];
		$thesis_campus = $row['campus'];
		$thesis_dept = $row['department'];
		$thesis_course = $row['course'];
		$thesis_year = $row['year'];
		$thesis_course = $row['course'];
		$get_filename = $db -> prepare ("SELECT abstract_filename,filepath,type,upload_date,complete_filename FROM thesis_arch WHERE thesis_id = :thid");
		$get_filename -> bindParam (":thid", $thid);
		$get_filename -> execute();
			$r_get_filename = $get_filename -> fetch();
			$abstract = $r_get_filename['abstract_filename'];
			$filepath = $r_get_filename['filepath'];
			$complete = $r_get_filename['complete_filename'];
							
			$check_if_unlock = $db -> prepare ("SELECT request_thesis_connect.request_id as rqid, request_thesis.status as request_status FROM request_thesis_connect 
			LEFT JOIN request_thesis
			ON request_thesis.request_id = request_thesis_connect.request_id
			WHERE request_thesis_connect.thesis_id = :thid AND request_thesis_connect.stud_id = :sid
							");
			$check_if_unlock -> bindParam (":thid", $thid);
			$check_if_unlock -> bindParam (":sid", $getid);
			$check_if_unlock -> execute();
			$res_check_unlock = $check_if_unlock -> fetch();
			$request_status = "";
			$request_status = $res_check_unlock['request_status'];
			$numrow_check_unlock = $check_if_unlock -> rowCount();
	?>
			<div class='col-md-5' style='position:relative;border:2px solid #1E90FF; border-radius:5px; margin-bottom:10px;height:170px;margin-left:60px; margin-top:10px;'>
			<label>
			<?php
			if($abstract!="" && $complete==""){
			?>
			<a href='php/<?php echo $filepath.$abstract;?>' target="_blank" style="color:red;"><?php echo $title;?>
			<?php
			}
			else if($complete!="" && ($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected') && $abstract==""){
			?>
			<a href='#' onclick='notapproved(event);'style="color:#000080;"><?php echo $title;?>
			<?php
			}
			else if($complete!="" && $request_status=='Approved'){
			?>
			<a href='php/<?php echo $filepath.$complete;?>' target="_blank" style="color:#000080;"><?php echo $title;?>
			<?php
			}
			else if($complete!="" && $abstract != "" &&($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected')){
			?>
			<a href='php/<?php echo $filepath.$abstract;?>' target="_blank" style="color:red;"><?php echo $title;?>
			<?php
			}
			else if($complete!="" && $abstract != "" && $request_status == 'Approved'){
			?>
			<a href='php/<?php echo $filepath.$complete;?>' target="_blank"style="color:#000080;"><?php echo $title;?>
			<?php
			}
			?>
				<span style='font-size:10px;'><?php
				if($abstract!="" && $complete==""){
				echo "(Abstract Only)";
				}
				else if($abstract=="" && $complete!=""){
				echo "(Complete Only)";	
				}
				else if($abstract!="" && $complete!=""){
				echo "(Both)";
				}
						
				if(($complete!="") && ($request_status=='Rejected' || $request_status == 'Pending' || $request_status=="")){
				?>
				<img src='image/extra/lock_icon.png' style='width:12px;height:12px;'>
				<?php
				}
				else if($numrow_check_unlock>0 && $request_status == 'Approved'){
				?>
				<img src='image/extra/unlock_icon.png' style='width:12px;height:12px;'>
				<?php
				}
				?>
				</span></a></label>		
				<span>
					<div style='font-size:10px;'>
						<span style='display:block;margin-top:-5px;'><?php echo $thesis_campus;?></span>
						<span style='display:block;margin-top:-5px;'><?php echo $thesis_dept;?></span>
						<span style='display:block;margin-top:-5px;'><?php echo $thesis_year;?></span>
					</div>
				<?php
				if($abstract != "" && ($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected')){
				?>
				<a href='php/<?php echo $filepath.$abstract;?>' style='margin-bottom:25px;' download title='Download Abstract'><img style='height:20px;width:20px;'src='image/extra/download_button.png' ></a>
				<?php
				}
				else if($complete!="" && $request_status == "Approved"){
				?>
				<a href='php/<?php echo $filepath.$complete;?>' style='margin-bottom:25px;' download title='Download Abstract'><img style='height:20px;width:20px;'src='image/extra/download_button.png' ></a>
				<?php	
				}
				if($complete!=""){
				?>
				<span class='request-container'>
				<?php
				if($request_status=="" || $request_status=="Rejected"){
				?>
				<a href='#' style='margin-bottom:25px;' title='Request Complete Access' class='request-access' id='thesis_<?php echo $thid;?>' onclick='request_thesis(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/request.png'></a>
				<?php
				}
				else if($request_status=='Pending'){
				?>
				<a href='#' style='margin-bottom:25px;' title='Waiting for Approval' class='cancel-request' id='cancel_request_<?php echo $thid;?>' onclick='cancel_request(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/waiting.png'></a>
				<?php
				}
				?>
				</span>
				<?php
				}
				?>
				
		</div>
		<?php
		}
	}
	else{
		echo "<span style='display:block;'>No Thesis Found</span>";
	}
		?>
				
				
		</div>
		<!--Start of Pagination-->
		<div class='col-md-12'>
		<?php
			if(!isset($_GET['page'])){
				$page = 1;
			}
			$next_page = $page + 1;
			$previous_page = $page - 1;
		
	if(!isset($_GET['department'])){
	?>
		<?php
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
		?>
	<?php
	}
	//department
	else if(isset($_GET['department']) && !isset($_GET['course']) && !isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['other_year'])){
	?>
		<?php
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
		?>
	<?php
	}
	//dept and course
	else if(isset($_GET['department']) && isset($_GET['course']) && !isset($_GET['campus']) && !isset($_GET['year'])&& !isset($_GET['other_year'])){
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>&course=<?php echo $filter_course;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>&course=<?php echo $filter_course;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
	}		
	//dept and year
	else if(isset($_GET['department']) && isset($_GET['year']) && isset($_GET['other_year']) && !isset($_GET['campus']) && !isset($_GET['course'])){
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
	}		
	//dept and campus
	else if(isset($_GET['department']) && isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['course']) && !isset($_GET['other_year'])){
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>&campus=<?php echo $filter_campus;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>&campus=<?php echo $filter_campus;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
	}		
	//dept and course and campus
	else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['other_year'])){
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>&course=<?php echo $filter_course;?>&campus=<?php echo $filter_campus;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>&course=<?php echo $filter_course;?>&campus=<?php echo $filter_campus;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
	}
	//dept and course and year
	else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['year'])&& !isset($_GET['campus']) && isset($_GET['other_year'])){
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>&course=<?php echo $filter_course;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>&course=<?php echo $filter_course;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
	}
	//dept and campus and year
	else if(isset($_GET['department']) && isset($_GET['year']) && isset($_GET['campus']) && !isset($_GET['course']) && isset($_GET['other_year'])){
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>&campus=<?php echo $filter_campus;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>&campus=<?php echo $filter_campus;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
	}
	//dept and course and year
	else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['year'])&& !isset($_GET['campus']) && isset($_GET['other_year'])){
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>&course=<?php echo $filter_course;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>&course=<?php echo $filter_course;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
	}		
	//all
	else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['campus']) && isset($_GET['year']) && isset($_GET['other_year'])){
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>&department=<?php echo $filter_department;?>&campus=<?php echo $filter_campus;?>&course=<?php echo $filter_course;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>&department=<?php echo $filter_department;?>&campus=<?php echo $filter_campus;?>&course=<?php echo $filter_course;?>&year=<?php echo $filter_year;?>&other_year=<?php echo $filter_other_year;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
	}
		?>
		
		</div>
		<!--End of thesis archive display-->

		
		<!--End of Pagination-->
	</div>

    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
               <div class="col-sm-6" style="color:white;">
                   &copy; 2017 <a target="_blank" href="#" title="arvin is awesome">Suicide Squad</a>. All Rights Reserved.
                </div>

            </div>
        </div>
    </footer><!--/#footer-->

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/wow.min.js"></script>
</body>
</html>