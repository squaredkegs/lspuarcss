<?php
include_once ("connection.php");
include_once ("adminfunction.php");
$date = date("Y-m-d H:i:s"); 
$aid = $_SESSION['admin_log'];
	$_SESSION['LAST_ACTIVITY'] = time();

if(isset($_POST['ban_sid'])){
	$sid = $_POST['ban_sid'];
	$status = $_POST['status'];
	$reason = $_POST['reason'];
	$bid = createUniqueId('banned_id','banned_tbl');
	if($status=='Banned'){
	$query = $db -> prepare ("UPDATE stud_bas SET status='Registered' WHERE stud_id=:sid");
	$newstatus="Remove Ban";
	}
	else if($status=='Registered'){
	$query = $db -> prepare ("UPDATE stud_bas SET status='Banned' WHERE stud_id=:sid");
	$newstatus="Banned";
	}
	$query -> bindParam (":sid", $sid);
	$query -> execute();
		if($query){
			$sec_quer = $db -> prepare ("INSERT INTO admin_activity (admin_id,object_id,activity,stat_date,object) VALUES (:aid,:oid,:activity,:date,:object)");
			$sec_quer -> execute(array(
				"aid" => $aid,
				"oid" => $bid,
				"activity" => $newstatus,
				"date" => $date,
				"object" => "Student"
			));
			$insert_to_ban_tbl = $db -> prepare ("
							INSERT INTO banned_tbl (banned_id,reason,datetime) 
							VALUES(:bid,:reason,:date)");
			$insert_to_ban_tbl -> bindParam (":bid", $bid);	
			$insert_to_ban_tbl -> bindParam (":reason", $reason);
			$insert_to_ban_tbl -> bindParam (":date", $date);
			$insert_to_ban_tbl -> execute();
			$insert_to_connect_ban = $db -> prepare ("INSERT INTO ban_connect (banned_id,stud_id) VALUES(:bid,:sid)");
			$insert_to_connect_ban -> bindParam (":bid", $bid);
			$insert_to_connect_ban -> bindParam (":sid", $sid);
			$insert_to_connect_ban -> execute();
			
 		}
		
}
	
?>