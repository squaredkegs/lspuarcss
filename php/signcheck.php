
<?php
include ("connection.php");




function emptyField ($data)
{
	$column = $_POST[$data];
	if(empty($column))
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Fill Up</span>";
	}	
}
if(isset($_POST['fname']))
{ emptyField('fname');}
if(isset($_POST['lname']))
{ emptyField('lname');}
if(isset($_POST['mname']))
{ emptyField('mname');}

if(isset($_POST['password']))
{ 	
	$password = $_POST['password'];
	if(empty($password))
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Fill Up</span>";
	}
}


if(isset($_POST['username']))
{
$username = $_POST['username'];
	$chusername = $db->prepare("SELECT username FROM stud_bas WHERE username = :username");
	$chusername -> bindParam(':username', $username);
	$chusername->execute();
	$numrow = $chusername->rowCount();
	
	if(empty($username))
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Fill up</span>";
	}
	else if(strlen($username)<=7)
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Username Too Short</span>";
	}
	else if($numrow==0)
	{
		echo "<span style='color:green;float:right;font-size:12px;'>Available</span>";
	}
		
	else if($numrow>0)
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Username Already Taken</span>";
	}
}

if(isset($_POST['email']))
{
	$email = $_POST['email'];
	$chemail = $db->prepare("SELECT email FROM stud_bas WHERE email = :email");
	$chemail -> bindParam(':email', $email);
	$chemail ->execute();
	$numrow2 = $chemail -> rowCount();
	
	if(empty($email))
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Fill up</span>";
	}
	else if($numrow2==0)
	{
		echo "<span style='color:green;float:right;font-size:12px;'>Available</span>";
	}
		
	else if($numrow2>0)
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Email Already In Use</span>";
	}
	
}

if(isset($_POST['studentid']))
{
	$studentid = trim($_POST['studentid']);
	$chid = $db -> prepare ("SELECT school_id FROM stud_Bas WHERE school_id = :sid");
	$chid -> bindParam (":sid", $studentid);
	$chid -> execute ();
	$idcount = $chid -> rowCount();
	if(empty($studentid))
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Fill up</span>";
	}
	else if($idcount>0)
	{
		echo "<span style='color:red;float:right;font-size:12px;'>Already in Use</span>";
	}
	else if($idcount==0){
		echo "<span style='color:green;float:right;font-size:12px;'>Available</span>";
		
	}
}

if(isset($_POST['ch_username']))
{
	$user = $_POST['ch_username'];
	$query = $db -> prepare ("SELECT username FROM stud_bas WHERE username=:username");
	$query -> bindParam (":username", $user);
	$query -> execute();
	$numrow = $query -> rowCount();
	
	if(strlen($user)<=7)
	{
		echo 
			"<span style='color:red;font-size:10px;margin-left:5px;'>Too Short</span>";
	}
	else if($numrow>0)
	{
		echo 
			"<span style='color:red;font-size:10px;margin-left:5px;'>Unavailable</span>";
	}
	else
	{
		echo 
			"<span style='color:green;font-size:10px;margin-left:5px;'>Available</span>";
		
	}
}
?>
