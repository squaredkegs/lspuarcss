<?php
	include ("php/connection.php");
	include ("php/querydata.php");
	include ("php/function.php");
?>

<script>
$(document).ready(function(){
		$(".friend-request").click(function(){
			var frid = $(this).attr("id");
			var name = $(this).attr("name");
			var sid = "<?php echo $_SESSION['log_user'];?>";
			var acc_btn = $(".accept" + frid);
			var rej_btn = $(".reject" + frid);
			var whole_name = $(".fullname" + frid);
			var friend_name = $(whole_name).html();
			var rqst = $(".friend_request" + frid);
			var seen = 1;
					$.ajax({
						type: 'POST',
						url: 'php/friend_request.php',
						data:
						{
							seen: seen,
						},
						
					});
			if(name=='accept'){
				
							$(this).hide();
					$.ajax({
						type: 'POST',
						url: 'php/friend_request.php',
						data:
						{
							accept_visit: sid,
							owner: frid,
						},
						cache: false,
						success: function (data)
						{
							$(rej_btn).hide();
							$(whole_name).hide();
							rqst.html(
							"<span style='margin-left:15px;color:lightblue;'>You are now friends with " + friend_name + "</span>");
							
						},
					});
				
			}
			else{
				$(this).hide();
				$(acc_btn).hide();
				$(whole_name).hide();
					$.ajax({
						type: 'POST',
						url: 'php/friend_request.php',
						data:
						{
							reject_visit: sid,
							owner: frid,
						},
						cache:false,
					});
			}
		});
	});
	
</script>
<style>
	a.new_comment:hover{
		color:blue;
		font-weight:bold;
		background-color:white;
	}
