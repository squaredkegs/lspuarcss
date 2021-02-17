<?php

include_once ("function.php");
include_once ("connection.php");
include_once ("user_data.php");
	if(isset($_POST['submit'])){
		$datetime = date("Y-m-d H:i:s");
		$desc = $_POST['desc'];
		$title = $_POST['title'];
		$dept = $_POST['dept'];
		$course = $_POST['select_course'];
		$media = 0;
		$nid = createRandomId('news_id','newsfeed');
		
		if(isset($_FILES['post_media']['tmp_name'])){
			$media = 1;
			$media_file = $_FILES['post_media']['tmp_name'];
			$media_temp_name = $_FILES['post_media']['name'];
			$randomstr = createRandomId('file_name','cmmt_media');
			$cmid = createRandomId ('cmid', 'cmmt_media');
			$cut_name = strrpos($media_temp_name, ".");
			$media_name = substr($media_temp_name, 0, $cut_name);
			$extension = substr($media_temp_name, $cut_name);
			$new_name = $media_name.$randomstr.$extension;
			$filepath = "../image/post&comments/";
			$size = $_FILES['post_media']['size'];
			$type = "post";
			if(!is_dir($filepath)){
				mkdir("../image/post&comments/", 0777, true);
			}
			
				if(move_uploaded_file($media_file,$filepath.$new_name)){
					$insert_media = $db -> prepare 
					("START TRANSACTION;
					INSERT INTO cmmt_media 	
					(cmid,file_name,file_path,file_type,file_size,type)
					VALUES (:cmid,:new_name,:filepath,:extension,:size,:type);
					INSERT INTO cmmt_media_connect (cmid,cid)
					VALUES (:cmid,:nid);
					COMMIT;");
					$insert_media -> execute(array(
						"cmid" => $cmid,
						"new_name" => $new_name,
						"filepath" => $filepath,
						"extension" => $extension,
						"size" => $size,
						"type" => $type,
						"nid" => $nid,
						));
					$insert_media -> closeCursor();	
				}
			
		}
		
		
		$query = $db -> prepare ("
							
							START TRANSACTION;
							INSERT INTO newsfeed (news_id,title,description,campus,department,course,date_and_time,media) VALUES(:nid,:title,:desc,:campus,:dept,:course,:datetime,:media);
							INSERT INTO post_connect (stud_id,news_id) VALUES (:sid, :nid2);
							COMMIT;");
							
		$query -> execute(
		array(
			"nid" => $nid,
			"title" => $title,
			"desc" => $desc,
			"campus" => $rcampus,
			"dept" => $dept,
			"course" => $course,
			"datetime" => $datetime,
			"media" => $media,
			"sid" => $getid,
			"nid2" => $nid
		));
		$query2 = $isam_db -> prepare ("INSERT INTO isam_newsfeed (news_id,title,description,campus,department,course,date_and_time) VALUES(:nid,:title,:desc,:campus,:dept,:course,:datetime);");
		$query2 -> execute(array(
			"nid" => $nid,
			"title" => $title,
			"desc" => $desc,
			"campus" => $rcampus,
			"dept" => $dept,
			"course" => $course,
			"datetime" => $datetime
		
			));
			if($query)
			{
			header("location:../newsfeed.php?research=$nid");
			}
			else{
				echo 
					"Couldn't submit Post! Website error!";
			}
			
	}
	else{
		echo "Error No submit";
		die();
	}

?>