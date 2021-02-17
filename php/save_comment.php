<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	
	if(isset($_POST['cid'])){
		$cid = $_POST['cid'];
		$date = date("Y-m-d H:i:s");
		
		$check = $db -> prepare ("SELECT oid FROM stud_save WHERE oid=:cid AND type='Comment' AND stud_id=:sid");
		$check -> bindParam (":cid", $cid);
		$check -> bindParam (":sid", $getid);
		$check -> execute();
		$numrow = $check -> rowCount();
			if($numrow==0){
				
				$query = $db -> prepare ("INSERT INTO stud_save (stud_id,oid,type,date) VALUES (:sid,:oid,:type,:date)");
					$query -> execute(array(
								"sid" => $getid,
								"oid" => $cid,
								"type" => "Comment",
								"date" => $date
							));
				
				echo "<a href='#'>Unsave</a>";
				}
			else if($numrow==1){
				$query = $db -> prepare ("DELETE FROM stud_save WHERE oid=:oid AND stud_id=:sid");
				$query -> bindParam (":oid", $cid);
				$query -> bindParam (":sid", $getid);
				$query -> execute();				
				echo "<a href='#'>Save</a>";
			}
	}
?>