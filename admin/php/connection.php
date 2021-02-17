<?php
date_default_timezone_set ('Asia/Hong_Kong');

	try 
	{
		$db = new PDO ('mysql:dbname=smnp;host=localhost', 'root', '');
		$db2 = new PDO('mysql:dbname=isam_smnp;host=localhost', 'root', '');
		
	} catch (PDOexception $ex)
		{
			echo 'Connection Failed: ' . $ex->getMessage(); 
		}


	if(session_id() == ''){
		session_start();
	}
?>