</style>

		<?php
		$child_unseen_numrow = 0;
		$unseen_comment = $db -> prepare ("
				SELECT DISTINCT(comment_connect.news_id) as nid, newsfeed.title as title 
				FROM (SELECT news_id,stud_id FROM post_connect WHERE stud_id = :sid) as post_connect 
				LEFT JOIN comment_connect 
				ON comment_connect.news_id = post_connect.news_id 
				LEFT JOIN newsfeed
				ON newsfeed.news_id = post_connect.news_id
				WHERE comment_connect.post_c_seen = 0 and comment_connect.stud_id != :sid");
		$unseen_comment -> bindParam (":sid", $getid);
		$unseen_comment -> execute();
		$unseen_comment_numrow = $unseen_comment -> rowCount();
		
			
		
		$unseen_reply = $db -> prepare ("SELECT cmmt_id FROM comment_connect WHERE stud_id = :sid") ;
		$unseen_reply -> bindParam (":sid", $getid);
		$unseen_reply -> execute();
		while($unseen_rep_row = $unseen_reply -> fetch(PDO::FETCH_ASSOC)){
		$pid = $unseen_rep_row['cmmt_id'];
		$child_unseen_reply = $db -> prepare ("
				SELECT COUNT(comment_connect.cmmt_id) as cid, comment_connect.stud_id as sid
				FROM comment_connect
				INNER JOIN cmmt_sect
				ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
				WHERE parent_id=:pid 
				AND comment_connect.comment_c_seen = 0 AND comment_connect.stud_id!=:sid");
		$child_unseen_reply -> bindParam (":sid",$getid);
		$child_unseen_reply -> bindParam (":pid", $pid);
		$child_unseen_reply -> execute();
		$child_unseen_numrow = $child_unseen_reply -> rowCount();
			if($child_unseen_numrow>0){
				$res_unseen_reply = $child_unseen_reply -> fetch();
				$count_unseen_reply = $res_unseen_reply['cid'];
			}
			
		}
		$query = $db -> prepare ("SELECT fname, stud_id, lname FROM stud_bas 
			RIGHT JOIN frnd_rqst
			ON frnd_rqst.frst_user = stud_bas.stud_id
			WHERE frnd_rqst.scnd_user = :my_id AND frnd_rqst.status = '1'");
			$query -> bindParam (":my_id", $getid);
			$query -> execute();
			$rqst_count = $query -> rowCount();
							
		$acceptance = $db -> prepare ("SELECT fname, stud_id, lname,scnd_user FROM stud_bas RIGHT JOIN frnd_rqst
			ON frnd_rqst.frst_user = stud_bas.stud_id
			WHERE frnd_rqst.frst_user = :my_id2 AND frnd_rqst.status = '2' AND seen_acceptance='0'");
			$acceptance -> bindParam (":my_id2", $getid);
			$acceptance -> execute();
			$accepted_rqst = $acceptance -> rowCount();
								
			if($rqst_count>0 or $accepted_rqst>0 or $unseen_comment_numrow>0 or $child_unseen_numrow>0)
			{
			?>
				<ul class="jq-dropdown-menu">
					<?php
					$unseen_reply = $db -> prepare ("SELECT cmmt_id,news_id FROM comment_connect WHERE stud_id = :sid") ;
					$unseen_reply -> bindParam (":sid", $getid);
					$unseen_reply -> execute();
					while($unseen_rep_row = $unseen_reply -> fetch(PDO::FETCH_ASSOC)){
					$pid = $unseen_rep_row['cmmt_id'];
					$child_nid = $unseen_rep_row['news_id'];
					$child_unseen_reply = $db -> prepare ("
							SELECT COUNT(cmmt_sect.parent_id) as pid,
							comment_connect.stud_id as sid
							FROM comment_connect
							INNER JOIN cmmt_sect
							ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
							WHERE parent_id=:pid
							AND comment_connect.comment_c_seen = 0 AND comment_connect.stud_id!=:sid");
					$child_unseen_reply -> bindParam (":sid",$getid);
					$child_unseen_reply -> bindParam (":pid", $pid);
					$child_unseen_reply -> execute();
					$child_numrow = $child_unseen_reply -> rowCount();
						while($res_child_unseen_reply = $child_unseen_reply -> fetch(PDO::FETCH_ASSOC)){
						$no_reply_comment = $res_child_unseen_reply['pid'];
							if($no_reply_comment>0){
					?>
						
					<span style='display:block;margin-right:5px;margin-left:5px;'><a href='newsfeed?research=<?php echo $child_nid."#".$pid;?>' onClick="window.location.reload();"><?php echo $no_reply_comment;?> person replied</a></span>
					<?php
							}
						}
					}
					?>
					
					
					
					
					<?php
					while($unseen_comment_row = $unseen_comment -> fetch(PDO::FETCH_ASSOC)){
						$unseen_comment_news_id = $unseen_comment_row['nid'];
						$title = $unseen_comment_row['title'];
						$get_number_of_unseen_comment = $db -> prepare ("SELECT COUNT(cmmt_id) as unseen_c FROM comment_connect WHERE news_id = :nid");
						$get_number_of_unseen_comment -> bindParam (":nid", $unseen_comment_news_id);
						$get_number_of_unseen_comment -> execute();
						$res_gt_no_unsin_cmt = $get_number_of_unseen_comment ->fetch();
						$unseen_c_count =  $res_gt_no_unsin_cmt['unseen_c'];
						//limit_length
					?>
						<span style='display:block;margin-left:5px;margin-right:5px;background-color:lightgray;'><a href='newsfeed?research=<?php echo $unseen_comment_news_id;?>' style='margin-left:3px;margin-right:3px;'class='new_comment'><?php echo $unseen_c_count;?> New Comments on <?php limit_length($title, 32);?></a></span>
					<?php
					}		
					while($accept_row = $acceptance -> fetch(PDO::FETCH_ASSOC)){
					$second_user = $accept_row['scnd_user'];
					$get_friend_data = $db -> prepare ("SELECT fname,lname, stud_id FROM stud_bas WHERE stud_id=:suid");
					$get_friend_data -> bindParam(":suid", $second_user);
					$get_friend_data -> execute();
					$res = $get_friend_data -> fetch();
					$res_fname = $res['fname'];
					$res_lname = $res['lname'];
					$res_sid = $res['stud_id'];
					$res_fullname = $res_fname." ".$res_lname;
					
					?>
					
					<div id="accepted_friend_request" style="background-color:lightgrey;padding-bottom:10px;margin:5px 5px 0 5px;border-radius:3px;">
						<a href="myprofile?user=<?php echo $friend_id; ?>
							"style="margin:20px 15px 0px 15px;">
							<span style="margin:-2px -10px 0px -10px;" class="fullname<?php echo $friend_id;?>">
								<?php
								$res_fullname = limit_length($res_fullname,22);
								?> has accepted your friend request
							</span>
						</a>
					</div>
					<?php
					}
						
						while($row = $query -> fetch(PDO::FETCH_ASSOC))
						{
							$friend_fname = $row['fname'];
							$friend_lname = $row['lname'];
							$friend_id = $row['stud_id'];
							$full_name = $friend_fname. " ". $friend_lname;
						
					?>
							
						<a href="myprofile?user=<?php echo $friend_id; ?>
								"style="margin:20px 15px 0px; 15px;">
						
						<div class="friend_request<?php echo $friend_id;?>">			
							<span class="fullname<?php echo $friend_id;?>">
								<?php
								$fullname = limit_length($full_name,22);
								?>
							</span>
						</a>
							
						<li style="margin:10px 10px 0 10px;">
							<div class='top'>
								<div style="display: table-row;">
									<div style="display: table-cell;">
									<button id="<?php echo $friend_id;?>" style="margin-right:10px;margin-left:10px;"class="btn btn-info friend-request accept<?php echo $friend_id;?>" name="accept">Accept
									</button>
									<button id="<?php echo $friend_id; ?>" class="btn btn-danger friend-request reject<?php echo$friend_id;?>">Reject</button>
									</div>				
								</div>
							</div>
						</div>
							</li>
							<?php
							}
							?>
						</ul>
							<?php
							}
							else
							{
							?>
						<ul class="jq-dropdown-display">
						</ul>
							<?php
							}
							?>
					</span>
		<?php
		
		$seen_accept = $db -> prepare ("UPDATE frnd_rqst SET seen_acceptance = '1' WHERE frst_user = :sid AND seen_acceptance = 0");
		$seen_accept -> bindParam (":sid", $getid);
		$seen_accept -> execute();
		
		?>