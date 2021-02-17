<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	$_SESSION['LAST_ACTIVITY'] = time();
		
	$cid = createUniqueId('course_id','course_tbl');
	$datetime = date("Y-m-d H:i:s");
	if(isset($_POST['submit'])){
		$newcourse = $_POST['new_course'];
		$department = $rdepartment;
		$get_dept_id = $db -> prepare ("SELECT department_id FROM department_tbl WHERE department=:department");
		$get_dept_id -> bindParam(":department", $department);
		$get_dept_id -> execute();
		$result = $get_dept_id -> fetch();
		$did = $result['department_id'];
			$query = $db -> prepare ("SELECT course FROM course_tbl WHERE course=:course");
			$query -> bindParam (":course", $newcourse);
			$query -> execute();
			$numrow = $query -> rowCount();
			if($numrow==0){
				$course_query = $db -> prepare ("
								START TRANSACTION;
								INSERT INTO course_tbl (course_id,course) 			
								VALUES(:cid,:course);
								INSERT INTO	admin_activity(admin_id,object_id,object,activity,stat_date) VALUES (:aid,:oid,:object,:activity,:date);
								INSERT INTO
								course_connect (course_id,department_id) 
								VALUES (:cid2,:did2);
								COMMIT;");
				$course_query -> execute(array(
								"cid" => $cid,
								"course" => $newcourse,
								"aid" => $aid,
								":oid" => $cid,
								":object" => "Course",
								":activity" => "Added Course",
								":date" => $datetime,
								"cid2" => $cid,
								"did2" => $did,
								));
					if($course_query){
						echo "<script>
							alert('Added!');
							window.location.href='../deptandcourse.php?add=success';
							</script>";
						
							
					}
					else{
						echo "
							<script>
							window.location.href='../deptandcourse.php?add=failure';
							alert('Adding Failed, Database Error!');
							</script>";
							
					}
			}
			else{
				echo "<script>
						window.location.href='../deptandcourse.php?error=department_already_exists';
						alert('Course Already Exists!');
					  </script>";
						
			}
	}
	else{
		echo 
			"<script>
			alert('Website Error');
			</script>";
	}

?>