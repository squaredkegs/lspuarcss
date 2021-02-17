<?php
	if(file_exists("../php/connection.php")){
		include_once ("../php/connection.php");
		include_once ("../php/querydata.php");
		include_once ("../php/profile_query.php");
		include_once ("../php/profile_query.php");
		include_once ("../php/function.php");
	}
	else if(file_exists("php/connection.php")){
		include_once ("php/connection.php");
		include_once ("php/querydata.php");
		include_once ("php/profile_query.php");
		include_once ("php/profile_query.php");
		include_once ("php/function.php");
	}
		if($getid==$user_id || !isset($_GET['user'])){
			$real_id = $getid;
		}
		else{
			$real_id = $user_id;
		}

?>
<script type="text/javascript">

	$(document).ready(function(){
		$("#comment_n_post").click(function(){
			$("#post_container").hide();
			$("#comment_container").show();
		});
	});
	
	$(document).ready(function(){
		$("#comment_n_post_2").click(function(){
			$("#post_container").show();
			$("#comment_container").hide()
		});
	});
</script>
				<div class="col-md-9 main-post-section" style="margin-left:0px;display:inline;">
					<div class="col-md-12" id="comment_post_container" style="background-color:white; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);  border-radius:4px;">
						<div id="post_container">
							<div>
							<button style="background-color:lightgrey;border-radius:5px;margin-top:5px;" id="comment_n_post">See Comments</button>
							</div>
								<?php
							if($getid==$user_id || !isset($_GET['user'])){
								$real_id = $getid;
							}
							else{
								$real_id = $user_id;
							}
								$gpst_query = $db -> prepare ("SELECT newsfeed.title as title, newsfeed.news_id as nid, newsfeed.date_and_time as datetime FROM post_connect
								INNER JOIN stud_bas
								ON post_connect.stud_id = stud_bas.stud_id
								INNER JOIN newsfeed
								ON post_connect.news_id = newsfeed.news_id
								WHERE post_connect.stud_id=:sid");
								$gpst_query -> bindParam (":sid", $real_id);
								$gpst_query -> execute();
								$gpst_numrow = $gpst_query -> rowCount();
								if($gpst_numrow==0)
								{
								?>
									<span style="font-size:24px;color:grey;">No Posts</span>
								<?php
								}
								else
								{
								while($row = $gpst_query -> fetch(PDO::FETCH_ASSOC))
								{
									
								$title = $row['title'];
								$nid = $row['nid'];
								$datetime = $row['datetime'];
								$newdate = date("M d, Y", strtotime($datetime));
								?>
								<div style="display:block;margin-bottom:15px;margin-top:10px; color:#1E90FF; ">
								<a href="newsfeed.php?research=<?php echo $nid; ?>" style="font-size:18px;"><?php displayText($title);?></a>
								<span style="font-size:8px;display:block;"><?php echo $newdate;?></span>
								</div><hr>
								<?php
								}
								}
								?>
							</div>
								<div id="comment_container" style="display:none;">
								<div>
								<button style="background-color:lightgrey;border-radius:5px;margin-top:5px;" id="comment_n_post_2">See Posts</button>
								</div>
								<?php
								$cmt_qr = $db -> prepare ("SELECT cmmt_sect.content as comment, cmmt_sect.cmmt_id as cid, cmmt_sect.date as datetime FROM comment_connect 
								INNER JOIN cmmt_sect
								ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
								INNER JOIN stud_bas
								ON stud_bas.stud_id = comment_connect.stud_id
								WHERE stud_bas.stud_id = :sid");
								$cmt_qr -> bindParam (":sid", $real_id);
								$cmt_qr -> execute();
								$num_cmt_qr = $cmt_qr -> rowCount();
								if($num_cmt_qr>0){
									while($comrow = $cmt_qr -> fetch(PDO::FETCH_ASSOC)){
										$comment = $comrow['comment'];
										$cid = $comrow['cid'];
										$datetime2 = $comrow['datetime'];
										$datetime2 = date("F d, Y");
										$comment = substr($comment, 0, 75);
										$get_nid = $db -> prepare ("SELECT cmmt_id,news_id FROM comment_connect WHERE cmmt_id=:cid");
										$get_nid -> bindParam (":cid", $cid);
										$get_nid -> execute();
										$res = $get_nid -> fetch();
										$comment_nid = $res['news_id'];
									?>
									<div style="display:block;margin-bottom:15px;margin-top:10px;">
									<a href="newsfeed.php?research=<?php echo $comment_nid; ?>" style="font-size:18px;"><?php displayText($comment);?></a>
									<span style="font-size:8px;display:block;"><?php echo $datetime2;?></span>
									</div>
									<?php
									}
								}
								else{
								?>
								
									<span style="font-size:24px;color:grey;">No Comment</span>
								
								<?php	
								}
								?>
								</div>
						</div>
				</div>