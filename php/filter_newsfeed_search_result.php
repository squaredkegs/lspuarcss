<?php

include_once ("connection.php");
include_once ("querydata.php");
if(isset($_POST['submit'])){	
	$var = $_POST['var'];
	$sort = $_POST['sort'];
	$campus = $_POST['campus'];
	$department = $_POST['department'];
	$course = $_POST['course'];
	$year = $_POST['year'];
	$other_year = $year;
	if(isset($_POST['other_year'])){
		$other_year = $_POST['other_year'];
	}
	//dept only
	if(isset($_POST['department']) && 
	($course == null or $course == "") && 
	($campus == null or $campus == "") &&
	($year == null or $year == "")){
	header("location:../result.php?var=$var&sort=$sort&department=$department");
	}
	//dept and course
	else if(isset($_POST['department']) && 
	isset($_POST['course']) && 
	($campus == null or $campus == "") &&
	($year == null or $year == "")){
	header("location:../result.php?var=$var&sort=$sort&department=$department&course=$course");
	}
	//dept and year
	else if(isset($_POST['department']) && 
	isset($_POST['year']) && 
	($course == null or $course == "") &&
	($campus == null or $campus == "")){
	header("location:../result.php?var=$var&sort=$sort&department=$department&year=$year&other_year=$other_year");	
	}
	//dept and campus
	else if(isset($_POST['department']) && 
	isset($_POST['campus']) && 
	($course == null or $course == "") &&
	($year == null or $year == "")){
	header("location:../result.php?var=$var&sort=$sort&department=$department&campus=$campus");	
	}
	//dept and course and campus
	else if(isset($_POST['department']) && 
	isset($_POST['course']) && 
	isset($_POST['campus']) &&
	($year == null or $year == "")){
	header("location:../result.php?var=$var&sort=$sort&department=$department&course=$course&campus=$campus");	
	}
	//dept and course and year
	else if(isset($_POST['department']) && 
	isset($_POST['course']) && 
	isset($_POST['year']) &&
	($campus == null or $campus == "")){
	header("location:../result.php?var=$var&sort=$sort&department=$department&course=$course&year=$year&other_year=$other_year");	
	}
	//dept and campus and year
	else if(isset($_POST['department']) && 
	isset($_POST['campus']) && 
	isset($_POST['year']) &&
	($course == null or $course == "")){
	header("location:../result.php?var=$var&sort=$sort&department=$department&campus=$campus&year=$year&other_year=$other_year");	
	}
	//All
	else if(isset($_POST['department']) && 
	isset($_POST['course']) && 
	isset($_POST['campus']) &&
	isset($_POST['year'])){
	header("location:../result.php?var=$var&sort=$sort&department=$department&course=$course&campus=$campus&year=$year&other_year=$other_year");	
	
	}
	
}
?>