<?php
							
include("connection.php");
	if(isset($_SESSION['log_user_time'])){
		
		$sid = $_SESSION['log_user'];
		$slid = $_SESSION['log_user_time'];
		$date = date("Y-m-d H:i:s");
		$query = $db -> prepare ("UPDATE stud_logtime SET logging_out = :date WHERE stud_id = :sid AND stud_logid = :slid");
		$query -> bindParam (":date",$date);
		$query -> bindParam (":sid",$sid);
		$query -> bindParam (":slid",$slid);
		$query -> execute();
			if($query){
				unset($_SESSION['log_user']);
				unset($_SESION['log_user_time']);
				header("location:../index.php");
			}
	}
	else{
			unset($_SESSION['log_user']);
			header("location:../index.php");
		
	}
?>