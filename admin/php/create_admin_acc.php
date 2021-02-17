<?php
include ("connection.php");

include ("adminfunction.php");
include ("queryadmindata.php");
$_SESSION['LAST_ACTIVITY'] = time();
$schid = createUniqueId('admin_id','admin_tbl');
if(isset($_POST['submit']))
{
	
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$campus = $_POST['campus'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$position = $_POST['position'];
	$username = $_POST['username'];
	$department = $_POST['department'];
	$date = date ("Y-m-d h:i:s");
	$userrow = checkIfExisting('admin_account',$username);
	$emailrow = checkIfExisting('email',$email);
	$expire_date = date("Y-m-d H:i:s", strtotime("+6 months"));	
	$encryptpassword = password_hash($password, PASSWORD_BCRYPT);
		
	if($userrow==0)
	{
		if($emailrow==0)
		{
			if(strlen($password)>=8)
			{
				if(strlen($username)>=8){
				$query = $db -> prepare 
								("START TRANSACTION;
								INSERT INTO admin_tbl (admin_id,campus,position,admin_account, password,email,fname,lname,datetime,accnt_expire,department)
								VALUES (:schid,:campus,:position,:acc,:pass,:email,
								:fname,:lname,:date,:accnt_xpire,:dept);
								INSERT INTO admin_activity (admin_id,object_id,activity,stat_date,object) VALUES (:aid,:oid,:activity,:date2,:object);
								COMMIT;");
				$query -> execute(array(
							"schid" => $schid,
							"campus" => $campus,
							"position" => $position,
							"acc" => $username,
							"pass" => $encryptpassword,
							"email" => $email,
							"fname" => $fname,
							"lname" => $lname,
							"date" => $date,
							"accnt_xpire" =>$expire_date,
							"dept" => $department,
							"aid" => $aid,
							"oid" => $schid,
							"activity" => "Create New Admin",
							"date2" => $date,
							"object" => "Admin"
				
							));	

					if($query){
						echo 
							"
							<script>
							alert('Successful');
							window.location.href='../index.php';
							</script>
							";
	
					}
				}
				else
				{				
				echo 	"<script>
				alert('Username Too Short!');
				window.location.href='../add_admin.php';
				</script>";				
				}
			}
			else
			{
			echo 	"<script>
				alert('Password Too Short!');
				window.location.href='../add_admin.php';
				</script>";				
			}
		}
		else
		{
			echo 	"<script>
				alert('Email Already In Use!');
				window.location.href='../add_admin.php';
				</script>";
		}
	}
	else
	{
		echo 	"<script>
				alert('Username Already Exists!');
				window.location.href='../add_admin.php';
				</script>";
	}
}

?>