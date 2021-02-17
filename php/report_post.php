<?php
include_once ("connection.php");
include_once ("querydata.php");
include_once ("function.php");
	$datetime = date("Y-m-d H:i:s");
	if(
		(isset($_POST['reason']) && isset($_POST['nid']) && isset($_POST['content']) && isset($_POST['type']))
		){
		$type = $_POST['type'];
		$nid = $_POST['nid'];	
		$content = $_POST['content'];
		$reason = $_POST['reason'];
		$rid = createRandomId('report_id','report');
		
		$check_if_reported_already = $db -> prepare ("SELECT EXISTS(SELECT report_connect.stud_id FROM report_connect 
		INNER JOIN report
		ON report.report_id = report_connect.report_id
		WHERE report_connect.stud_id=:sid AND report_connect.news_id=:nid AND report.type=:type) as count");
		$check_if_reported_already -> bindParam (":nid", $nid);
		$check_if_reported_already -> bindParam (":type", $type);
		$check_if_reported_already -> bindParam (":sid", $getid);
		$check_if_reported_already -> execute();
		$num_check_report = $check_if_reported_already -> fetch();
		$num_report = $num_check_report ['count'];
		if($num_report==0){
			$query = $db -> prepare ("
								START TRANSACTION;
								INSERT INTO report (report_id,reason,content,type,datetime) VALUES(:rid,:reason,:content,:type,:datetime);
								INSERT INTO report_connect (report_id,stud_id,news_id) VALUES (:rid,:sid,:nid);
								COMMIT;
								");
			$query -> execute(array(
						"rid" => $rid,
						"reason" => $reason,
						"content" => $content,
						"type" => $type,
						"datetime" => $datetime,
						"sid" => $getid,
						"nid" => $nid
						));
			if($query){
			}
			else{
				echo "Reported";
				//header("location:javascript://history.go(-1)");
			}
		}
		else{
			echo "Already Reported";
		}
	}
	else{
		echo "
			<script>
			alert('Error Detected!');
			</script>";
			//header("location:javascript://history.go(-1)");
	}
?>