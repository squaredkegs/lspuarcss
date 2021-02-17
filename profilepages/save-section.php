<?php
		if(file_exists("php/connection.php")){
		include("php/connection.php");
		include("php/querydata.php");
		}
		else if(file_exists("../php/connection.php")){
		include("../php/connection.php");
		include("../php/querydata.php");
		include("../php/function.php");	
		}

?>

<head>

	<script>
			$(document).ready(function(){
				$(".unsave_post").click(function(){
				var nid = $(this).attr("id");
					$.ajax({
						type: 'POST',
						url: 'php/save_post.php',
						data:
						{
							nid: nid,
						},
						cache: false,
						success: function(data){
						$('.unsave_' + nid).html(data);
						}
					});
				});
			});
			
			$(document).ready(function(){
				$(".unsave_post").click(function(e){
					e.preventDefault();
				});
			});
		
		$(document).ready(function(){
			$('.unsave_comment').click(function(){
				var cid = $(this).attr("id");
					$.ajax({
						type: 'POST',
						url: 'php/save_comment.php',
						data:
						{
							cid: cid,
						},
						cache: false,
						success: function(data){
							$('.unsave_' + cid).html(data);
						}
					});
			});
		});
								</script>
</head>
	<?php
		if(isset($_GET['user'])){
		$check_if_viewer_is_owner = $_GET['user'];
			if($check_if_viewer_is_owner==$getid){
		
	?>
					<div class="col-md-9 main-information" id="main-info" style="background-color:white; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19); border-radius:10px;"> <center><h1 style="color:#1E90FF; text-shadow: 1px 1px 2px black;  font-family:arial; ">Saved Post</h1></center>
						<?php
							$query = $db -> prepare ("SELECT stud_save.stud_id as sid, stud_save.oid as oid, stud_save.type as type, stud_save.date as date, newsfeed.title as title, cmmt_id as cid, cmmt_sect.content as comment FROM stud_save
							LEFT JOIN newsfeed
							ON newsfeed.news_id = stud_save.oid
							LEFT JOIN cmmt_sect
							ON cmmt_id = stud_save.oid
							WHERE stud_save.stud_id = :sid
							ORDER BY stud_save.date DESC
							");
							$query -> bindParam (":sid", $getid);
							$query -> execute();
						?>
						
						<?php
							while($row = $query -> fetch(PDO::FETCH_ASSOC)){
								$oid = $row['oid'];
								$title = $row['title'];
								$type = $row['type'];
								$date = $row['date'];
								$comment = $row['comment'];
								$date = date("F j, Y H:i (A)", strtotime($date));
						?>	
							
						
							<ul style="list-style-type:none;">	
								<?php
								if($type == 'Post'){
								?>
								<li><a style="font-size:18px;" href="newsfeed?research=<?php echo $oid;?>"><?php 
								displayText($title);?>
							
								<a style="font-size:14px;margin-left:10px;color:red;" class="unsave_post unsave_<?php echo $oid;?>" id="<?php echo $oid;?>" href="#">Unsave</a></span> 
								<span style="display:block;font-size:15px; color:#1E90FF;">
								<?php echo $date;?><hr>
								</span>
								</li>
								<?php
								}
								else if($type=='Comment')
								{
								?>								<li><a style="font-size:18px;" href="newsfeed?research=<?php echo $oid;?>&commentfind=<?php echo $nid;?>"><?php 
								displayText($comment);?>
							
								<a style="font-size:14px;margin-left:10px; " class="unsave_comment unsave_<?php echo $oid;?>" id="<?php echo $oid;?>" href="#">Unsave</a></span> 
								<span style="display:block;font-size:15px;">
								<?php echo $date;?>
								</span>
								</li>									
								<?php
								}
								?>
							</ul>
						<?php
						
						
							}
						?>
					</div>
				
		<?php
			}
			else{
				include_once ("../403.php");
			}
		}
		else{
			include_once ("../404.php");
		}

		?>
				
				