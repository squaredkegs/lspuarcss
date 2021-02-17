<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	
	
	if(isset($_POST['change_email'])){
		$new_email = $_POST['new_email'];
		$old_email = $_POST['old_email'];
		if($new_email=="" or $new_email==null){
			echo 
					"
					<script>
					alert('New Email Empty!');
					window.location.href='../settings?lct=account';
					</script>";
		
		}
		else{
			$check_email_owner = $db -> prepare ("SELECT EXISTS(SELECT stud_id FROM stud_bas WHERE stud_id=:sid AND email=:email) as count");
			$check_email_owner -> bindParam (":email", $old_email);
			$check_email_owner -> bindParam (":sid", $getid);
			$check_email_owner -> execute();
			$result = $check_email_owner -> fetch();
			$count = $result['count'];
			if($count==1){
				$check_if_existing = $db -> prepare ("SELECT EXISTS (SELECT email FROM stud_bas WHERE email=:new_email) as count");
				$check_if_existing -> bindParam (":new_email", $new_email);
				$check_if_existing -> execute();
				$result_if_exist = $check_if_existing -> fetch();
				$numrow = $result_if_exist['count'];
				if($numrow>0){
					echo 
						"<script>
						window.location.href='../settings?lct=account&chngml=exist';
						</script>";
				}
				else{
				$change_email = $db -> prepare ("UPDATE stud_bas SET email=:new_email WHERE stud_id=:sid");
				$change_email -> bindParam (":sid", $getid);
				$change_email -> bindParam (":new_email", $new_email);
				$change_email -> execute();
					if($change_email){
						echo "
						<script>
						window.location.href='../settings?lct=account&chngml=success';
						</script>";
					}
					else{
						echo "
						<script>
						alert('Database Error!\n Try again Later');
						window.location.href='../settings?lct=account';
						</script>";
					}
				}
				
			}
			else{
				echo "
					<script>
					alert('Session not found\n or old email doesn't match with your account!\n Try again later');
					window.location.href='../settings?lct=account';
					</script>";
			}
		}
	}
	else{
		echo "what";
	}

?>