<?php

	header('Content-type: application/json');
	$_SESSION['LAST_ACTIVITY'] = time();
	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	$date = date("Y-m-d H:i:s");
	$exid = createUniqueId('expire_id','admin_expire_passwords');
	$expire_query_status = 'success';
	if(isset($_POST['password']) && isset($_POST['repass'])){
		$password = $_POST['password'];
		$encryptpassword = password_hash($password, PASSWORD_BCRYPT);
		$check_query = $db -> prepare ("SELECT password FROM admin_expire_passwords WHERE campus = :campus AND department = :dept");
		$check_query -> bindParam (":campus", $rcampus);
		$check_query -> bindParam (":dept", $rdepartment);
		$check_query -> execute();
		$numrow_check = $check_query -> rowCount();
		if($numrow_check==0){
		$query = $db -> prepare ("INSERT INTO admin_expire_passwords (expire_id,campus,department,password) VALUES (:exid,:campus,:dept,:pass)");
		$query -> execute(array(
			"exid" => $exid,
			"campus" => $rcampus,
			"dept" => $rdepartment,
			"pass" => $encryptpassword,
		));
	
			if($query){
				$expire_query_status = 'success';
			}
		}
		
		else{
			die();
		}
		
	echo json_encode(array('response' => $expire_query_status));
	}


?>