<?php
session_start();
include ("connection.php");
$sid = $_SESSION['log_user'];

if(isset($_POST['save'])){

	
	$campus = $_POST['campus'];
	$department = $_POST['department'];
	$gender = $_POST['gender'];
	$course = $_POST['course'];
		$sec_query = $db -> prepare ("UPDATE stud_bas SET campus=:camp, gender =:gender, department=:dept, course=:course WHERE stud_id=:sid");
		$sec_query -> execute(array(
			"gender" => $gender,
			"camp" => $campus,
			"sid" => $sid,
			"course" => $course,
			"dept" => $department
		));		
		if($sec_query){
			echo "test";
			
		}
		else{
			echo "did not";
		}
}

if(isset($_POST['other_save'])){
	
	$aboutme = $_POST['aboutme'];
	$elem = $_POST['elementary'];
	$hs = $_POST['highschool'];
	$hometown = $_POST['hometown'];
		$query = $db -> prepare("UPDATE stud_info SET aboutme=:aboutme, hsschol=:hs, hometown=:htown, elemschol=:elem  WHERE stud_id = :sid");
		$query -> bindParam (":sid", $sid);
		$query -> bindParam (":aboutme", $aboutme);
		$query -> bindParam (":hs", $hs);
		$query -> bindParam (":elem", $elem);
		$query -> bindParam (":htown", $hometown);
		$query -> execute(array(
					"sid" => $sid,
					"aboutme" => $aboutme,
					"hs" => $hs,
					"elem" => $elem,
					"htown" => $hometown
				
		));
			if($query){
						echo "success";
					}
					else{
						echo "not";	
					}

}
?>


