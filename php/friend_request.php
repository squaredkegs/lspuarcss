	
<?php
header('Content-type: application/json');
include_once ("connection.php");
include_once ("querydata.php");
	//header('Content-type: application/json');
	$date = date("Y-m-d H:i:s");
	if(isset($_POST['unseen_sid'])){
		$sid = $_POST['unseen_sid'];
		$seen_qr = $db -> prepare ("UPDATE frnd_rqst SET seen='1' WHERE scnd_user=:sid AND seen!=1");
		$seen_qr -> bindParam (":sid", $sid);
		$seen_qr -> execute();
		
	}

	if(isset($_POST['cancel_visit']))
	{
		$fid = $_POST['cancel_visit'];
		$sid = $_POST['owner'];
		$check_friend_list = $db -> prepare ("
					SELECT frst_user, scnd_user 
					FROM frnd_rqst 
					WHERE (frst_user = :fid AND scnd_user=:sid AND status='1') OR (frst_user = :sid AND scnd_user = :fid AND status='1')");
		$check_friend_list -> bindParam (":fid", $fid);
		$check_friend_list -> bindParam (":sid", $sid);
		$check_friend_list -> execute();
		$ch_numrow = $check_friend_list -> rowCount();
		if($ch_numrow==1){
		$response = "success";
		$query = $db -> prepare("DELETE FROM frnd_rqst WHERE frst_user=:fid
							AND scnd_user=:sid");
		$query -> bindParam (":fid", $fid);
		$query -> bindParam (":sid", $sid);
		$query -> execute();
		}
		else{
			$response = "error";
		}
		echo json_encode(array('response' => $response));
		
		exit();
	}
	
	if(isset($_POST['add_visit']))
	{
		$fid = $_POST['add_visit'];
		$sid = $_POST['owner'];
		$check_friend_list = $db -> prepare ("
						SELECT frst_user,scnd_user 
						FROM frnd_rqst 
						WHERE 
						(frst_user=:fid AND scnd_user=:sid) 
						OR 
						(frst_user=:sid AND scnd_user=:fid)");
		$check_friend_list -> bindParam (":fid", $fid);
		$check_friend_list -> bindParam (":sid", $sid);
		$check_friend_list -> execute();
		$ch_numrow = $check_friend_list -> rowCount();
		if($ch_numrow==0){
			$response_array = "success";  
			$query = $db -> prepare("INSERT INTO frnd_rqst (frst_user,scnd_user,status) VALUES (:fid,:sid,'1')");
			$query -> bindParam (":fid", $fid);
			$query -> bindParam (":sid", $sid);
			$query -> execute();
			
			
		}
		else{    
			$response_array = 'error';  
			
		}
		echo json_encode(array('response' => $response_array));
		exit();
		
	}
	
	if(isset($_POST['accept_visit']))
	{
		$date = date("Y-m-d H:i:s");
		
		$fid = $_POST['accept_visit'];
		$sid = $_POST['owner'];
		$check_friend_list = $db -> prepare ("
					SELECT frst_user, scnd_user 
					FROM frnd_rqst 
					WHERE (frst_user = :fid AND scnd_user=:sid AND status='1') OR (frst_user = :sid AND scnd_user = :fid AND status='1')");
		$check_friend_list -> bindParam (":fid", $fid);
		$check_friend_list -> bindParam (":sid", $sid);
		$check_friend_list -> execute();
		$ch_numrow = $check_friend_list -> rowCount();
		
		if($ch_numrow>0){
			
			$response = 'success';
			$getfname = $db -> prepare ("SELECT fname, lname FROM stud_bas WHERE stud_id=:sid");
			$getfname -> bindParam (":sid",$sid);
			$getfname -> execute();
			$result = $getfname -> fetch();
			$fname = $result['fname'];
			$lname = $result['lname'];
			$friend_name = $fname." ".$lname;
				
				if($getfname){
					
					$query = $db -> prepare ("UPDATE frnd_rqst SET status='2',date_of_acceptance=:date_accept WHERE frst_user=:fid AND scnd_user=:sid");
					$query -> bindParam (":date_accept", $date);
					$query -> bindParam (":fid", $sid);
					$query -> bindParam (":sid", $fid);
					$query -> execute();
					//echo json_encode(array('response' => "<span style='margin-left:15px;color:lightblue;'>You are now friends with ". $friend_name ."</span>"));
				}
		}
		else{
			$response = 'error';
		}
		echo json_encode(array('response' => $response));
		exit();
		
	}
	
	if(isset($_POST['destroy_visit']))
	{
		$fid = $_POST['destroy_visit'];
		$sid = $_POST['owner'];
		$query = $db -> prepare ("DELETE FROM frnd_rqst WHERE (frst_user=:fid AND scnd_user=:sid) || (frst_user=:sid AND scnd_user=:fid)");
		$query -> bindParam (":fid", $fid);
		$query -> bindParam (":sid", $sid);
		$query -> execute();
	}
	
	if(isset($_POST['reject_visit']))
	{
		$fid = $_POST['reject_visit'];
		$sid = $_POST['owner'];
		$check_friend_list = $db -> prepare ("
					SELECT frst_user, scnd_user 
					FROM frnd_rqst 
					WHERE (frst_user = :fid AND scnd_user=:sid AND status='1') OR (frst_user = :sid AND scnd_user = :fid AND status='1')");
		$check_friend_list -> bindParam (":fid", $fid);
		$check_friend_list -> bindParam (":sid", $sid);
		$check_friend_list -> execute();
		$ch_numrow = $check_friend_list -> rowCount();
		
		if($ch_numrow>0){
			$response = 'success';
			$query = $db -> prepare ("DELETE FROM frnd_rqst WHERE (frst_user=:fid AND scnd_user=:sid) || (frst_user=:sid AND scnd_user=:fid)");
			$query -> bindParam (":fid", $fid);
			$query -> bindParam (":sid", $sid);
			$query -> execute();
		}
		else{
			$response = 'error';
		}
		echo json_encode(array('response' => $response));
		exit();
	}
	
	
	if(isset($_POST['frid'])){
		$frid = $_POST['frid'];
		$check = $db -> prepare ("SELECT * FROM frnd_rqst WHERE (frst_user=:frid AND scnd_user=:scid) OR (frst_user=:scid AND scnd_user=:frid)");
		$check -> bindParam (":frid", $getid);
		$check -> bindParam (":scid", $frid);
		$check -> execute();
		$numrow = $check -> rowCount();
		if($numrow==1){
		$result = $check -> fetch();
		$first_user = $result['frst_user'];
		$second_user = $result['scnd_user'];
		$status = $result['status'];
		}
			if($numrow==0){
				$query = $db -> prepare ("INSERT INTO frnd_rqst (frst_user,scnd_user,status) VALUES (:first_user,:second_user,:status)");
				$query -> execute(array(
							"first_user" => $getid,
							"second_user" => $frid,
							"status" => 1
							));
				if($query){
					echo "Waiting for Friend Request";
				}			
			}
			else if($numrow==1 && $status==1){
				if($first_user==$getid && $second_user==$frid){
					$query = $db -> prepare ("DELETE FROM frnd_rqst WHERE (frst_user=:frid AND scnd_user = :scid) OR (frst_user = :scid AND scnd_user=:frid) AND status=1");
					$query -> bindParam (":frid", $getid);
					$query -> bindParam (":scid", $frid);
					$query -> execute();
					if($query){
						echo "Add Friend";
					}	
				}
				else{
				
					$query = $db -> prepare ("UPDATE frnd_rqst SET status=2 WHERE (frst_user=:frid AND scnd_user = :scid) OR (frst_user = :scid AND scnd_user=:frid) AND status=1");
					$query -> bindParam (":frid", $getid);
					$query -> bindParam (":scid", $frid);
					$query -> execute();
					if($query){
						echo "Already Friends";
					}	
					}			
			}
			else if($numrow==1 && $status==2){
				$query = $db -> prepare ("DELETE FROM frnd_rqst WHERE (frst_user=:frid AND scnd_user = :scid) OR (frst_user = :scid AND scnd_user=:frid)");
				$query -> bindParam (":frid", $getid);
				$query -> bindParam (":scid", $frid);
				$query -> execute();
				if($query){
					echo "Cancel Friend";
				}			
			}
	}
	if(isset($_POST['unfrid'])){
		$unfrid = $_POST['unfrid'];
		$query = $db -> prepare ("DELETE FROM frnd_rqst WHERE (frst_user=:frid AND scnd_user = :scid) OR (frst_user = :scid AND scnd_user=:frid)");
				$query -> bindParam (":frid", $getid);
				$query -> bindParam (":scid", $unfrid);
				$query -> execute();
				if($query){
					echo "Add Friend";
				}
				
	}
?>