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

			<select id='select_course' name='course' class='form-control' onchange='select_course(this);'>
				<option value=''>Course</option>
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
			
			<form action='php/save_edit_course.php' method='POST'>
			<input type='text' id='new_course' name='new_name' class='form-control' style='margin-top:10px;margin-bottom:10px;'>
			<input type='hidden' id='old_course' name='old_name' value=''>
			<input type='submit'  name='submit_new_course_name' value='Save Edit' class='btn btn-info form-control' style='margin-bottom:10px;'>
			</form>
			<button class='btn btn-danger form-control'><a href='php/delete_course' style='color:white;margin-top:15px;'>Delete Course</a></button>
	<?php		
		}
	}

?>