<?php
	header('Content-type: application/json');
	include_once ("connection.php");
	include_once ("queryadmindata.php");
	if(isset($_POST['expire_password'])){
		$password_check = "false";
	
		$exp_password = $_POST['expire_password'];
		$get_data = $db -> prepare ("SELECT password FROM admin_expire_passwords WHERE campus = :campus AND department = :department");
		$get_data -> bindParam (":campus", $rcampus);
		$get_data -> bindParam (":department", $rdepartment);
		$get_data -> execute();
		$result = $get_data -> fetch();
		$realpassword = $result['password'];
		$encrypt_db_pass = password_verify ($exp_password, $realpassword);
		if($encrypt_db_pass){
			$password_check = "true";
		}
		echo json_encode(array ('response' => $password_check));
		exit ();
	}

?>