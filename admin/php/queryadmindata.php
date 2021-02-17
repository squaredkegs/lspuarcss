<?php
include_once ("connection.php");
include_once ("adminfunction.php");	
	$admin_id = adminSec();
	$realquery = $db -> prepare("SELECT * FROM admin_tbl WHERE admin_id=:aid");
	$realquery -> bindParam (":aid", $admin_id);
	$realquery -> execute ();
	$getadmindata = $realquery -> fetch();
	$admin_campus = $getadmindata['campus'];
	$rcampus = $getadmindata['campus'];
	$rposition = $getadmindata['position'];
	$rfname = $getadmindata['fname'];
	$rlname = $getadmindata['lname'];
	$rdepartment = $getadmindata['department'];
	$aid = $getadmindata['admin_id'];
	$remail = $getadmindata['email'];
	$rpassword = $getadmindata['password'];
	$raccount_expire = $getadmindata['accnt_expire'];
	$rstatus = $getadmindata['status'];
?>