<?php
function checkLogIn(){
include ("php/connection.php");
		$user = $_SESSION['log_user'];

	if(!isset($_SESSION['log_user']))
	{
		header("location:login.php?err=ntlog");
	}
	else
	{
		$user = $_SESSION['log_user'];
		$query = $db -> prepare ("SELECT stud_id FROM stud_bas WHERE stud_id = :sid");
		$query -> bindParam (':sid', $user);
		$query -> execute ();
		$idres = $query -> fetch();
		$sessionid = $idres ['stud_id']; 
		

	}
	
	return $sessionid;
}

function limit_length($text, $length)
{
	if(strlen($text)<=$length)
	{
		echo htmlspecialchars($text);
	}
	else
	{
		$y = substr($text,0,$length) . '...';
		echo htmlspecialchars($y);
	}
}

function createRandomId($column,$table)
{
include ("connection.php");
	$string = "";
	$rand = substr(md5(microtime()), rand (0, 26), 75);
	$query = $db -> prepare ("SELECT $column FROM $table WHERE $column = :id");
	$query -> bindParam (":id", $rand);
	$query -> execute();
	$numrow = $query -> rowCount();
	$string = $rand;
	if($numrow>0)
	{
		$string = createRandomId($column,$table);
	}
	return $string;
}



function getUserId()
{
include ("connection.php");
		$user = $_SESSION['log_user'];
		$query = $db -> prepare ("SELECT stud_id FROM stud_bas WHERE stud_id = :sid");
		$query -> bindParam (':sid', $user);
		$query -> execute ();
		$idres = $query -> fetch();
		$sessionid = $idres ['stud_id']; 
		return $sessionid;
		
	
}

function registerFunction($form_name,$occ,$occupation)
{
include ("connection.php");
include ("password_hash.php");	
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$encryptpassword = password_hash($password, PASSWORD_BCRYPT);
	$campus = $_POST['campus'];
	$department = $_POST['department'];
		if(isset($_POST['studentid'])){
			$school_id = $_POST['studentid'];
		}
		else{
			$school_id = $_POST['teacherid'];
		}
	$sid = createRandomId('stud_id','stud_bas');	
	$datetime = date("Y-m-d h:i:s");						

			$chemail = $db -> prepare ("SELECT email FROM stud_bas WHERE email=:email");
			$chemail -> bindParam (":email", $email);
			$chemail ->execute();
			$emailcount = $chemail -> rowCount();
				if($emailcount==0)
				{
					$paslent = strlen($password);
					if($paslent>=7)
					{		
							$chid = $db -> prepare("SELECT school_id FROM stud_bas WHERE school_id = :sid");
							$chid -> bindParam (':sid', $sid);
							$chid -> execute();
							$idcount = $chid -> rowCount();
							if($idcount==0)
							{
								$statement = $db->prepare("INSERT INTO stud_bas (stud_id,fname,lname,campus,department,email,password,school_id,status,timereg,occupation)
								VALUES (:sid,:fname, :lname, :campus,:dept, :email, :password, :school_id, :status, :datetime,:occ)");
								
								$statement->execute(array(
									"sid" => $sid,
									"fname" => $fname,
									"lname" => $lname,
									"campus" => $campus,
									"dept" => $department,
									"email" => $email,
									"password" => $encryptpassword,
									"school_id" => $school_id,
									"status" => "Pending",
									"datetime" => $datetime,
									"occ" => $occ
									));
									/*if($occ=='Teacher'){
										$sub = $db -> prepare ("INSERT INTO teacher_")()
									}*/
									if($statement)
									{
										 
										echo
										"
										<script>
										alert('Successfully Registered! \\nPlease wait for confirmation of Registration');
										window.location.href='../index.php';		
										</script>";
									}
							}
							else
							{
							//	echo "test";
								echo
								"
								<script>
								alert('School I.D. Already in used!');
								window.location.href='../signup.php?reg=$occupation&err=id';		
								</script>";
							}
					}
					else
					{

						echo
						"
						<script>
						alert('Password too short!');
						window.location.href='../signup.php?reg=$occupation&err=pass';		
						</script>";
						
					}	
				}
				else
				{
			
					echo
					"
					<script>
					alert('Email Already in Use!');
					window.location.href='../signup.php?reg=".$occupation."&err=email';		
					</script>";
					
				}
}

function displayText($text){
	$newtext = htmlspecialchars($text);
	echo $newtext;
	}
?>

