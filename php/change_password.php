<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	
	if(isset($_POST['submit'])){
		
		$old_pass = $_POST['old_pass'];
		$new_pass = $_POST['new_pass'];
		$renew_pass = $_POST['renew_pass'];
		$encrypted_newpassword = PASSWORD_HASH($new_pass, PASSWORD_BCRYPT);
		$check_pass = $db -> prepare ("SELECT password FROM stud_bas WHERE stud_id=:sid");
		$check_pass -> bindParam (":sid", $getid);
		$check_pass -> execute();
		$result = $check_pass -> fetch();
		$encrypted_password = $result['password'];
		$compare_password = password_verify($old_pass, $encrypted_password);
		$numrow = $check_pass -> rowCount();
		if($compare_password){
				$pass_length = strlen($new_pass);
			if($pass_length>=7){
				if($new_pass==$renew_pass){
					$change_pass = $db -> prepare ("UPDATE stud_bas SET password=:password WHERE stud_id=:sid");
					$change_pass -> bindValue (":password",$encrypted_newpassword);
					$change_pass -> bindValue (":sid", $getid);
					$change_pass -> execute();
					if($change_pass){
						echo
						"
						<script>
						window.location.href='../settings?lct=pass&stat=success';
						</script>
						";
					}
					else{
						echo
							"
							<script>
							alert('Database Error!');
							window.location.href='../settings';
							</script>
							";
					}
				}
				else{
					echo 
						"
						<script>
						window.location.href='../settings?lct=pass&stat=notmatch';
						</script>
						";
						
						//Unmatch new password
				}
			}
			else{
				echo 
					"
					<script>
					window.location.href='../settings?lct=pass&stat=tooshort';
					</script>
					";
			}
		}
		else{
			echo 
					"
					<script>
					window.location.href='../settings?lct=pass&stat=wrongpass';
					</script>
					";
		}
	}
?>