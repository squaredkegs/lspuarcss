<?php

	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	
	if(isset($_POST['c_id']) && isset($_POST['n_cm'])){
		$cid = $_POST['c_id'];
		$comment = $_POST['n_cm'];
			if(isset($_FILES['edit_media']['tmp_name'])){
				$delete_previous = $db -> prepare ("SELECT cmmt_media.cmid as cmid, cmmt_media.file_name as filename, cmmt_media.file_path as filepath 
				FROM cmmt_media_connect 
				LEFT JOIN cmmt_media
				ON cmmt_media.cmid = cmmt_media_connect.cmid
				WHERE cmmt_media_connect.cid = :cid");
				$delete_previous -> bindParam (":cid", $cid);
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
				
				$media_name = $_FILES['edit_media']['name'];
				$media_file = $_FILES['edit_media']['tmp_name'];
				$media_size = $_FILES['edit_media']['size'];
				$cut_media = strrpos ($media_name, ".");
				$extension = substr ($media_name, $cut_media + 1);
				$new_name = substr ($media_name, 0, $cut_media);
				$extension_upp = strtoupper ($extension);
				$addstr = createRandomId ('file_name','cmmt_media');
				$real_name = $new_name.$addstr.".".$extension;
				$path = "../image/post&comments/";
				if(
					$extension_upp == "JPEG" ||
					$extension_upp == "PNG"  ||
					$extension_upp == "JPG"){
						$type = "image";
					}
					else if(
					$extension_upp == "MP4" ||
					$extension_upp == "AVI"
						){
						$type = 'video';
					}
				
				if(!is_dir($path)){
					mkdir("../image/post&comments/", 0777, true);
				}
				if(move_uploaded_file($media_file,$path.$real_name)){
					
					$check_media = $db -> prepare ("SELECT cid FROM cmmt_media_connect WHERE cid=:cid");
					$check_media -> bindParam (":cid", $cid);
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
					$change_media -> bindParam (":cid", $cid);
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
								"type" => 'image',
								"cid" => $cid
								));
					$change_media -> closeCursor();
						
					}
				}
				
			}
		$get_media = $db -> prepare("
					SELECT cmmt_media.file_name as filename, cmmt_media.file_path as filepath 
					FROM cmmt_media_connect
					LEFT JOIN cmmt_media
					ON cmmt_media.cmid = cmmt_media_connect.cmid
					WHERE cmmt_media_connect.cid = :cid");
				$get_media -> bindParam (":cid", $cid);
				$get_media -> execute();
				$res_get_media = $get_media -> fetch();
				$new_media_path = $res_get_media['filepath'];
				$new_media_name = $res_get_media['filename'];
				$get_media -> closeCursor();
			?>
						<img src='php/<?php echo $new_media_path.$new_media_name;?>' style='height;175px;width:175px;display:block;margin-top:10px;cursor:pointer;' onclick='enlarge_image(this,event);' id='img_<?php echo $cid;?>'>
							
			<?php	
		$check = $db -> prepare ("SELECT EXISTS(SELECT cmmt_id FROM comment_connect WHERE cmmt_id=:cid AND stud_id=:sid) as count");
		$check -> bindParam (":cid", $cid);
		$check -> bindParam (":sid", $getid);
		$check -> execute();
		$result = $check -> fetch();
		$numrow = $result['count'];
		if($numrow>0){
		$query = $db -> prepare ("UPDATE cmmt_sect SET content=:comment, stat='Edited' WHERE cmmt_id=:cid");
		$query -> bindParam (":cid", $cid);
		$query -> bindParam (":comment", $comment);
		$query -> execute();
		}
		else{
			echo "Error! Database/Query Error";
			die();
		}
		
	}
?>