<?php
	include_once ("connection.php");
	include_once ("querydata.php");
	include_once ("function.php");
	if(isset($_POST['nid']) && isset($_POST['comment'])){
		$nid = $_POST['nid'];
		$comment = $_POST['comment'];
		$cid = createRandomId('cmmt_id','cmmt_sect');
		
		$media = 0;
			if(isset($_FILES['post_media']['tmp_name'])){
				$media = 1;
				$media_file = $_FILES['post_media']['tmp_name'];
				$media_name = $_FILES['post_media']['name'];
				$media_size = $_FILES['post_media']['size'];
				$cut_m_name = strrpos($media_name, ".");
				$cmid = createRandomId('cmid','cmmt_media');
				$addstr = createRandomId('file_name','cmmt_media');
				$extension = substr($media_name, $cut_m_name + 1);
				$real_m_name = substr($media_name, 0, $cut_m_name);
				$new_media_name = $real_m_name.$addstr.".".$extension;
				$extension_upp = strtoupper($extension);
				$media_path = "../image/post&comments/";
				if(	$extension_upp == "JPG"  || 
					$extension_upp == "JPEG" ||
					$extension_upp == "PNG"
					){
					$media_type = "image";
				}
				else if(
					$extension_upp == "MP4"  || 
					$extension_upp == "AVI"
					){
					$media_type = "video";
				}
				if(!is_dir($media_path)){
					mkdir("../image/post&comments/", 0777, true);
				}
				if(move_uploaded_file($media_file,$media_path.$new_media_name)){
					$insert_to_media_tbl = $db -> prepare ("
					START TRANSACTION;
					INSERT INTO cmmt_media (cmid,file_name,file_path,file_type,file_size,type) VALUES
					(:cmid,:filename,:filepath,:filetype,:filesize,:type);
					INSERT INTO cmmt_media_connect (cmid,cid)
					VALUES (:cmid,:cid);
					COMMIT;");
					$insert_to_media_tbl -> execute(array(
							"cmid" => $cmid,
							"filename" => $new_media_name,
							"filepath" => $media_path,
							"filetype" => $extension,
							"filesize" => $media_size,
							"type" => $media_type,
							"cid" => $cid,
							));
					$insert_to_media_tbl -> closeCursor();
				}
				
			
			}
			
		if(($nid==null || $comment==null) && ($nid=="" || $comment=="")){
			echo 	"<script>
					alert('Comment cannot be empty');
					</script>";
			die();		
		}
		else{
		$fullname = $rfname." ".$rlname;
		$query = $db -> prepare ("
								START TRANSACTION;
								INSERT INTO cmmt_sect (cmmt_id,content,parent_id,depth,media)
								VALUES (:cid,:content,:pid,:depth,:media);
								INSERT INTO comment_connect (cmmt_id,news_id,stud_id,comment_c_seen) VALUES (:cid2,:nid,:sid,:p_seen);
								COMMIT;");
		$query -> execute(array(
					"cid" => $cid,
					"content" => $comment,
					"pid" => $nid,
					"depth" => '1',
					"media" => $media,
					"cid2" => $cid,
					"nid" => $nid,
					"sid" => $getid,
					"p_seen" => 1
					));
		$query -> closeCursor();			
					if($query){
			?>			
			<div style='background-color:#DCDCDC;margin-bottom:10px;padding-bottom:0.5px;padding-top:5px;margin-top:15px ; border-radius:10px;' class='whole_comment_div_<?php echo $cid;?>' id="pid_<?php echo $cid;?>">
			<div class='col-md-12' style='margin-left:0px;'>
			<div class='col-md-1' style='margin-right:0px;'>
			<button class='c_vote' name='upvote' id='c_up_<?php echo $cid;?>' style='margin: 10px 0 5px 0;' onclick='upvote_downvote(this,event)'>
				<img src='image/newsfeed/arrow.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
			<button class='c_vote' name='remove_upvote' id='c_remove_up_<?php echo $cid;?>'  style='visibility:hidden;display:none;' onclick='upvote_downvote(this,event)'>
				<img src='image/newsfeed/arrowg.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
				&nbsp;
			<button class='c_vote' name='downvote' id='c_down_<?php echo $cid;?>' onclick='upvote_downvote(this,event)'>
				<img src='image/newsfeed/arrow1.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
			<button class='c_vote' name='remove_downvote' id='c_remove_down_<?php echo $cid;?>' style='visibility:hidden;display:none;' onclick='upvote_downvote(this,event)'>
				<img src='image/newsfeed/arrowr.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
			
			<span class='comment-score c_upvoted_sc_<?php echo $cid;?>' style='margin-left:10px;visibility:invisible;display:none;' >1</span>
			<span class='comment-score c_neutral_sc_<?php echo $cid;?>' style='margin-left:10px;'>0</span>
			<span class='comment-score c_downvoted_sc_<?php echo $cid;?>' style='margin-left:10px;visibility:invisible;display:none;'>-1</span>
			
			</div>
			<div class='col-md-11'>
				<div style='position:relative;'>
				<span style='margin-left:10px;'><a href='myprofile?user=<?php echo $getid;?>'class='user'><b><?php echo $fullname; ?></a></b></span>
				</div>
				<div class='div-post-comment'>
				<span style='margin-left:10px;word-wrap:break-word;' class='post-comment' id='user_comment_<?php echo $cid;?>'><?php echo $comment;?></span>
				
				
				</div>
				<span class='media-container'>
				<?php
				
				if($media==1){
						
					$get_media = $db -> prepare 
					("SELECT cmmt_media.cmid as cmid,cmmt_media.file_name as medianame,cmmt_media.file_path as mediapath,cmmt_media.type as type
					FROM cmmt_media_connect
					LEFT JOIN cmmt_media
					ON cmmt_media.cmid = cmmt_media_connect.cmid
					WHERE cmmt_media_connect.cid = :nid
					");
					$get_media -> bindParam (":nid", $cid);
					$get_media -> execute();
					$numrow_media = $get_media -> rowCount();
					if($numrow_media>0){
					$res_get_media = $get_media -> fetch();
					$media_name = $res_get_media['medianame'];
					$media_path = $res_get_media['mediapath'];
					$media_type = $res_get_media['type'];
						if($media_type=='image'){
				?>
					
					<img src='php/<?php echo $media_path.$media_name;?>'/ style='height;175px;width:175px;display:block;' onclick='enlarge_image(this,event);' id='img_<?php echo $cid;?>'>
				
				<?php
						}
					}
					else{
						echo "Image Error";
					}
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
				<li style='display:inline;margin-left:10px;' class='reply_comment comment_id_<?php echo $cid;?>' id='cmmt_id_<?php echo $cid;?>' onclick='reply_comment(this,event);'><a href='#'>Reply</a></li>
				<li style='display:inline;margin-left:10px;' class='edit_comment' id='edt_cmmt_id_<?php echo $cid;?>' onclick='edit_comment(this,event);'><a href='#'>Edit</a></li>
				<li style='display:inline;margin-left:10px;' class='delete_comment'onclick='show_option_delete_comment(this,event);' id='dlt_<?php echo $cid;?>'><span style='color:black;'><a href='#'>Delete</a></span></li>				
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
		
	}
	else{
		echo  "
			<script>
			alert('Error!');
			window.location.href='../index.php';
			</script>";
		die();
		
	}
?>