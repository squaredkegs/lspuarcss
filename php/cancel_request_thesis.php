<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	
	if(isset($_POST['thid'])){
		$thid = $_POST['thid'];
		$status = 'Pending';
		$date = date("Y-m-d H:i:s");
		$check_if_request_exists = $db -> prepare ("
			SELECT request_id 
			FROM request_thesis_connect 
			WHERE thesis_id=:thid 
			AND stud_id=:sid");
		$check_if_request_exists -> bindParam (":thid", $thid);
		$check_if_request_exists -> bindParam (":sid", $getid);
		$check_if_request_exists -> execute();
		$result = $check_if_request_exists -> fetch();
		$rqid = $result['request_id'];
		$numrow_check = $check_if_request_exists -> rowCount();
		if($numrow_check==1){
				$delete_request_query = $db -> prepare ("
				START TRANSACTION;
				DELETE FROM request_thesis_connect 
				WHERE thesis_id =:thid AND stud_id=:sid;
				DELETE FROM request_thesis WHERE request_id = :rqid; 
				COMMIT;");
				$delete_request_query -> bindParam (":thid", $thid);
				$delete_request_query -> bindParam (":sid", $getid);
				$delete_request_query -> bindParam (":rqid", $rqid);
				$delete_request_query -> execute();
			if($delete_request_query){
			?>
			
				<a href='#' style='margin-bottom:25px;' title='Request Complete Access' class='request-access' id='thesis_<?php echo $thid;?>' onclick='request_thesis(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/request.png' ></a>
		
			<?php	
			}
			
		}
		else{
		//Already Requested
		?>
			<a href='#' style='margin-bottom:25px;' title='Request Complete Access' class='request-access' id='thesis_<?php echo $thid;?>'onclick='request_thesis(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/request.png'></a>
							
		<?php
		}
	}
?>