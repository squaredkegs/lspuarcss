<?php

	include_once ("connection.php");
	if(isset($_POST['dept'])){
		$dept = $_POST['dept'];
		$query = $db -> prepare ("SELECT department_id as did FROM department_tbl WHERE department = :dept");
		$query -> bindParam (":dept", $dept);
		$query -> execute();
		$res = $query -> fetch();
		$did = $res['did'];
	?>

			<label>Course</label>
			<select id='select_course' name='course' class='form-control'>
				<option value=''>Select Course</option>
	<?php
			
			$qr_cr = $db -> prepare ("SELECT course_tbl.course as course FROM course_connect LEFT JOIN course_tbl ON
			course_tbl.course_id = course_connect.course_id
			WHERE department_id=:did");
			$qr_cr -> bindParam (":did", $did);
			$qr_cr -> execute();
			$qr_num = $qr_cr -> rowCount();
		if($qr_num>0){
			while($row = $qr_cr -> fetch(PDO::FETCH_ASSOC)){
				$course = $row['course'];
	?> 
	
				<option value="<?php echo $course;?>"><?php echo $course;?></option>
	
	<?php
			}
	?>
			</select>
			
	<?php		
		}
	}

?>