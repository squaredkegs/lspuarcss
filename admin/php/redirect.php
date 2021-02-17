<?php

	include_once ("connection.php");
	echo "Unauthorized Access!";
	echo "<br/>";
	echo "Redirecting. Please Wait.";
	sleep(5);
	header("location:../index");
	exit;

?>