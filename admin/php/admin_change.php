<?php

	include_once ("connection.php");
	include_once ("adminfunction.php");
	include_once ("queryadmindata.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['submit_admin_change'])){
		$aid = $_POST['aid'];
		$position = $_POST['position'];
		$campus = $_POST['campus'];
		$query = $db -> prepare ("UPDATE admin_tbl SET campus=:camp, position=:post WHERE admin_id=:aid");
		$query -> bindParam (":aid", $aid);
		$query -> bindParam (":post", $position);
		$query -> bindParam (":camp", $campus);
		$query -> execute();
		if($query){
			echo 
			"
			<script>
			alert('Changes Saved');
			window.location.href='../admin_detail?detail=$aid';
			</script>
			";
		}
	}

?>