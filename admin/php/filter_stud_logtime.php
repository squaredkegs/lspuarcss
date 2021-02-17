<?php

	include_once ("connection.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['filter_stud_logtime'])){
		$year = $_POST['year'];
		$month = $_POST['month'];
		if($month != null or $month != ""){
			header("location:../student_log?year=$year&month=$month");
		}
		else{
			header("location:../student_log?year=$year");
		}
	
	}

?>