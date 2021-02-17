<?php

	include_once ("php/connection.php");
	include_once ("php/function.php");
	include_once ("php/querydata.php");

	if(isset($_GET['var'])){
		$variable = $_GET['var'];
		$get_dept_id = $db -> prepare ("SELECT department_id as did FROM department_tbl WHERE department=:dept");
		$get_dept_id -> bindParam (":dept", $rdepartment);
		$get_dept_id -> execute();
		$res_did = $get_dept_id -> fetch();
		$did = $res_did['did'];

		if(isset($_GET['sort'])){
			$sort = $_GET['sort'];
		}
	
	
		$variable = $_GET['var'];
		$variable_to_array = explode(" ",$variable);
		$echo_array = implode(" +",$variable_to_array);
		$count = count($variable_to_array);
		if($count==1){
			$thesis_variable = "+".$variable;
		}
		else if($count==2){
			$first_variable = $variable_to_array[0];
			$second_variable = $variable_to_array[1];
			$thesis_variable = "+".$first_variable." +".$second_variable;
		}
		else if($count==3){
			$first_variable = $variable_to_array[0];
			$second_variable = $variable_to_array[1];
			$third_variable = $variable_to_array[2];		
			$thesis_variable = "+(".$first_variable." ".$second_variable.")"." +".$third_variable;
		}
		else if($count>=4){
			$student_variable = "+".$echo_array;
			$thesis_variable = "+".$echo_array;
		}
	}
	else {
		$variable = "Not Found";
	}

?>

<html>
<head>
<style>
	select{
		margin-top:5px;
		margin-bottom:5px;
	}
</style>
<script>
		$(document).ready(function(){
			$("#year_option").on('change', function(){
				var val = $(this).val();
					if(val=='from'){
					$.ajax({
						type: 'POST',
						url: 'php/show_other_year.php',
						data:
						{
							val : val,
						},
						success: function(data){
							$("#second_year").html(data);
							$("#until").css("display", "block");
							$("#until").show();
						}
					});
					}
					else
					{
							$("#second_year").html("");
							$("#until").hide();
					}
				
			});
		});
</script>
</head>
<body>
		<div id="thesis">
			<center><div style='width:300px;margin-left:20px;'>
			<form class='form-inline' action='php/filter_thesis.php' method='POST'>
				<input type='hidden' name='var' value='<?php echo $variable;?>'>
				<input type='hidden' name='sort' value='thesis'>
				<select class='form-control' name='campus' style='margin-bottom:15px;'>
						<option value=''>Select Campus</option>
					<?php
						$get_camp = $db -> prepare ("SELECT campus FROM campus_tbl");
						$get_camp -> execute();
						while($r_get_camp = $get_camp -> fetch(PDO::FETCH_ASSOC)){
						$campus = $r_get_camp['campus'];	
					?>
						<option value='<?php echo $campus;?>'><?php echo $campus;?></option>
					<?php
						}
					?>
				</select>
				<input type='text' name='department' value='<?php echo $rdepartment;?>' class='form-control' readonly required>
				<select name='course' class='form-control'>
					<option value=''>Select Course</option>
					<?php
						
						$get_course = $db -> prepare ("SELECT course_tbl.course as course FROM course_connect
						LEFT JOIN course_tbl
						ON course_tbl.course_id = course_connect.course_id
						WHERE course_connect.department_id = :did
						");
						$get_course -> bindParam (":did", $did);
						$get_course -> execute();
						while($r_get_course = $get_course -> fetch(PDO::FETCH_ASSOC)){
							$course = $r_get_course['course'];
					?>
						<option value='<?php echo $course;?>'><?php echo $course;?></option>
					<?php
						}
					?>	
				</select>
				<select id='year_option' class='form-control'>
					<option value='in'>In</option>
					<option value='from'>From</option>
				</select>
				<select name='year' class='form-control'>
					<option value=''>Select Year</option>
					<?php
						$curr_year = date("Y");
						$limit_year = $curr_year - 6;
						for($x = $curr_year;$x>=$limit_year;$x--){
					?>
						<option value='<?php echo $x;?>'><?php echo $x;?></option>
					<?php
						}
					?>
				</select>
				<span id='until' style='display:none;'>Until</span>
				
				<span id='second_year'>
				</span>
				
				<input type='submit' name='submit' value='Search' class='btn btn-info'>
				
			</form>
			</div></center>
			
		<?php
			if(isset($_GET['department']) && isset($_GET['campus']) && isset($_GET['course'])){
				$dept = $_GET['department'];
				$camp = $_GET['campus'];
				$course = $_GET['course'];
				$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
				WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department=:dept AND campus=:camp, AND course=:course;");
				$search_thesis_query -> bindParam (":dept", $dept);
				$search_thesis_query -> bindParam (":camp", $camp);
				$search_thesis_query -> bindParam (":course", $course);
			}
			else
			{
				
			}
				
			
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE);");
			$search_thesis_query -> bindValue (":variable",$thesis_variable, PDO::PARAM_STR);
			$search_thesis_query -> execute();
			$thesis_numrow = $search_thesis_query -> rowCount();
				if($thesis_numrow>0){
						while($row = $search_thesis_query -> fetch (PDO::FETCH_ASSOC)){
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
								<a href='php/<?php echo $filepath.$abstract;?>' target="_blank" style="color:red; font-size:13px;"><?php echo $title;?>a
								<?php
								}
								else if($complete!="" && ($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected') && $abstract==""){
								?>
								<a href='#' onclick='notapproved(event);' style="color:#000080;"><?php echo $title;?>b
								<?php
								}
								else if($complete!="" && $request_status=='Approved'){
								?>
								<a href='php/<?php echo $filepath.$complete;?>' target="_blank" style="color:#000080;"><?php echo $title;?>c
								
								<?php
								}
								else if($complete!="" && $abstract != "" &&($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected')){
								?>
								<a href='php/<?php echo $filepath.$abstract;?>' target="_blank" style="color:red;"><?php echo $title;?>d
								
								<?php
								}
								else if($complete!="" && $abstract != "" && $request_status == 'Approved'){
								?>
								<a href='php/<?php echo $filepath.$complete;?>' target="_blank" style="color:#000080;"><?php echo $title;?>e
								
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
							<a href='#' style='margin-bottom:25px;' title='Request Complete Access' class='request-access'id='thesis_<?php echo $thid;?>' onclick='request_thesis(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/request.png'></a>
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
					echo "<center style='color:red'>No Thesis Found<center>";
			}
		?>	</div>
</body>
</html>