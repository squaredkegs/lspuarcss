<?php
	
	if(file_exists("connection.php")){
		include_once ("connection.php");
	}
	else if(file_exists("../php/connection.php")){
		include_once ("../php/connection.php");
	}
	
	
	
	if(isset($_SESSION['log_user'])){
	$stud_username = $_SESSION['log_user'];
	$gtbscinfo = $db -> prepare ("SELECT stud_id FROM stud_bas WHERE stud_id = :stud_id");
	$gtbscinfo -> bindParam (':stud_id', $stud_username);
	$gtbscinfo -> execute ();
	$userdata = $gtbscinfo -> fetch();
	$getid = $userdata ['stud_id'];
	
	$getinfo = $db -> prepare ("SELECT * FROM stud_bas WHERE stud_id = :id");
	$getinfo -> bindParam (':id', $getid);
	$getinfo -> execute ();
	$stif_row = $getinfo -> fetch();
	
		$rfname = $stif_row['fname'];
		$rlname = $stif_row['lname'];
		$remail = $stif_row['email'];
		$rusername = $stif_row['username'];
		$rcampus = $stif_row['campus'];
		$rdepartment = $stif_row['department'];
		$ruser_id = $getid;
		$rcourse = $stif_row['course'];
		$rgender = $stif_row['gender'];
	
	
	$otherinfo = $db -> prepare ("SELECT * FROM stud_info WHERE stud_id = :secid");
	$otherinfo -> bindParam (':secid', $getid);
	$otherinfo -> execute ();
	$stud_info = $otherinfo -> fetch();
		$rnumber = $stud_info ['connum'];
		$rhometown = $stud_info['hometown'];
		$relem = $stud_info['elemschol'];
		$rhsschol = $stud_info['hsschol'];
		$raboutme = $stud_info ['aboutme'];
		$rpicname = $stud_info ['picture_name'];
		$rpicpath = $stud_info ['picture_path'];
		
	
	
	
	}
?>