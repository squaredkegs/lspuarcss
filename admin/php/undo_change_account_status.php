<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	if(isset($_POST['sid']) && isset($_POST['name'])){
		$sid = $_POST['sid'];
		$name = $_POST['name'];
		if($name=='Reactivation'){
			$query = $db -> prepare ("UPDATE stud_bas SET status='Expired'  WHERE stud_id = :sid");
			$query -> bindParam (":sid", $sid);
			$query -> execute();
		}
		else if($name=='Inactive'){
			$query = $db -> prepare ("UPDATE stud_bas SET status='Registered'  WHERE stud_id = :sid");
			$query -> bindParam (":sid", $sid);
			$query -> execute();
			
		}
	}

?>