		<?php
function child_comment($nid){
			
	include ("connection.php");
	include ("querydata.php");
			$comment_query = $db -> prepare("SELECT stud_bas.fname as fname, stud_bas.lname as lname, stud_bas.stud_id as sid, cmmt_sect.cmmt_id as cid, cmmt_sect.content as comment, cmmt_sect.parent_id as pid, cmmt_sect.stat as stat, cmmt_sect.type as type, cmmt_sect.date as date_n_time, cmmt_sect.media as media, cmmt_sect.depth as depth, COALESCE(SUM(upvote_comment.score),0) as scored FROM comment_connect 
			LEFT JOIN stud_bas
			ON stud_bas.stud_id = comment_connect.stud_id
			LEFT JOIN cmmt_sect
			ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
			LEFT JOIN newsfeed
			ON newsfeed.news_id = comment_connect.news_id
			LEFT JOIN upvote_comment
			ON upvote_comment.cmmt_id = cmmt_sect.cmmt_id
			WHERE cmmt_sect.parent_id = :nid GROUP BY cmmt_sect.cmmt_id");
			$comment_query -> bindParam (":nid", $nid);
			$comment_query -> execute();
			while ($row = $comment_query -> fetch(PDO::FETCH_ASSOC)){
				$comment = $row['comment'];
				$cid = $row['cid'];
				$c_stat = $row['stat'];	
				$c_sid = $row['sid'];
				$c_depth = $row['depth'];
				$c_type = $row['type'];
				$c_datetime = $row['date_n_time'];
				$c_fname = $row['fname'];
				$c_media = $row['media'];
				$c_lname = $row['lname'];
				$c_fullname = $c_fname." ".$c_lname;
				$c_score_query = $db -> prepare ("SELECT SUM(score) as score FROM upvote_comment WHERE cmmt_id=:cid1");
				$c_score_query -> bindParam (":cid1", $cid);
				$c_score_query -> execute();
				$num_c_score = $c_score_query -> rowCount();
				if($num_c_score>0){
					$res_c_score = $c_score_query -> fetch();
					$c_score = $res_c_score['score'];
					if($c_score==null or $c_score==""){
						$c_score = 0;
					}
					else{
					$c_score = $res_c_score['score'];
					}
				}
				else if($num_c_score==null){
					$c_score = "Error";
				}
				$chck_if_voted_qr = $db -> prepare ("SELECT * FROM upvote_comment WHERE cmmt_id=:cid1 AND stud_id=:sid");
				$chck_if_voted_qr -> bindParam (":cid1", $cid);
				$chck_if_voted_qr -> bindParam (":sid", $getid);
				$chck_if_voted_qr -> execute();
				$num_chk_if_vtd = $chck_if_voted_qr -> rowCount();
				if($num_chk_if_vtd==1){
					$res_num_if_vtd = $chck_if_voted_qr -> fetch();
					$c_user_score = $res_num_if_vtd['score'];
					if($c_user_score==1){
						$c_up_score = $c_score;
						$c_down_score = $c_score - 2;
						$c_score = $c_score - 1;
						$n_visibility = "hide-object";
						$up_visibility = "show-object";
						$down_visibility = "hide-object";
						$up_button_visibility = "hide-object";
						$down_button_visibility = "show-object";
					}
					else if($c_user_score==-1){
						$c_down_score = $c_score;
						$c_up_score = $c_score + 2;
						$c_score = $c_score + 1;
						$n_visibility = "hide-object";
						$up_visibility = "hide-object";
						$down_visibility = "show-object";
						$up_button_visibility = "show-object";
						$down_button_visibility = "hide-object";
					}
				}
				else if($num_chk_if_vtd==0){
					$c_user_score = 0;
					$c_up_score = $c_score + 1;
					$c_down_score = $c_score - 1;
					$c_score = $c_score;
					$n_visibility = "show-object";
					$up_visibility = "hide-object";
					$down_visibility = "hide-object";
					$up_button_visibility = "comment-button";	
					$down_button_visibility = "comment-button";
				}	
		
		
				
		?>
			<!--Start of Parent Comment-->
			
			<?php
				
					$fwords = $db->query("SELECT filter_word FROM filter_tbl")->fetchAll(PDO::FETCH_COLUMN);
				
					foreach($fwords as $t)
					{
						if (stripos($comment,$t) !== false) {
							continue 2;
						}
						else{
						}
					}
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
				if($c_type!='Deleted'){
					if($c_type!='Removed'){
			?>
			
			<?php
			if($c_depth %2 == 0 ){
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
			<div class="col-md-12" style='margin-left:0px;'>
			<div class="col-md-1" style='margin-right:0px;'>
			
				<button class="c_vote <?php echo $up_button_visibility;?>" name="upvote" id="c_up_<?php echo $cid;?>" style="margin: 10px 0 5px 0;" onclick="upvote_downvote(this,event)">
					<img src='image/newsfeed/arrow.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
				<button class="c_vote <?php echo $up_visibility ;?>" name="remove_upvote" id="c_remove_up_<?php echo $cid ;?>" style="margin: 10px 0 5px 0;" onclick="upvote_downvote(this,event)">
					<img src='image/newsfeed/arrowg.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
			
				<button class="c_vote <?php echo $down_button_visibility ;?>" name="downvote" id="c_down_<?php echo $cid;?>" style="margin: 10px 0 5px 0;" onclick="upvote_downvote(this,event)">
					<img src='image/newsfeed/arrow1.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
				<button class="c_vote <?php echo $down_visibility ;?>" name="remove_downvote" id="c_remove_down_<?php echo $cid ;?>" style="margin: 10px 0 5px 0;" onclick="upvote_downvote(this,event)">
					<img src='image/newsfeed/arrowr.png' title='Upvote' alt='logo' style='margin-top:4px;' width='15' height='15' border='0'></button>
				
				<span class="comment-score c_upvoted_sc_<?php echo $cid ;?> <?php echo $up_visibility;?>" style="margin-left:10px;"><?php echo $c_up_score ;?></span>
				<span class="comment-score c_neutral_sc_<?php echo $cid ;?> <?php echo $n_visibility;?>" style="margin-left:10px;"><?php echo $c_score ;?></span>
				<span class="comment-score c_downvoted_sc_<?php echo $cid ;?> <?php echo $down_visibility;?>" style="margin-left:10px;"><?php echo $c_down_score;?></span>
			
			</div>
			<div class="col-md-11">
				<div style="position:relative;">
				<!--full name on comment-->
				<span style="margin-left:0px;"><a href="myprofile?user=<?php echo $c_sid;?>"class="user"><b style="color:#4169E1;"><?php echo $c_fullname;?></b></a>
					<span title="Edited Comment" style='cursor:help;'>
					<?php 
						if($c_stat=="Edited"){
							echo "*";
							}
					?>
					</span>
				</span>
				</div>
				
				<div class="div-post-comment">
				<span style="margin-left:10px;word-wrap:break-word;" class="post-comment" id="user_comment_<?php echo $cid;?>"><?php echo $comment;?></span>
				</div>
				<span class='media-container'>
					<?php
				if($c_media>0){
					$get_picture = $db -> prepare ("SELECT cmmt_media.file_path as path, cmmt_media.file_name as name FROM cmmt_media_connect LEFT JOIN cmmt_media ON cmmt_media.cmid = cmmt_media_connect.cmid WHERE cmmt_media_connect.cid = :cid");
					$get_picture -> bindParam (":cid", $cid);
					$get_picture -> execute();
					$res_get_picture = $get_picture -> fetch();
					$path = $res_get_picture['path'];
					$name = $res_get_picture['name'];
					
				?>
				
				<img src='php/<?php echo $path.$name;?>' id='img_<?php echo $cid;?>' style='height;175px;width:175px;display:block;cursor:pointer;' onclick='enlarge_image(this,event);'>
				
				<?php
				}
				?>
				</span>
			</div>
			</div>
			<ul style="margin-left:15px;">
				<li style="display:inline;margin-left:10px;" id="<?php echo $cid;?>" class="save_comment save_comment_<?php echo $cid;?>" onclick="save_comment(this.id,event);"><a href="#">
				<?php
					$check = $db -> prepare ("SELECT oid FROM stud_save WHERE oid=:cid AND stud_id=:sid");
					$check -> bindParam (":cid", $cid);
					$check -> bindParam (":sid", $getid);
					$check -> execute();
					$save_numrow = $check -> rowCount();
						if($save_numrow==0){
							echo "Save";
						}
						else if($save_numrow==1){
							echo "Unsave";
						}
				?>
				</a>
				</li>				
				<!--This is report comment-->
				<li style="display:inline;margin-left:10px;" class="report_post" id="<?php echo $cid;?>" name="Comment" onclick="report_post(this,event)"><a href="#">Report</a></li>
					<?php
					if($c_depth<10){
					?>
				<li style="display:inline;margin-left:10px;" class="reply_comment comment_id_<?php echo $cid;?>" id="cmmt_id_<?php echo $cid;?>" onclick="reply_comment(this,event);"><a href="#">Reply</a></li>
				<?php
					}
					if($c_sid==$getid){
				?>
				<li style="display:inline;margin-left:10px;" class="edit_comment" id="edt_cmmt_id_<?php echo $cid;?>" onclick="edit_comment(this,event);"><a href="#">Edit</a></li>
				<li style="display:inline;margin-left:10px;" class="delete_comment"onclick="show_option_delete_comment(this,event);" id="dlt_<?php echo $cid;?>"><span style="color:black;"><a href="#">Delete</a></span></li>
				<li style="display:inline;margin-left:10px;"><span style= "color:black;"><a href="#pid_<?php echo $nid;?>" class='to_parent'onclick='highlight(this,event);'>Parent</a></span></li>
									
				<?php
						}
				?>
				<div class="reply_form_<?php echo $cid;?>">
				</div>
				
				<div class="child" id="child_of_comment_<?php echo $cid;?>">
						<?php
							child_comment($cid);
						?>
				</div>
			</ul>
			</div>
			<?php
				}
			}
			//If Deleted
			//If user's comment has been deleted or removed by admin comment
			//Arvin
			else{
			?>
			<?php
			if($c_depth %2 == 0 ){
			?>
			
			<div style="background-color:#F5DEB3;margin-bottom:10px;padding-bottom:0.5px;padding-top:5px;margin-top:0px;border-radius:5px;margin-right:10px;" class="whole_comment_div_<?php echo $cid;?>" id="pid_<?php echo $cid;?>">
			
			<?php
			}
			else{
			?>
			<div style="background-color:#D2B48C;margin-bottom:10px;padding-bottom:0.5px;padding-top:5px;margin-top:15px;border-radius:5px;margin-right:10px;" class="whole_comment_div_<?php echo $cid;?>" id="pid_<?php echo $cid;?>">
			<?php
			}
			?>
				
			<div class="col-md-12" style='margin-left:-14px;'>
			
			<div class="col-md-11">
				<div style="position:relative;">
				<!--full name on comment-->
				<span style="margin-left:0px;"><a href="myprofile?user=<?php echo $c_sid;?>"class="user">[Comment Deleted]</a>
					
				</span>
				</div>
				
				<div class="div-post-comment">
				<span style="margin-left:10px;word-wrap:break-word;" class="post-comment" id="user_comment_<?php echo $cid;?>"><?php echo $comment;?></span>
				</div>
				
			</div>
			</div>
			
			
			
			<ul style="margin-left:15px;">
				
				
				<div class="child" id="child_of_comment_<?php echo $cid;?>">
						<?php
							child_comment($cid);
						?>
				</div>
			</ul>
			</div>
			<?php
			}
			?>
			<!--HERE-->
		<?php
		
			if($c_type=='Removed' or $c_type=='Deleted'){
			$check_if_has_child_comment = $db -> prepare ("call comment_procedure(:cid)");
				$check_if_has_child_comment -> bindParam (":cid", $cid);
				$check_if_has_child_comment -> execute();
				$pid = $check_if_has_child_comment -> rowCount();
				$check_if_has_child_comment -> closeCursor();
				if($pid==0){
					$query = $db -> prepare ("DELETE FROM cmmt_sect WHERE cmmt_id=:cid");
					$query -> bindParam (":cid", $cid);
					$query -> execute();
				
		
				}
				}
			}	

		?>
			
			
<?php
	
}	
?>	