<?php

	include_once ("connection.php");
	include_once ("function.php");
	include_once ("querydata.php");
	
	if(isset($_POST['desc']) && isset($_POST['nid'])){
		$desc = $_POST['desc'];
		$nid = $_POST['nid'];
		$media = 0;
			if(isset($_FILES['edit_post_media']['tmp_name'])){
				$media = 1;
				$delete_previous = $db -> prepare ("SELECT cmmt_media.cmid as cmid, cmmt_media.file_name as filename, cmmt_media.file_path as filepath 
				FROM cmmt_media_connect 
				LEFT JOIN cmmt_media
				ON cmmt_media.cmid = cmmt_media_connect.cmid
				WHERE cmmt_media_connect.cid = :cid");
				$delete_previous -> bindParam (":cid", $nid);
				$delete_previous -> execute();
				$numrow_delete_previous = $delete_previous -> rowCount();
				if($numrow_delete_previous>0){
				$res_del_prev = $delete_previous -> fetch();
				$prev_filename = $res_del_prev['filename'];
				$prev_filepath = $res_del_prev['filepath'];
				$prev_cmid = $res_del_prev['cmid'];
				
					if(file_exists($prev_filepath.$prev_filename)){
						unlink($prev_filepath.$prev_filename);
					}
				}
				$delete_previous -> closeCursor();
				$media_name = $_FILES['edit_post_media']['name'];
				$media_file = $_FILES['edit_post_media']['tmp_name'];
				$media_size = $_FILES['edit_post_media']['size'];
				$cut_name = strrpos($media_name, ".");
				$raw_name = substr($media_name, 0,$cut_name);
				$extension = substr($media_name, $cut_name + 1);
				$addstr = createRandomId('file_name','cmmt_media');
				$extension_upp = strtoupper($extension);
				$path = "../image/post&comments/";
				$real_name = $raw_name.$addstr.".".$extension;
					if(
					$extension_upp == "JPG"  ||
					$extension_upp == "JPEG" ||
					$extension_upp == "PNG" 
					){
						$type = 'image';
					}
					else{
						$type = 'video';
					}
					if(!is_dir($path)){
						mkdir("../image/post&comments/", 0777, true);
					}
				if(move_uploaded_file($media_file,$path.$real_name)){
			
					$check_media = $db -> prepare ("SELECT cid FROM cmmt_media_connect WHERE cid=:cid");
					$check_media -> bindParam (":cid", $nid);
					$check_media -> execute();
					$numrow_check_media = $check_media -> rowCount();
					if($numrow_check_media>0){
					$change_media = $db -> prepare 
						("
						UPDATE cmmt_media
						LEFT JOIN cmmt_media_connect
						ON cmmt_media.cmid = cmmt_media_connect.cmid
						  SET cmmt_media.file_name = :real_name 
						WHERE cmmt_media_connect.cid = :cid
						");
					$change_media -> bindParam (":real_name", $real_name);
					$change_media -> bindParam (":cid", $nid);
					$change_media -> execute();
					$change_media -> closeCursor();
					}
					else{
					
					$cmid = createRandomId('cmid','cmmt_media');
					$change_media = $db -> prepare 
						("
						START TRANSACTION;
						INSERT INTO cmmt_media (cmid,file_name,file_path,file_type,file_size,type)
						VALUES (:cmid,:filename,:filepath,:filetype,:filesize,:type);
						INSERT INTO cmmt_media_connect
						(cmid,cid)
						VALUES
						(:cmid,:cid);
						UPDATE cmmt_sect SET media='1' WHERE cmmt_id = :cid;
						COMMIT;
						");
					$change_media -> execute(array(
								"cmid" => $cmid,
								"filename" => $real_name,
								"filepath" => $path,
								"filetype" => $extension,
								"filesize" => $media_size,
								"type" => $type,
								"cid" => $nid
								));
					$change_media -> closeCursor();
						
					}	
				}
			}
			if($media>0){
			$get_media = $db -> prepare("
					SELECT cmmt_media.file_name as filename, cmmt_media.file_path as filepath 
					FROM cmmt_media_connect
					LEFT JOIN cmmt_media
					ON cmmt_media.cmid = cmmt_media_connect.cmid
					WHERE cmmt_media_connect.cid = :cid");
				$get_media -> bindParam (":cid", $nid);
				$get_media -> execute();
				$res_get_media = $get_media -> fetch();
				$new_media_path = $res_get_media['filepath'];
				$new_media_name = $res_get_media['filename'];
				$get_media -> closeCursor();
			?>
						<img src='php/<?php echo $new_media_path.$new_media_name;?>'/ style='display:block;width:250px;height:250px;margin-left:75px;margin-top:40px;margin-bottom:10px;'>
							
			<?php	
			}
		
		if(($nid==null || $nid=="") && ($desc==null || $desc=="")){
			echo 	"</script>
					alert('Description cannot be empty!');
					window.location.href='../newsfeed?research=$nid';
					</script>";
		}
		else{
			$check = $db -> prepare ("SELECT EXISTS (SELECT news_id, stud_id FROM post_connect WHERE news_id=:nid AND stud_id=:sid) as result");
			$check -> bindParam (":nid", $nid);
			$check -> bindParam (":sid", $getid);
			$check -> execute();
			$res = $check -> fetch();
			$count = $res['result'];
			if($count>0){
				$query = $db -> prepare ("UPDATE newsfeed SET description=:desc, status='Edited', media=:media WHERE news_id = :nid");
				$query -> bindParam (":desc", $desc);
				$query -> bindParam (":nid", $nid);
				$query -> bindParam (":media", $media);
				$query -> execute();
				if($query){
					
					}
			}
			else{
			echo 	"</script>
					alert('Database error try again later!');
					window.location.href='../newsfeed?research=$nid';
					</script>";
			}
		}
	}
	else{
		echo "Cannot detect description Error! Try Again";
		header("location:../index.php");
	}

?>