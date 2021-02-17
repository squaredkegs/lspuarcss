<?php

include_once ("connection.php");
include_once ("querydata.php");
if(isset($_POST['submit'])){	
	
	$dept = $_POST['select_dept'];
	$course = $_POST['select_course'];
	
	if(isset($_POST['select_dept']) && 
	($course == null or $course == "")){
	header("location:../?dept=$dept");
	}
	else if(isset($_POST['select_course']) && ($dept == null or $dept == "")){
	header("location:../?course=$course");
		
	}
	else if(isset($_POST['select_course']) && isset($_POST['select_dept'])){
	
	header("location:../?dept=$dept&course=$course");
	}
	
}
?>