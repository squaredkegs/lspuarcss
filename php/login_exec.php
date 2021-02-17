<?php

include_once ("connection.php");
include_once ("function.php");
$date = date("Y-m-d H:i:s");
$slid = createRandomId('stud_logid','stud_logtime');


if(isset($_POST['login']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$expire_date = 'false';
	$attempt = 'false';
	echo "Please Wait";
	if((!empty($_POST['username'])) && (!empty($_POST['username'])))
	{	
		
		
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
			$ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
			
		}
		$get_user_and_email = $db -> prepare ("SELECT username,email FROM stud_bas WHERE (username = :username OR email = :username)");
		$get_user_and_email -> bindParam (":username", $username);
		$get_user_and_email -> execute();
		$count_get_user_and_email = $get_user_and_email -> rowCount();
		
		if($count_get_user_and_email>0){
			$result_get_user_and_email = $get_user_and_email -> fetch();
			$dummy_username = $result_get_user_and_email['username'];
			$dummy_email = $result_get_user_and_email['email'];
			//check brute force
			$count_attempts = $db -> prepare ("SELECT * FROM stud_trylog WHERE (username = :username OR username = :email) AND ip = :ip AND datetime > NOW() - INTERVAL 30 MINUTE");
			$count_attempts -> bindParam(":username", $dummy_username);
			$count_attempts -> bindParam(":email", $dummy_email);
			$count_attempts -> bindParam(":ip", $ipAddress);
			$count_attempts -> execute();
			$count_attempts_numrow = $count_attempts -> rowCount();
			if($count_attempts_numrow>10){
				echo 
					"
					<script>
					window.location.href='../login.php?err=toomanyattempts';
					</script>
					";
				
				die();
			}
		}

		$query = $db->prepare("SELECT * FROM stud_bas WHERE username = :user");
		$query -> bindParam(':user', $username);
		$query -> execute();
		$rowcount = $query -> rowCount();
		
		if($rowcount>0)
		{
			$result = $query -> fetch();
			$realpassword = $result['password'];
			$realusername = $result['username'];
			$status = $result['status'];
			$sid = $result['stud_id'];
			$campus = $result['campus'];
			$department = $result['department'];
			
			//$new_real_password = crypt($realpassword, $existingHashdb) === existingHashdb;	
			$encryptedpassword = password_verify($password, $realpassword);
				if($encryptedpassword && $realusername == $username)
				{
					
					if($status == 'Expired'){
						echo 
							"
							<script>
							window.location.href='../login.php?err=accntexpired';
							</script>
							";
							die();
					}
					$get_expire = $db -> prepare ("SELECT expire_date FROM admin_expire_passwords WHERE campus = :campus AND department = :department ");
					$get_expire -> bindParam (":campus", $campus);
					$get_expire -> bindParam (":department", $department);
					$get_expire -> execute();
					$get_expire_count = $get_expire -> rowCount();
					if($get_expire_count>0){
						$get_expire_result = $get_expire -> fetch();
						$expire_date = $get_expire_result['expire_date'];
						if($expire_date != 'false'){
							$string_date = strtotime($date);
							$string_expire_date = strtotime($expire_date);
							if($string_date>=$string_expire_date){
								echo 
									"
									<script>
									window.location.href='../login.php?err=expired';
									</script>
									";
			
								die();
							}
						}
						
					}
					
					if($status!="Pending")
					{
						if($status=="Registered")
						{
							echo "Please Wait.. Logging In";
							$_SESSION['log_user'] = $sid;
							header("location:../index.php");
							$tquer = $db -> prepare ("INSERT INTO stud_logtime (stud_id,logging_in,stud_logid) VALUES (:sid,:timein,:slid)");
							$tquer -> execute(array(
									"sid" => $sid,
									"timein" => $date,
									"slid" => $slid
							));
							$_SESSION['log_user_time'] = $slid;
							die();
						}
						else
						{
							echo
								"
								<script>
								window.location.href='../login.php?err=accntbn';
								</script>
								";
						}
					}
					else
					{
						
						echo 
							"
							<script>
							window.location.href='../login.php?err=ntaprvd';
							</script>
							";
					}
				}
				else
				{
					$get_ip = $db -> prepare ("INSERT INTO stud_trylog (username,ip,datetime,attempt) VALUES (:username,:ip,:datetime,:attempt)");
					$get_ip -> bindParam (":username", $username);
					$get_ip -> bindParam (":ip", $ipAddress);
					$get_ip -> bindParam (":datetime", $date);
					$get_ip -> bindParam (":attempt", $attempt);
					$get_ip -> execute();	

					echo 
						"
						<script>
						window.location.href='../login.php?err=invlusr';
						</script>
						";
				}
		}
		else if($rowcount==0)
		{
			$chemail = $db -> prepare ("SELECT * FROM stud_bas WHERE email = :email");
			$chemail -> bindParam(':email', $username);
			$chemail -> execute();
			$emcnt = $chemail -> rowCount();
			if($emcnt>0)
			{
				$emresult = $chemail -> fetch();
				$empassword = $emresult['password'];
				$realemail = $emresult['email'];
				$emuser = $emresult['username'];
				$emstatus = $emresult['status'];
				$emid = $emresult['stud_id'];
				$campus = $emresult['campus'];
				$department = $emresult['department'];
				
			$e_encryptedpassword = password_verify($password, $empassword);
			
				if($e_encryptedpassword && $username==$realemail)
				{
					if($emstatus == 'Expired'){
						echo 
							"
							<script>
							window.location.href='../login.php?err=accntexpired';
							</script>
							";
							die();
					}
					$get_expire = $db -> prepare ("SELECT expire_date FROM admin_expire_passwords WHERE campus = :campus AND department = :department ");
					$get_expire -> bindParam (":campus", $campus);
					$get_expire -> bindParam (":department", $department);
					$get_expire -> execute();
					$get_expire_count = $get_expire -> rowCount();
					if($get_expire_count>0){
						$get_expire_result = $get_expire -> fetch();
						$expire_date = $get_expire_result['expire_date'];
						if($expire_date != 'false'){
							$string_date = strtotime($date);
							$string_expire_date = strtotime($expire_date);
							if($string_date>=$string_expire_date){
								echo 
									"
									<script>
									window.location.href='../login.php?err=expired';
									</script>
									";
								die();
							}
						}
						
					}
					if($emstatus!="Pending")
					{
						if($emstatus=="Registered")
						{
							
						
							echo "Please Wait... Logging In";
							$_SESSION['log_user'] = $emid;
							$tquer = $db -> prepare ("INSERT INTO stud_logtime (stud_id,logging_in,stud_logid) VALUES (:sid,:timein,:slid)");
							$tquer -> execute(array(
									"sid" => $emid,
									"timein" => $date,
									"slid" => $slid
							));
							$_SESSION['log_user_time'] = $slid;
							header('location:../index.php');
							die();
						}
						else
						{
							echo 
								"
								<script>
								window.location.href='../login.php?err=accntbn';
								</script>
								";
						}
					}
					else
					{
						echo 
						"
						<script>
						window.location.href='../login.php?err=ntaprvd';
						</script>
						";	
					}
				}
				else
				{
					$get_ip = $db -> prepare ("INSERT INTO stud_trylog (username,ip,datetime,attempt) VALUES (:username,:ip,:datetime,:attempt)");
					$get_ip -> bindParam (":username", $username);
					$get_ip -> bindParam (":ip", $ipAddress);
					$get_ip -> bindParam (":datetime", $date);
					$get_ip -> bindParam (":attempt", $attempt);
					$get_ip -> execute();	
					echo 
						"
						<script>
						window.location.href='../login.php?err=invlusr';
						</script>
						";
							
				}
			}
			else
			{
			
				echo
					"
					<script>
					window.location.href='../login.php?err=invlusr';
					</script>
					";
			}
		}
		else
		{
				
			echo
				"
				<script>
				window.location.href='../login.php?err=invlusr';
				</script>
				";
		}
		
		
	}
	else
	{
				
			echo
				"
				<script>
				window.location.href='../login.php?err=invlusr';
				</script>
				";
	}
		
}
else
{
	header("location:../login.php?eerr=invlusr");
}
?>