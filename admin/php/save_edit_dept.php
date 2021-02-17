<?php

	include_once ("connection.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['submit_new_dept_name'])){
		$old_name = $_POST['old_name'];
		$new_name = $_POST['new_name'];
		$check = $db -> prepare ("SELECT department FROM department_tbl WHERE department = :dept");
		$check -> bindParam (":dept", $new_name);
		$check -> execute();
		$numrow = $check -> rowCount();
		if($numrow==0){
			
			$query = $db -> prepare ("UPDATE department_tbl SET department = :new_dept WHERE department =:old_dept");
			$query -> bindParam (":new_dept", $new_name);
			$query -> bindParam (":old_dept", $old_name);
			$query -> execute();
			if($query){
				$multiple_update = $db -> prepare ("
					START TRANSACTION;
					UPDATE admin_expire_password SET department = :new_dept WHERE department = :old_dept;
					UPDATE admin_tbl SET department = :new_dept WHERE department = :old_dept;
					UPDATE  newsfeed SET department = :new_dept WHERE department = :old_dept;
					UPDATE stud_bas SET department = :new_dept	WHERE department = :old_dept;
					UPDATE thesis_arch SET department = :new_dept WHERE department = :old_dept;
					COMMIT;
					");
				$multiple_update -> bindParam (":new_dept", $new_name);
				$multiple_update -> bindParam (":old_dept", $old_name);
				$multiple_update -> execute();
				
			echo 
				"
				<script>
				alert('Department Edited!');
				window.location.href='../view_dept.php';
				</script>
				";
				
			}
			else{
				echo 
					"
					<script>
					alert('Error! Try Again');
					window.loocation.href='../view_dept.php';
					</script>
					";
			}
		}
		else{
			echo 
				"
				<script>
				alert('Department Already Exists!');
				window.location.href='../view_dept.php';
				</script>
				";
		}
	}

?>