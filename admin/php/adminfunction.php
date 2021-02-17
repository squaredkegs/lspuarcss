<?php
include ("connection.php");
	

	function adminLog ()
	{
	include ("php/connection.php");
		if(isset($_SESSION['admin_log']))
		{
			
			$admin = $_SESSION['admin_log'];
			$query = $db -> prepare ("SELECT admin_id FROM admin_tbl WHERE admin_id = :aid");
			$query -> bindParam (":aid", $admin);
			$query -> execute();
			$getdata = $query -> fetch();
			$adminid = $getdata['admin_id'];
		}
		else
		{
			header("location:adminlog.php");
		}
		return $adminid;	
	}

	function adminSec ()
	{
	include ("connection.php");
		if(isset($_SESSION['admin_log']))
		{
			
			$admin = $_SESSION['admin_log'];
			$query = $db -> prepare ("SELECT admin_id FROM admin_tbl WHERE admin_id = :aid");
			$query -> bindParam (":aid", $admin);
			$query -> execute();
			$getdata = $query -> fetch();
			$adminid = $getdata['admin_id'];
		}
		else
		{
			echo "Error";
			//header("location:adminlog.php");
		}
	
		return $adminid;
	}

	function checkIfExisting($column,$value)
	{
	include ("connection.php");
	
		$query = $db -> prepare ("SELECT $column FROM admin_tbl WHERE $column = :value");
		$query -> bindParam (":value", $value);
		$query -> execute();
		$numrow = $query -> rowCount();
		return $numrow;
	
	}
	
	function createSessionId()
	{
	include ("connection.php");
		$string = "";
		$rand = substr(md5(microtime()),rand (7, 11), 80);
		$query = $db -> prepare ("SELECT log_id FROM admin_logtime WHERE log_id = :lid");
		$query -> bindParam (":lid", $rand);
		$query -> execute();
		$numrow = $query -> rowCount();
		$string = $rand;
		if($numrow>0)
		{
			$string = createSessionId();
		}
		return $string;
	}
	
	function createUniqueId($column,$table)
	{
	include ("connection.php");
		$string = "";
		$rand = substr(md5(microtime()),rand (0, 26), 80);
		$query = $db -> prepare ("SELECT $column FROM $table WHERE $column = :lid");
		$query -> bindParam (":lid", $rand);
		$query -> execute();
		$numrow = $query -> rowCount();
		$string = $rand;
		if($numrow>0)
		{
			$string = createUniqueId($column,$table);
		}
		return $string;
	}	
	
	function limit_length($text, $length)
	{
		if(strlen($text)<=$length)
		{
			echo $text;
		}
		else
		{
			$y = substr($text,0,$length) . '...';
			echo $y;
		}
	}

?>