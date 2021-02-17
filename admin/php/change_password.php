<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['change_password'])){
		$old_password = $_POST['old_password'];
		$new_password = $_POST['new_password'];
		$renew_password = $_POST['renew_password'];
		$encrypted_newpassword = PASSWORD_HASH($new_password, PASSWORD_BCRYPT);
		$compare_password = password_verify($old_password, $rpassword);
		if($compare_password){
			if($new_password==$renew_password){
				$change_password = $db -> prepare ("UPDATE admin_tbl SET password=:password WHERE admin_id=:aid");
				$change_password -> bindParam(":password", $encrypted_newpassword);
				$change_password -> bindParam(":aid", $aid);
				$change_password -> execute();
				if($change_password){
					echo 
					"
					<script>
					alert('Password Successfully Change');
					window.location.href='../edit_account.php';
					</script>
					";
				}
				else{
					
					echo 
					"
					<script>
					alert('Error! Try Again Later!');
					window.location.href='../edit_account.php';
					</script>
					";
				}
			}
			else{
				
			echo 
					"
					<script>
					alert('New Password do not match');
					window.location.href='../edit_account.php';
					</script>
					";
			}
		}
		else{
			
			echo 
			"
			<script>
			alert('Old Password is Wrong');
			window.location.href='../edit_account.php';
			</script>
			";
		}
		
	}
	
?>