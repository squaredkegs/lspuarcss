<?php
include_once ("connection.php");
include_once ("function.php");
include_once ("user_data.php");


if(isset($_POST['submit'])){
	$student = registerFunction('submit','Student','stud');
}
else if(isset($_POST['t_submit'])){
	$teacher = registerFunction('t_submit','Teacher','teach');
}


?>