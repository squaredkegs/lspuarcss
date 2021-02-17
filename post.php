<?php

	include_once ("php/connection.php");
	include_once ("php/function.php");
	include_once ("php/querydata.php");

	if(isset($_GET['var'])){
		$variable = $_GET['var'];
	
		if(isset($_GET['sort'])){
			$sort = $_GET['sort'];
		}
	
	
		$variable = $_GET['var'];
		$variable_to_array = explode(" ",$variable);
		$echo_array = implode(" +",$variable_to_array);
		$count = count($variable_to_array);
		if($count==1){
			$post_variable = "+".$variable;
		}
		else if($count==2){
			$first_variable = $variable_to_array[0];
			$second_variable = $variable_to_array[1];
			$post_variable = "+".$first_variable." +".$second_variable;
		}
		else if($count==3){
			$first_variable = $variable_to_array[0];
			$second_variable = $variable_to_array[1];
			$third_variable = $variable_to_array[2];		
			$post_variable = "+(".$first_variable." ".$second_variable.")"." +".$third_variable;
		}
		else if($count>=4){
			$student_variable = "+".$echo_array;
			//$post_variable
		}
	}
	else {
		$variable = "Not Found";
	}

?>

<html>
<head>
</head>
<body>
		<div id="post">
		
		<?php
		
			$search_post_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE);");
			$search_post_query -> bindValue (":variable",$post_variable, PDO::PARAM_STR);
			$search_post_query -> execute();
			$post_numrow = $search_post_query -> rowCount();
				if($post_numrow>0){
		?>	
			<div style='width:300px;margin-left:20px;'>
				<form class='form-inline' action='php/filter_newsfeed_searach.php' method='POST'>
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
		</div>	
		<?php
				while($row = $search_post_query -> fetch (PDO::FETCH_ASSOC)){
					$title = $row['title'];
					$description = $row['description'];
		?>
				<?php echo $title;?><br/>
				
		<?php
				}
				
			}
			else{
					echo "<center style='color:red'>No Post Found</center>";
			}
		?>	</div>
</body>
</html>