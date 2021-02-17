<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	if(isset($_POST['nid'])){
	$nid = $_POST['nid'];
	$check = $db -> prepare ("SELECT EXISTS(SELECT news_id,stud_id FROM post_connect WHERE news_id=:nid AND stud_id=:sid) as count");
	$check -> bindParam (":nid", $nid);
	$check -> bindParam (":sid", $getid);
	$check -> execute();
	$res = $check -> fetch();
	$numrow = $res['count'];
	if($numrow>0){
			$delete_comment = $db -> prepare ("
				DELETE cmmt_sect,comment_connect  FROM cmmt_sect
				INNER JOIN comment_connect
				ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
				WHERE comment_connect.news_id = :nid
				");
			$delete_comment -> bindParam (":nid", $nid);
			$delete_comment -> execute();
			$query = $db -> prepare("DELETE FROM newsfeed WHERE news_id=:nid");
			$query -> bindParam (":nid", $nid);
			$query -> execute();
			
			$isam_query = $isam_db -> prepare("DELETE FROM isam_newsfeed WHERE news_id=:nid");
			$isam_query -> bindParam (":nid", $nid);
			$isam_query -> execute();
		}
	
	$get_media = $db -> prepare ("
					SELECT cmmt_media.cmid as cmid, cmmt_media.file_path as filepath, cmmt_media.file_name as filename 
					FROM cmmt_media_connect
					LEFT JOIN cmmt_media
					ON cmmt_media.cmid = cmmt_media_connect.cmid
					WHERE cid = :nid");
	$get_media -> bindParam (":nid", $nid);
	$get_media -> execute();
	$res_get_media = $get_media -> fetch();
	$filename = $res_get_media['filename'];
	$filepath = $res_get_media['filepath'];
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
			
		$get_comment = $db -> prepare ("SELECT cmmt_sect.cmmt_id as cid FROM comment_connect
		LEFT JOIN cmmt_sect
		ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
		WHERE comment_connect.news_id = :nid");
		$get_comment -> bindParam (":nid", $nid);
		$get_comment -> execute();
	
				while($row = $get_comment -> fetch(PDO::FETCH_ASSOC)){
					$cid = $row['cid'];
					$get_comment_media = $db -> prepare ("
					SELECT cmmt_media.cmid as cmid, cmmt_media.file_name as filename, cmmt_media.file_path as filepath
					FROM cmmt_media_connect
					LEFT JOIN cmmt_media
					ON cmmt_media.cmid = cmmt_media_connect.cmid
					WHERE cmmt_media_connect.cid = :nid
					");
					$get_comment_media -> bindParam (":nid", $cid);
					$get_comment_media -> execute();
					$res_get_media = $get_comment_media -> fetch();
					$cmid = $res_get_media['cmid'];
					$filename = $res_get_media['filename'];
					$filepath = $res_get_media['filepath'];
						if(file_exists($filepath.$filename)){
							unlink($filepath.$filename);
							$delete_media_comment = $db -> prepare ("DELETE FROM cmmt_media WHERE cmid = :cmid");
							$delete_media_comment -> bindParam (":cmid", $cmid);
							$delete_media_comment -> execute();
							$delete_media_comment_connect = $db -> prepare ("DELETE FROM cmmt_media_connect WHERE cmid = :cmid");
							$delete_media_comment_connect -> bindParam (":cmid", $cmid);
							$delete_media_comment_connect -> execute();
	
						}
				}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
?>