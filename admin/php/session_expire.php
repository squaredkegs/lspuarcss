<?php
	
	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");

include ("connection.php");
if(isset($_SESSION['admin_time_log']))
{
	$lid = $_SESSION['admin_time_log'];
	$logout = date("Y-m-d H:i:s");
			
	$query = $db -> prepare ("UPDATE admin_logtime SET datetime_out=:logout WHERE log_id=:lid");
	$query -> bindParam (":logout", $logout);
	$query -> bindParam (":lid", $lid);
	$query -> execute();
		if($query)
		{
			unset($_SESSION['admin_log']);
			unset($_SESSION['admin_time_log']);
			header("location:../adminlog.php");
			die();
		}

}
else
{
			unset($_SESSION['admin_log']);
			unset($_SESSION['admin_time_log']);
			header("location:../adminlog.php");
			die();
	
}		

?>