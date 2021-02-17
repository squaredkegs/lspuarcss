<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['save_edit'])){
		$new_email = $_POST['new_email'];
		$check = $db -> prepare ("SELECT email FROM admin_tbl WHERE email=:new_email");
		$check -> bindParam (":new_email", $new_email);
		$check -> execute();
		$numrow = $check -> rowCount();
		if($numrow==0){
			$change = $db -> prepare ("UPDATE admin_tbl SET email=:email WHERE admin_id=:aid");
			$change -> bindParam (":email", $new_email);
			$change -> bindParam (":aid", $aid);
			$change -> execute();
			if($change){
				echo 
					"
					<script>
					alert('Email Successfully Change!');
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
			alert('Email Already In Use!');
			window.location.href='../edit_account.php';
			</script>
			";
		}
	}
	
?>