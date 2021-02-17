<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	
	$did = createUniqueId('department_id','department_tbl');
	$datetime = date("Y-m-d H:i:s");
	if(isset($_POST['submit'])){
		$newdepartment = $_POST['new_department'];
		
			$query = $db -> prepare ("SELECT * FROM department_tbl WHERE department=:dept");
			$query -> bindParam (":dept", $newdepartment);
			$query -> execute();
			$numrow = $query -> rowCount();
			if($numrow==0){
				$dept_query = $db -> prepare ("
								START TRANSACTION;
								INSERT INTO department_tbl (department_id,department) 			
								VALUES(:did,:dept);
								INSERT INTO	admin_activity(admin_id,object_id,object,activity,stat_date) VALUES (:aid,:oid,:object,:activity,:date);
								COMMIT;");
				$dept_query -> execute(array(
								"did" => $did,
								"dept" => $newdepartment,
								"aid" => $aid,
								":oid" => $did,
								":object" => "Department",
								":activity" => "Added Department",
								":date" => $datetime
								));
					if($dept_query){
						echo "<script>
							alert('Added!');
							window.location.href='../deptandcourse.php?add=success';
							</script>";
					}
					else{
						echo "
							<script>
							alert('Adding Failed, Database Error!');
							window.location.href='../deptandcourse.php?add=failure';
							</script>";
							
					}
			}
			else{
				echo "<script>
						window.location.href='../deptandcourse.php?error=department_already_exists';
						alert('Department Already Exists!');
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