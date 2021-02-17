<?php

	include_once ("connection.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['submit_new_course_name'])){
		$old_name = $_POST['old_name'];
		if($old_name!="" or $old_name!=null){
		
		$new_name = $_POST['new_name'];
		$check = $db -> prepare ("SELECT course FROM course_tbl WHERE course = :dept");
		$check -> bindParam (":dept", $new_name);
		$check -> execute();
		$numrow = $check -> rowCount();
		if($numrow==0){
			$query = $db -> prepare ("UPDATE course_tbl SET course = :new_course WHERE course =:old_course");
			$query -> bindParam (":new_course", $new_name);
			$query -> bindParam (":old_course", $old_name);
			$query -> execute();
			if($query){
				$multiple_update = $db -> prepare ("
					START TRANSACTION;
					UPDATE  newsfeed SET course = :new_course WHERE course = :old_course;
					UPDATE thesis_arch SET course = :new_course WHERE course = :old_course;
					UPDATE stud_bas SET course = :new_course	WHERE course = :old_course;
					
					COMMIT;
					");
				$multiple_update -> bindParam (":new_course", $new_name);
				$multiple_update -> bindParam (":old_course", $old_name);
				$multiple_update -> execute();
				echo 
				"
				<script>
				alert('Course Edited!');
				window.location.href='../view_dept.php';
				</script>
				";
				
			}
		}
		else{
			echo 
				"
				<script>
				alert('Course Already Exists!');
				window.location.href='../view_dept.php';
				</script>
				";
		}
		}
		else{
			echo 
				"
				<script>
				alert('No Course!');
				window.location.href='../view_dept.php';
				</script>
				";
			
		}
	}

?>