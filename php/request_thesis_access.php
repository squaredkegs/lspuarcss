<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	
	if(isset($_POST['thid'])){
		$thid = $_POST['thid'];
		$get_thid_info = $db -> prepare ("SELECT campus, department, course FROM thesis_arch WHERE thesis_id = :thid");
		$get_thid_info -> bindParam (":thid", $thid);
		$get_thid_info -> execute();
		$result = $get_thid_info -> fetch();
		$campus = $result['campus'];
		$department = $result['department'];
		$course = $result['course'];
		$rqid = createRandomId('request_id','request_thesis');
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
		$numrow_check = $check_if_request_exists -> rowCount();
		if($numrow_check==0){
			
			$request_query = $db -> prepare 
					("
					START TRANSACTION;
					INSERT INTO request_thesis (request_id,campus,department,course,request_date,status)
					VALUES
					(:rqid,:camp,:dept,:course,:rq_date,:stat);
					INSERT INTO request_thesis_connect
					(request_id,thesis_id,stud_id)
					VALUES
					(:rqid, :thid, :sid);
					COMMIT;
					");
			$request_query -> execute(array(
					"rqid" => $rqid,
					"camp" => $campus,
					"dept" => $department,
					"course" => $course,
					"rq_date" => $date,
					"stat" => $status,
					"thid" => $thid,
					"sid" => $getid,
					));
					
			if($request_query){
			?>
			
				<a href='#' style='margin-bottom:25px;' title='Waiting for Approval' class='cancel-request' id='cancel_request_<?php echo $thid;?>' onclick='cancel_request(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/waiting.png'></a>

			<?php	
			}
			
		}
		else{
		//Already Requested
		?>
			<a href='#' style='margin-bottom:25px;' title='Request Complete Access' class='request-access' id='thesis_<?php echo $thid;?>' onclick='request_thesis(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/request.png'></a>
							
		<?php
		}
	}
	else{
		
	}
?>