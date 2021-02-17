<?php


	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");
	$datetime = date("Y-m-d");
	$expire_query = $db -> prepare ("SELECT campus, department, expire_date FROM admin_expire_passwords");
	$expire_query -> execute();
	$string_date = strtotime($datetime);
	while($expire_row = $expire_query -> fetch(PDO::FETCH_ASSOC)){
		$qqcampus = $expire_row['campus'];
		$qqdepartment = $expire_row['department'];
		$expire_date = $expire_row['expire_date'];
		$string_expire_date = strtotime($expire_date);
	$get_students = $db -> prepare ("SELECT fname, lname, stud_id FROM stud_bas WHERE campus = :campus AND department = :department");
	$get_students -> bindParam (":campus", $qqcampus);
	$get_students -> bindParam (":department", $qqdepartment);
	$get_students -> execute();
		while($results = $get_students -> fetch(PDO::FETCH_ASSOC)){
		$sid = $results['stud_id'];
			if($string_date>=$string_expire_date){
				$expire_accounts = $db -> prepare ("UPDATE stud_bas SET status = 'Expired' WHERE stud_id = :sid AND status = 'Registered'");
				$expire_accounts -> bindParam (":sid", $sid);
				$expire_accounts -> execute();
			}
		}
	}
?>	




