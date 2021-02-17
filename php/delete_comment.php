<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	if(isset($_POST['cid'])){
		$cid = $_POST['cid'];
		$check = $db -> prepare ("SELECT EXISTS (SELECT cmmt_id FROM comment_connect WHERE cmmt_id=:cid AND stud_id = :sid) as count");
		$check -> bindParam (":cid", $cid);
		$check -> bindParam (":sid", $getid);
		$check -> execute();
		$res = $check -> fetch();
		$count = $res['count'];

			if($count>0){
				$check_if_has_child_comment = $db -> prepare ("call comment_procedure(:cid)");
				$check_if_has_child_comment -> bindParam (":cid", $cid);
				$check_if_has_child_comment -> execute();
				$pid = $check_if_has_child_comment -> rowCount();
				$check_if_has_child_comment -> closeCursor();
				if($pid>0){
				$query = $db -> prepare ("UPDATE cmmt_sect SET content='[Deleted by User]', type='Deleted' WHERE cmmt_id=:cid");
				$query -> bindParam (":cid", $cid);
				$query -> execute();
				}
				else{
					$query = $db -> prepare ("DELETE FROM cmmt_sect WHERE cmmt_id=:cid");
					$query -> bindParam (":cid", $cid);
					$query -> execute();
				
				}
			}
			else{
				die();
			}
			
			$get_media = $db -> prepare ("
						SELECT cmmt_media.cmid as cmid, cmmt_media.file_name as filename, cmmt_media.file_path as filepath 
						FROM cmmt_media_connect
						LEFT JOIN cmmt_media
						ON cmmt_media.cmid = cmmt_media_connect.cmid
						WHERE cmmt_media_connect.cid = :cid ");
			$get_media -> bindParam (":cid", $cid);
			$get_media -> execute();
			$numrow_get_media = $get_media -> rowCount();
			if($numrow_get_media>0){
			$res_get_media = $get_media -> fetch();
			$filepath = $res_get_media['filepath'];
			$filename = $res_get_media['filename'];
			$cmid = $res_get_media['cmid'];
				if(file_exists($filepath.$filename)){
					unlink($filepath.$filename);
					$delete_media = $db -> prepare ("DELETE FROM cmmt_media WHERE cmid = :cmid");
					$delete_media -> bindParam (":cmid", $cmid);
					$delete_media -> execute();
					$delete_media_connect = $db -> prepare ("DELETE FROM cmmt_media_connect WHERE cmid = :cmid");
					$delete_media_connect -> bindParam (":cmid", $cmid);
					$delete_media_connect -> execute();
				}
			}		
	}
	else{
		die();
	}

?>