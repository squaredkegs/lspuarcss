<?php
include_once ("connection.php");
include_once ("querydata.php");

$cid = $_POST['cid'];
$name = $_POST['name'];
		$check = $db -> prepare ("SELECT * FROM upvote_comment WHERE stud_id = :sid AND cmmt_id = :cid");
		$check -> bindParam (":sid", $getid);
		$check -> bindParam (":cid", $cid);
		$check -> execute();
		$numrow = $check -> rowCount();

switch ($name)
{
	case "upvote":
		if($numrow==0){
			$query = $db -> prepare ("INSERT INTO upvote_comment (stud_id,cmmt_id,score) VALUES (:sid,:cid,:score)");
		$query -> execute(array(
						"sid" => $getid,
						"cid" => $cid,
						"score" => 1
						));
			}
		else if($numrow==1){
			$score = 1;
			$query = $db -> prepare ("UPDATE upvote_comment SET score=:score WHERE stud_id = :sid AND cmmt_id = :cid");
			$query -> bindParam (":sid", $getid);
			$query -> bindParam (":score", $score);
			$query -> bindParam (":cid", $cid);
			$query -> execute();
		}
	break;
	case "downvote":
		if($numrow==0){
			$score = -1;
			$query = $db -> prepare ("INSERT INTO upvote_comment (stud_id,cmmt_id,score) VALUES (:sid,:cid,:score)");
			$query = $db -> prepare ("INSERT INTO upvote_comment (stud_id,cmmt_id,score) VALUES (:sid,:cid,:score)");
			$query -> execute(array(
						"cid" => $cid,
						"sid" => $getid,
						"score" => $score
						));
		}
		else if($numrow==1){
			$score = -1;
			$query = $db -> prepare ("UPDATE upvote_comment SET score=:score WHERE stud_id = :sid AND cmmt_id = :cid");
			$query -> bindParam (":sid", $getid);
			$query -> bindParam (":cid", $cid);
			$query -> bindParam (":score", $score);
			$query -> execute();
		}
	break;
	case "remove_downvote": 
	case "remove_upvote":
	$query = $db -> prepare ("DELETE FROM upvote_comment WHERE stud_id=:sid AND cmmt_id=:cid");
		$query -> bindParam (":sid", $getid);
		$query -> bindParam (":cid", $cid);
		$query -> execute();
	break;
	default:
		echo 
			"
			<script>
			alert('Website Error!');
			</script>
			";
			
}
?>