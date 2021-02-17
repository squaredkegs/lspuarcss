<?php

	include_once ("../php/connection.php");
	include_once ("../php/querydata.php");
	include_once ("../php/function.php");
	
	if(isset($_POST['department_filter'])){
		$dept = $_POST['department_filter'];
		$query = $db -> prepare ("SELECT department_id as did FROM department_tbl WHERE department=:dept");
		$query -> bindParam (":dept", $dept);
		$query -> execute();
		$res = $query -> fetch();
		$did = $res['did'];
			$get_course = $db -> prepare ("
					SELECT course_tbl.course_id as courid, course_tbl.course as course 
					FROM course_connect
					LEFT JOIN course_tbl
					ON course_tbl.course_id = course_connect.course_id
					WHERE course_connect.department_id = :did
					");
			$get_course -> bindParam (":did", $did);
			$get_course -> execute();
	?>
		<select style="margin-top:10px;margin-bottom:10px;"class="form-control" name="select_course" id='select_course'>
			
			<option value='' class='editbox3'>Select Course</option>
	<?php
			while($row = $get_course -> fetch(PDO::FETCH_ASSOC)){
				$course = $row['course'];
	?>
				<option value='<?php echo $course;?>'><?php echo $course;?></option>
	<?php
			}
			
	?>
		</select>
		
		<?php
	}
		?>
	