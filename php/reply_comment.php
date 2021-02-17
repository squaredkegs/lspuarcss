<?php
	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	if(isset($_POST['pcid']) && isset($_POST['comment']) && isset($_POST['nid'])){
		
		$cid = createRandomId('cmmt_id','cmmt_sect');
		$no_media = 0;
		if(isset($_FILES['media']))
		{
			$no_media = 1;
			$media = $_FILES['media']['tmp_name'];
			$cmid = createRandomId('cmid', 'cmmt_media');
			$name = createRandomId('file_name','cmmt_media');
			$temp = explode (".", $_FILES['media']['name']);
			$type = end ($temp);
			$pic_name = $name . "." . end ($temp);	
			$pic_path = "../image/post&comments/";
			$true_path = $pic_path.$pic_name;
			$size = $_FILES['media']['size'];	
				
			if($_FILES['media']['error'] != UPLOAD_ERR_OK){
				die("Upload Failed with error code". $_FILES['media']['error']);
			}
			$info = getimagesize($_FILES['media']['tmp_name']);
			
			if($_FILES['media']['size']>25097152){
				echo 
					"<script>
					alert('Picture size too big');
					</script>";
				die();
			}
			
			if($info === FALSE ){
				die("Invalid/Unable to determine image type of uploaded file");
			}
			
			if(($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)){
				header("location:../myprofile.php?user=$getid");
				die("Invalid file type");
			}
				if(!is_dir($pic_path)){
					mkdir("../image/post&comments/", 0777, true);
				}
		
				move_uploaded_file($media,$pic_path.$pic_name);
					
					$insert_media = $db -> prepare ("
					START TRANSACTION;
					INSERT INTO cmmt_media (cmid,file_name,file_path,file_type,file_size) VALUES(:cmid,:filename,:path,:type,:size);
					INSERT INTO cmmt_media_connect
					(cmid,cid)
					VALUES (:cmid,:cid);
					COMMIT;
					");
					$insert_media -> execute(array(
						"cmid" => $cmid,
						"filename" => $pic_name,
						"path" => $pic_path,
						"type" => $type, 
						"size" => $size,
						"cid" => $cid
					));
					$insert_media -> closeCursor();
				
		}
		
		$fullname = $rfname." ".$rlname;
		$comment = $_POST['comment'];
		$pcid = $_POST['pcid'];
		$nid = $_POST['nid'];		
		$get_depth = $db -> prepare ("SELECT depth FROM cmmt_sect WHERE cmmt_id=:pid");
		$get_depth -> bindParam (":pid", $pcid);
		$get_depth -> execute();
		$res_get_depth = $get_depth -> fetch();
		$depth = $res_get_depth['depth'];
		$depth = $depth + 1;
		$query = $db -> prepare ("
								START TRANSACTION;
								INSERT INTO cmmt_sect (cmmt_id,content,parent_id,depth,media)
								VALUES (:cid,:content,:pid,:depth,:media);
								INSERT INTO comment_connect (cmmt_id,news_id,stud_id) VALUES (:cid2,:nid,:sid);
								COMMIT;");
		$query -> execute(array(
					"cid" => $cid,
					"content" => $comment,
					"pid" => $pcid,
					"depth" => $depth,
					"media" => $no_media,
					"cid2" => $cid,
					"nid" => $nid,
					"sid" => $getid
					));
		$query -> closeCursor();
				
					if($query){
			?>
			<div style='display:block;margin-left:12px;'>
				<span class='hide_comment' onclick='show_section(this,event);'id="show_section_<?php echo $cid;?>" style='margin-bottom:5px;margin-top:5px;font-size:10px;' /><a href='#'><span style='font-size:8.5px;margin-left:-0.5px;'>[+]</span></a></span>
					<span class='hide_comment' onclick='hide_section(this,event);'id="hide_section_<?php echo $cid;?>" style='margin-bottom:5px;margin-top:5px;font-size:10px;'><a href='#'>
						<span style='font-size:8.5px;margin-right:-4px;'>
						[
						</span>
						-
						<span style='font-size:8.5px;margin-left:-4px;'>
						]
						</span>
					</a>
				</span>
				
			</div>			
			<?php
			if($depth %2 == 0 ){
			?>
			
			<div style="background-color:white;margin-bottom:10px;padding-bottom:0.5px;padding-top:5px;margin-top:0px;border-radius:5px;margin-right:10px;" class="whole_comment_div_<?php echo $cid;?>" id="pid_<?php echo $cid;?>">
			
			<?php
			}
			else{
			?>
			<div style="background-color:#DCDCDC;margin-bottom:10px;padding-bottom:0.5px;padding-top:5px;margin-top:15px;border-radius:5px;margin-right:10px;" class="whole_comment_div_<?php echo $cid;?>" id="pid_<?php echo $cid;?>">
			<?php
			}
			?>
			<div class='col-md-12' style='margin-left:0px;'>
			<div class='col-md-1' style='margin-right:0px;'>
			<button class='c_vote' name='upvote' id='c_up_<?php echo $cid;?>' style='margin: 10px 0 5px 0;' onclick='upvote_downvote(this,event)'>
				<img src='image/newsfeed/arrow.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
			<button class='c_vote' name='remove_upvote' id='c_remove_up_<?php echo $cid;?>'  style='visibility:hidden;display:none;' onclick='upvote_downvote(this,event)'>
				<img src='image/newsfeed/arrowg.png' title='rUpvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
			
			<button class='c_vote' name='downvote' id='c_down_<?php echo $cid;?>' onclick='upvote_downvote(this,event)'>
				<img src='image/newsfeed/arrow1.png' title='downvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
			<button class='c_vote' name='remove_downvote' id='c_remove_down_<?php echo $cid;?>' style='visibility:hidden;display:none;' onclick='upvote_downvote(this,event)'>
				<img src='image/newsfeed/arrowr.png' title='rdownvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
			
			<span class='comment-score c_upvoted_sc_<?php echo $cid;?>' style='margin-left:10px;visibility:invisible;display:none;' >1</span>
			<span class='comment-score c_neutral_sc_<?php echo $cid;?>' style='margin-left:10px;'>0</span>
			<span class='comment-score c_downvoted_sc_<?php echo $cid;?>' style='margin-left:10px;visibility:invisible;display:none;'>-1</span>
			
			</div>
			<div class='col-md-11'>
				<div style='position:relative;'>
				<span style='margin-left:10px;'><a href='myprofile?user=<?php echo $getid;?>'class='user'><b><?php echo $fullname; ?></b></a></span>
				</div>
				<div class='div-post-comment'>
				<span style='margin-left:10px;word-wrap:break-word;' class='post-comment' id='user_comment_<?php echo $cid;?>'><?php echo $comment;?></span>
				</div>
				
				<span class='media-container'>
				<?php
				if($no_media>0){
					$get_picture = $db -> prepare ("SELECT cmmt_media.file_path as path, cmmt_media.file_name as name FROM cmmt_media_connect LEFT JOIN cmmt_media ON cmmt_media.cmid = cmmt_media_connect.cmid WHERE cmmt_media_connect.cid = :cid");
					$get_picture -> bindParam (":cid", $cid);
					$get_picture -> execute();
					$res_get_picture = $get_picture -> fetch();
					$path = $res_get_picture['path'];
					$file_name = $res_get_picture['name'];
					
				?>
				<img src='php/<?php echo $path.$file_name;?>' style='height;175px;width:175px;display:block;cursor:pointer;' onclick='enlarge_image(this,event);' id='img_<?php echo $cid;?>'>
				
				<?php
				}
				?>
				</span>
			</div>
			</div>
			<ul style='margin-left:15px;display:inline-block;'>
				<li style='display:inline;'></li>
				<li style='display:inline;margin-left:10px;' id='<?php echo $cid;?>' class='save_comment save_comment_<?php echo $cid;?>' onclick='save_comment(this.id,event);'><a href='#'>
				Save
				</a>
				</li>				
				<li style='display:inline;margin-left:10px;' class='report_post' id='<?php echo $cid;?>' name='Comment' onclick='report_post(this,event)'><a href='#'>Report</a></li>
				<?php
					if($depth<10){
				?>		
				<li style='display:inline;margin-left:10px;' class='reply_comment comment_id_<?php echo $cid;?>' id='cmmt_id_<?php echo $cid;?>' onclick='reply_comment(this,event);'><a href='#'>Reply</a></li>
				<?php
					}
				?>
				<li style='display:inline;margin-left:10px;' class='edit_comment' id='edt_cmmt_id_<?php echo $cid;?>' onclick='edit_comment(this,event);'><a href='#'>Edit</a></li>
				<li style='display:inline;margin-left:10px;' class='delete_comment'onclick='show_option_delete_comment(this,event);' id='dlt_<?php echo $cid;?>'><span style='color:black;'><a href='#'>Delete</a></span></li>	
				<li style="display:inline;margin-left:10px;"><span style= "color:black;"><a href="#pid_<?php echo $pcid;?>" class='to_parent' onclick='highlight(this,event);'>Parent</a></span></li>
															
				<div class='reply_form_<?php echo $cid;?>'>
				</div>
				
				<div class='child' id='child_of_comment_<?php echo $cid;?>'>
				</div>	
			</ul>
			</div>
			<?php
					}
					else{
						echo "Error! Please Comment Again Later!";
					}
					
			
	}
	else{
		echo  "
			<script>
			alert('Error!');
			</script>";
		die();
		
	}
?>