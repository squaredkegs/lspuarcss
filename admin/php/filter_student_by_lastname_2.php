<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	if(isset($_POST['filter_expired_accounts'])){
		$first_letter = $_POST['lastname_letter'];
		$second_letter = $first_letter;
		if(isset($_POST['second_lastname_letter'])){
		$second_letter = $_POST['second_lastname_letter'];
		}
		header("location:../existingaccounts?flname=$first_letter&slname=$second_letter");
	}
	
?>