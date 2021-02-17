<?php
include ("connection.php");
$fname = $_POST['fname'];
//$lname = $_POST['lname'];
$test = $_POST['test'];
//echo "$fname $test";

$query = $db -> prepare("UPDATE stud_bas SET fname=:fname WHERE stud_id=:studid");
$query -> bindParam (":fname", $fname);
$query -> bindParam (":studid", $test);
$query -> execute ();


?>