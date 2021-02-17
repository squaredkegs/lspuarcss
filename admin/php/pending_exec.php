<?php
include ("connection.php");
$date = date("Y-m-d H:i:s"); 
$aid = $_SESSION['admin_log'];
$_SESSION['LAST_ACTIVITY'] = time();	
if(isset($_POST['accept']))
{
	$stud_id = $_POST['studid'];
	$get_query = $db -> prepare ("SELECT * FROM stud_bas WHERE stud_id=:sid");
	$get_query -> bindParam (":sid", $stud_id);
	$get_query -> execute();
	$result = $get_query -> fetch();
	$fname = $result['fname'];
	$lname = $result['lname'];
	$email = $result['email'];
	$query = $db -> prepare ("
							START TRANSACTION;
							UPDATE stud_bas SET status=:status
							WHERE stud_id = :studid ;
							INSERT INTO admin_activity (admin_id,object_id,activity,stat_date,object) VALUES (:aid,:oid,:activity,:date,:object);
							INSERT INTO stud_info (stud_id) VALUES (:students_new_id);
							COMMIT;");
	$query -> execute(array(
				"status" => "Registered",
				"studid" => $stud_id,
				"aid" => $aid,
				"oid" => $stud_id,
				"activity" => "Accepted Registration",
				"date" => $date,
				"object" => "Student",
				"students_new_id" => $stud_id
			));
			
	$isam_query = $db2 -> prepare ("
							INSERT INTO isam_stud_bas (stud_id,fname,lname,email)
							VALUES (:sid,:fname,:lname,:email)");
	$isam_query -> execute(array(
					"sid" => $stud_id,
					"fname" => $fname,
					"lname" => $lname,
					"email" => $email
					));
		if($query && $isam_query)
		{			$main = mkdir("../../image/profile/".$stud_id, 0777, true);
					$path = ("../../image/profile/".$stud_id);
					if(is_dir($path))
					{
						$pic = mkdir("../../image/profile/".$stud_id."/profile_picture", 0777, true);
					}
				header("location:../pending.php");
				echo "worked";
		}
		else{
			echo "error";
		}
}
else if(isset($_POST['reject']))
{
	$stud_id = $_POST['studid'];
	$query = $db -> prepare("
							START TRANSACTION;
							UPDATE stud_bas SET status='Rejected' 
							WHERE stud_id = :studid;
							INSERT INTO admin_activity (admin_id,object_id,activity,stat_date,object) VALUES (:aid,:oid,:activity,:date,:object);
							COMMIT;");
	$query -> execute (array(
				"studid" => $stud_id,
				"aid" => $aid,
				"oid" => $stud_id,
				"activity" => "Rejected Registration",
				"date" => $date,
				"object" => "Student"
			
				));
		if($query)
		{
			header("location:../pending.php");
		}	
}

/*
if(isset($_POST['remove_ban']))
{
	

	$sid = $_POST['sid'];
	$query = $db -> prepare ("UPDATE stud_bas SET status='Registered' WHERE stud_id=:sid");
	$query -> bindParam (":sid", $sid);
	$query -> execute();
		if($query)
		{
			$sec_quer = $db -> prepare ("INSERT INTO admin_approve (admin_id,stud_id,acc_stat,stat_date) VALUES (:aid,:sid,:acc_stat,:date)");
			$sec_quer -> execute(array(
				"aid" => $aid,
				"sid" => $sid,
				"acc_stat" => "Remove Ban",
				"date" => $date
			));

			header("location:../banned_list.php");
		}
		else
		{
			echo "Error";	
		}
}


if(isset($_POST['ban_sid'])){
	$sid = $_POST['ban_sid'];
	
	$query = $db -> prepare ("UPDATE stud_bas SET status='Banned' WHERE stud_id=:sid");
	$query -> bindParam (":sid", $sid);
	$query -> execute();
		if($query){
			$sec_quer = $db -> prepare ("INSERT INTO admin_approve (admin_id,stud_id,acc_stat,stat_date) VALUES (:aid,:sid,:acc_stat,:date)");
			$sec_quer -> execute(array(
				"aid" => $aid,
				"sid" => $sid,
				"acc_stat" => "Banned",
				"date" => $date
			));
		}
	
}

if(isset($_POST['unban_sid'])){
	$sid = $_POST['unban_sid'];
			
		$query = $db -> prepare ("UPDATE stud_bas SET status='Registered' WHERE stud_id=:sid");
		$query -> bindParam (":sid", $sid);
		$query -> execute();
			if($query){
				$sec_quer = $db -> prepare ("INSERT INTO admin_approve (admin_id,stud_id,acc_stat,stat_date) VALUES (:aid,:sid,:acc_stat,:date)");
				$sec_quer -> execute(array(
					"aid" => $aid,
					"sid" => $sid,
					"acc_stat" => "Remove Ban",
					"date" => $date
				));
			}	
	
}*/
?>