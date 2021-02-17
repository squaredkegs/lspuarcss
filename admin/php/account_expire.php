<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");

	$_SESSION['LAST_ACTIVITY'] = time();	
	$date = date("Y-m-d H:i:s");
	if(isset($_POST['submit'])){
		$schid = $_POST['schid'];
		$new_date = $_POST['new_expire'];
		$old_date = $_POST['old_expire'];
		$str_new_date = strtotime($new_date);
		$str_old_date = strtotime($old_date);
		//echo "test";
		
		if($str_new_date>$date){
				$query = $db -> prepare 
								("START TRANSACTION;
								UPDATE admin_tbl SET accnt_expire=:new_expire WHERE admin_id=:schid;
								INSERT INTO admin_activity (admin_id,object_id,activity,stat_date,object) VALUES (:aid,:oid,:activity,:date,:object);
								COMMIT;");
				$query -> execute(array(
							"new_expire" => $new_date,
							"schid" => $schid,
							"aid" => $aid,
							"oid" => $schid,
							"activity" => "Change Account Date Expiration",
							"date" => $date,
							"object" => "Admin"
							));				
				if($query){
							echo "<script>
								alert('Worked');
								window.location.href='../admin_detail?detail=$schid'; 
								</script>";
					
				}/*
				$query = $db -> prepare ("UPDATE admin_tbl SET accnt_expire=:new_expire WHERE admin_id=:schid");
				$query -> bindParam (":new_expire", $new_date);
				$query -> bindParam (":schid", $schid);
				$query -> execute();
				if($query){
					$acc_qr = $db -> prepare ("INSERT INTO admin_activity (admin_id,object_id,activity,stat_date,object) VALUES (:aid,:oid,:activity,:date,:object)");
					$acc_qr -> execute(array(
					"aid" => $aid,
					"oid" => $schid,
					"activity" => "Change Account Date Expiration",
					"date" => $date,
					"object" => "Admin"
					));
					if($acc_qr){

							echo "<script>
								alert('Worked');
								window.location.href='../admin_detail?detail=$schid'; 
								</script>";
					}
				}
				else{
					echo "insert error";
				}*/
			}
			else{
				echo "<script>
						alert('Date must be newer than today');
						window.location.href='../admin_detail?detail=$schid';
					</script>";
			}
	}
	else{
		echo "Error!";
		die();
	}
?>