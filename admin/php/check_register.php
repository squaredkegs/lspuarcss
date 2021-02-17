<?php

	include_once ("connection.php");
	
	if(isset($_POST['email'])){
		$email = $_POST['email'];
		$query = $db -> prepare ("SELECT email FROM admin_tbl WHERE email=:email");
		$query -> bindParam (":email", $email);
		$query -> execute();
		$numrow = $query -> rowCount();
		if($numrow>0){
			echo "<span style='color:red;font-size:12px;'>Email Already in use</span>";
		}
		else{
			echo "<span style='color:green;font-size:12px;'>Email Available</span>";
		}
	}
	
	if(isset($_POST['username'])){
		$username = $_POST['username'];
		$query = $db -> prepare ("SELECT admin_account FROM admin_tbl WHERE admin_account=:username");
		$query -> bindParam (":username", $username);
		$query -> execute();
		$numrow = $query -> rowCount();
		if($numrow>0){
			echo "<span style='color:red;font-size:12px;'>username Already in use</span>";
		}
		else{
			echo "<span style='color:green;font-size:12px;'>username Available</span>";
		}
	}
	
?>