<?php
include ("connection.php");
include_once ("adminfunction.php");	
include_once ("password_hash.php");
$_SESSION['LAST_ACTIVITY'] = time();

	if(isset($_POST['log']))
	{
		$admin	= $_POST['admin'];
		$password = $_POST['adpassword'];
		$datetime = date("Y-m-d H:i:s");
		$query = $db -> prepare ("SELECT admin_account FROM admin_tbl WHERE admin_account = :admin");
		$query -> bindParam (":admin", $admin);
		$query -> execute();
		$rowres = $query -> rowCount();
		if($rowres>0)
		{
			$secquery = $db -> prepare ("SELECT * FROM admin_tbl WHERE admin_account = :realadmin");
			$secquery -> bindParam (":realadmin", $admin);
			$secquery -> execute();
			$getdata = $secquery -> fetch();
			$realpassword = $getdata['password'];
			$realacc = $getdata['admin_account'];
			$realid = $getdata['admin_id'];
			$account_expire = $getdata['accnt_expire'];
			$status = $getdata['status'];
			$string_account_expire = strtotime($account_expire);
			$string_date = strtotime ($datetime);
			if(($string_date>=$string_account_expire) OR $status=='Expired'){
					echo "Loading... Please Wait";
					echo 
					"
					<script>
					window.location.href='../adminlog.php?err=accountxpire';
					</script>
					";
					die();
			}
			
			$encrypt_db_pass = password_verify($password, $realpassword);
			if($realacc == $admin && $encrypt_db_pass)
			{
				
				$_SESSION['admin_time_log'] = createSessionId();
				$lid = $_SESSION['admin_time_log'];
				$time_query = $db -> prepare ("INSERT INTO admin_logtime (datetime_in,admin_id,log_id)
					VALUES (:date,:id,:lid)");
				$time_query -> bindParam (":date", $datetime);
				$time_query -> bindParam (":id", $realid);
				$time_query -> bindParam (":lid", $lid);
				$time_query -> execute();
					if($time_query)
					{
						echo "Please Wait... Loggingg In";
						$_SESSION['admin_log'] = $realid;
						header("location:../index.php");
						die();
					}
					else
					{
						echo "Error Log Time!";						
					}
			}
			else
			{
				
					echo "Loading... Please Wait";
					echo 
					"
					<script>
					
					window.location.href='../adminlog.php?err=invlps';
					</script>
					";
			}
			
				
		}
		else if($rowres==0)
		{
			$adminmail = $admin;
			$thquery = $db -> prepare ("SELECT * FROM admin_tbl WHERE email = :emadmin");
			$thquery -> bindParam (":emadmin", $adminmail);
			$thquery -> execute();
			$gtdata = $thquery -> fetch();
			$repassword = $gtdata['password'];
			$email = $gtdata['email'];
			$realadminaccount = $gtdata['admin_account'];
			$realadminid = $gtdata['admin_id'];
			$adminencryptedpassword = password_verify($password, $repassword);
			
			if($email == $adminmail && $adminencryptedpassword)
			{
				
				$_SESSION['admin_time_log'] = createSessionId();
				$lid = $_SESSION['admin_time_log'];
				$time_quer = $db -> prepare ("INSERT INTO admin_logtime (datetime_in,admin_id,log_id)
					VALUES (:date,:id,:lid)");
				$time_quer -> bindParam (":date", $datetime);
				$time_quer -> bindParam (":id", $realadminid);
				$time_quer -> bindParam (":lid", $lid);
				$time_quer -> execute();
				echo "Please Wait... Logging - In";
				
				$_SESSION['admin_log'] = $realadminid;
				header("location:../index.php");
				die();
			}
			else
			{
				echo "Loading... Please Wait";
				echo 
					"
					<script>
					window.location.href='../adminlog.php?err=invlps';
					</script>
					";
			}

		}
		else
		{
			
			echo "Loading... Please Wait";
			header("location:../adminlog.php?err=invlps");
		}
	}
	else
	{
		header("location:../index.php");
	}
	
?>