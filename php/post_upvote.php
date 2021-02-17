<?php
include_once ("connection.php");


$name = $_POST['name'];
$id = $_POST['real_id'];
$stud_id = $_SESSION['log_user'];
switch ($name){
	case "up":
			$check = $db -> prepare("SELECT * FROM upvote_post WHERE stud_id=:sid AND news_id=:nid");
			$check -> bindParam (":sid", $stud_id);
			$check -> bindParam (":nid", $id);
			$check -> execute();
			$numrow = $check -> rowCount();
				if($numrow==0){
					$query = $db -> prepare ("INSERT INTO upvote_post (stud_id,news_id,score) 
										VALUES (:sid,:nid,'1')");
					$query -> bindValue (":sid", $stud_id);
					$query -> bindValue (":nid", $id);
					$query -> execute();
				}
				else{
					$query = $db -> prepare ("UPDATE upvote_post SET score='1' WHERE stud_id=:sid AND news_id=:nid");
					$query -> bindValue(":sid", $stud_id);
					$query -> bindValue(":nid", $id);
					$query -> execute();					
				}	
			
	break;
	case "down":
			$check = $db -> prepare("SELECT * FROM upvote_post WHERE stud_id=:sid AND news_id=:nid");
			$check -> bindParam (":sid", $stud_id);
			$check -> bindParam (":nid", $id);
			$check -> execute();
			$numrow = $check -> rowCount();
				if($numrow==0){
					$query = $db -> prepare ("INSERT INTO upvote_post (stud_id, news_id, score)
									VALUES(:sid,:nid,'-1')");
					$query -> bindParam (":sid", $stud_id);
					$query -> bindParam (":nid", $id);
					$query -> execute();
				}
				else{
					$query = $db -> prepare ("UPDATE upvote_post SET score='-1' WHERE stud_id=:sid AND news_id=:nid");
					$query -> bindValue(":sid", $stud_id);
					$query -> bindValue(":nid", $id);
					$query -> execute();
				}
	break;
	case "upvoted":
			$query = $db -> prepare ("DELETE FROM upvote_post WHERE stud_id=:sid AND news_id=:nid");
			$query -> bindParam (":sid", $stud_id);
			$query -> bindParam (":nid", $id);
			$query -> execute();	
	break;
	case "downvoted":
			$query = $db -> prepare ("DELETE FROM upvote_post WHERE stud_id=:sid AND news_id=:nid");
			$query -> bindParam (":sid", $stud_id);
			$query -> bindParam (":nid", $id);
			$query -> execute();
	break;
	default:
		echo "Error!";
}
	

?>