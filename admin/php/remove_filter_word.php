<?php

	include_once ("connection.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['fid'])){
		$fid = $_POST['fid'];
		$check = $db -> prepare ("SELECT filter_id FROM filter_tbl WHERE filter_id = :fid");
		$check -> bindParam (":fid", $fid);
		$check -> execute();
		$numrow = $check -> rowCount();
		if($numrow==1){
			$query = $db -> prepare ("DELETE FROM filter_tbl WHERE filter_id=:fid");
			$query -> bindParam(":fid", $fid);
			$query -> execute();
		}
		else{
			
		echo 
			"
			<script>
			alert('Word Not in Database!');
			window.location.href='../filterwords.php';
			</script>
			";
			
		}
		
	}
	else{
		
		echo 
			"
			<script>
			alert('Error!');
			window.location.href='../filterwords.php';
			</script>
			";
	}
?>