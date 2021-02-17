<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	if(isset($_POST['flname']) && isset($_POST['slname'])){
		$flname = $_POST['flname'];
		$slname = $_POST['slname'];
		$query = $db -> prepare ("UPDATE stud_bas SET status='Registered' WHERE campus = :campus AND department = :department AND status = 'Expired' AND (lname >= :first_letter AND lname < char(ascii(:second_letter) + 1))");
		$query -> bindParam (":campus", $rcampus);
		$query -> bindParam (":department", $rdepartment);
		$query -> bindParam (":first_letter", $flname);
		$query -> bindParam (":second_letter", $slname);
		$query -> execute();
		
	}

?>