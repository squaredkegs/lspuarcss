<?php

include_once ("connection.php");
include_once ("function.php");
include_once ("user_data.php");


if(isset($_POST['create_user']))
{
	$user = $_POST['username'];
	$query = $db -> prepare ("SELECT username FROM stud_bas WHERE username=:username");
	$query -> bindParam (":username", $user);
	$query -> execute();
	$numrow = $query -> rowCount();
	//$user_length = strlen($user);
	if(strlen($user)>7)
	{
		if($numrow==0)
		{	
				$update = $db -> prepare ("
				UPDATE stud_bas SET username=:user WHERE stud_id=:sid");
				$update -> bindParam (":user", $user);
				$update -> bindParam (":sid", $getid);
				$update -> execute();
					if($update)
					{
					echo 
					"<script>
					alert('Successfully created username!');
					window.location.href='../myprofile.php?user=$getid';
					</script>";	
					}
					else
					{
						echo 
						"<script>
						alert('Server Error! Contact Admin');
						window.location.href='../myprofile.php?user=$getid&err=site_error';
						</script>";					}
		}
		else
		{
			echo 
					"<script>
					alert('Username already exists!');
					window.location.href='../myprofile.php?user=$getid&error=username_exists';
					</script>";	
		}
	}
	else
	{
			echo 
				"<script>
				alert('Username too short!');
				window.location.href='../myprofile.php?user=$getid&error=short_username';
				 
				</script>";
	
	
		echo $user_length;
	}
	
}

?>