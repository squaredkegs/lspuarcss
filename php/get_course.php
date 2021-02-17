

<?php
	include_once ("connection.php");
	if(isset($_POST['department'])){
		$department = $_POST['department'];
		
		$get_dept_id = $db -> prepare ("SELECT department_id as did FROM department_tbl WHERE department=:dept");
		$get_dept_id -> bindParam (":dept", $department);
		$get_dept_id -> execute();
		$res_did = $get_dept_id -> fetch();
		$did = $res_did['did'];

?>
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
<?php
	}
?>	