<!DOCTYPE html>
<?php
	session_start();
	include_once ("php/connection.php");
	include_once ("php/querydata.php");
	include_once ("php/function.php");
	include_once ("php/child_function.php");
	
	$check_user_login = checkLogIn();
	if(isset($_GET['research']))
	{
		$nid = $_GET['research'];
		$query = $db -> prepare ("SELECT stud_bas.fname as fname, stud_bas.lname as lname, stud_bas.stud_id as sid, newsfeed.title as title, newsfeed.description as description,newsfeed.media as media FROM post_connect
				LEFT JOIN stud_bas
				ON stud_bas.stud_id = post_connect.stud_id
				LEFT JOIN newsfeed
				ON newsfeed.news_id = post_connect.news_id
				WHERE newsfeed.news_id = :nid");
	$query -> bindParam (":nid", $nid);
	$query -> execute();
	$numrow = $query -> rowCount();
	$result = $query -> fetch();
	$title = $result['title'];
	$fname = $result['fname'];
	$lname = $result['lname'];
	$sid = $result['sid'];
	$media = $result['media'];
	$fullname = $fname." ".$lname;
	$description = $result['description'];
	
	$check_if_viewer_is_owner = $db -> prepare ("SELECT EXISTS (SELECT stud_id,news_id FROM post_connect WHERE stud_id=:sid AND news_id=:nid) as count");
	$check_if_viewer_is_owner -> bindParam (":sid", $getid);
	$check_if_viewer_is_owner -> bindParam (":nid", $nid);
	$check_if_viewer_is_owner -> execute();
	$res_check_if_viewer_is_owner = $check_if_viewer_is_owner -> fetch();
	$is_it_owner = $res_check_if_viewer_is_owner['count'];
	
	if($is_it_owner>0){
	$unseen_to_seen = $db -> prepare ("UPDATE comment_connect SET post_c_seen=1  WHERE news_id=:nid");
	$unseen_to_seen -> bindParam(":nid", $nid);
	$unseen_to_seen -> execute();
	}
	
	//Start for unseen reply
		$get_pid = $db -> prepare("SELECT cmmt_id as cid FROM comment_connect WHERE news_id=:nid AND stud_id=:sid");
		$get_pid -> bindParam (":nid", $nid);
		$get_pid -> bindParam (":sid", $getid);
		$get_pid -> execute();
		while($row_get_pid = $get_pid -> fetch(PDO::FETCH_ASSOC)){
				$unseen_get_pid = $row_get_pid['cid'];
				$reply_unseen_to_seen = $db -> prepare ("
							UPDATE comment_connect 
							INNER JOIN cmmt_sect
							ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
							SET comment_connect.comment_c_seen = 99
							WHERE cmmt_sect.parent_id=:pid
							");
				$reply_unseen_to_seen -> bindParam (":pid", $unseen_get_pid);
				$reply_unseen_to_seen -> execute();
			
				
		}
	
		
	//End for unseen reply
	}
	
	$score_query = $db -> prepare ("SELECT SUM(score) as newscore FROM upvote_post WHERE news_id=:nid");
	$score_query -> bindParam (":nid", $nid);
	$score_query -> execute();
	$score_numrow = $score_query -> rowCount();
	if($score_numrow>0){
	$score_result = $score_query -> fetch();
	$real_score = $score_result['newscore'];
		if($real_score==null){
			$real_score=0;
		}
	}
	else if($score_numrow==0){
		$real_score=0;
	}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Newsfeed | Thesis</title>
	
	<!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
	<link href="css/newsfeed.css" rel="stylesheet">
   	<link href="css/jquery-ui.css" rel="stylesheet">
 
	<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       

    <link rel="shortcut icon" href="image/thesis.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/newsfeed.js"></script>
	<script type="text/javascript">
	
		'use strict';
	//IMPORTANT PLEASE READ//
	//I'll clean the goddamn code, I know it's bloody dirty
	var id = "<?php echo $nid;?>";
	$(document).ready(function(){
		$(".vote").click(function(){
			var name = $(this).attr("name");		
			var score_place = $("#neutral_score");
			var int_score = parseInt($("#neutral_score").html());			
			var add_upvote = $(".upvote");
			var add_downvote = $(".downvote");
			var delete_up = $(".remove-upvote");
			var delete_down = $(".remove-downvote");
			var upvote_score = $("#upvote_score")
			var downvote_score = $("#downvote_score");
			var parent = $(this);
			if(name=='up')
			{
							
				$.ajax({
					type: 'POST',
					url: 'php/post_upvote.php',
					data: 
					{
						real_id: id,
						name: name,
						score: int_score,
					},
					cache: false,
				});
				$(score_place).hide();
				$(downvote_score).hide();
				$(upvote_score).css("display", "block");
				$(this).hide();
				$(add_downvote).show();
				$(delete_up).show();
				$(delete_down).hide();
			}
			else if(name=='down')
			{
				$.ajax({
					type: 'POST',
					url: 'php/post_upvote.php',
					data: 
					{
						real_id: id,
						name: name,
						score: int_score,
					},
					cache: false,
				});
				
				$(score_place).hide();
				$(upvote_score).hide();
				$(downvote_score).css("display", "block");
				$(this).hide();
				$(delete_down).show();
				$(delete_up).hide();
				$(add_upvote).show();
			}
			else if(name=='upvoted'){
				$.ajax({
					type: 'POST',
					url: 'php/post_upvote.php',
					data:
					{
						real_id: id,
						name: name,
						score: int_score,
					},
				});
				$(downvote_score).hide();
				$(upvote_score).hide();
				$(score_place).hide();
				$(score_place).css("display", "block");
				$(score_place).css("visibility", "visible");
				$(this).hide();
				$(add_upvote).show();
			}
			else if(name='downvoted'){
				$.ajax ({
					type: 'POST',
					url: 'php/post_upvote.php',
					data:
					{
						real_id: id,
						name: name,
						score: int_score,
					},
					cache: false,	
				});
				$(upvote_score).hide()
				$(downvote_score).hide();
				$(score_place).show();
				$(score_place).css("display", "block");
				$(this).hide();
				$(add_downvote).show();
			}
			return false;
		});
	});

	//Sav
	$(document).ready(function(){
		$(".save_post").click(function(e){
			var sid = $(this).attr("id");
				$.ajax({
					type: 'POST',
					url: 'php/save_post.php',
					data:
					{
						nid: sid,
					},
					cache: false,
					success: function(data){
						$(".save_post_" + sid).html(data);
					}
				});
		e.preventDefault();
		});
	});
	$(document).ready(function(){
		$(".delete_comment").click(function(e){
			e.preventDefault();
				$.ajax({
					type: 'POST',
					url: 'php/delete_user_content.php',
					data:{
						content_id : id,
						
					}
				});
		});
	});
	
	$(document).ready(function(){
		$("#file_media").on('change', function(){
			var commentForm = new FormData();
			var media_name = $(this).val();
			var media_id = document.getElementById("file_media");
			var remove_path_name = media_name.lastIndexOf("\\");
			var r_p_name = media_name.substr(remove_path_name + 1);
			var cut_name = r_p_name.lastIndexOf(".");
			var extension = r_p_name.substr(cut_name + 1).toUpperCase();
			var progress = $("#file_comment_progress");
			commentForm.append('media_id', media_id.files[0]);
				if(media_name==""){
					$("#file_media_name").hide();
					$(progress).text("");
					$("#submit_comment_button").prop("disabled", false);
				}
				else{
					if(
						extension == "JPG" ||
						extension == "PNG" ||
						extension == "JPEG"
						){
							///////////
					$.ajax({
					type: 'POST',
					contentType: false,
					processData: false,
					data: commentForm,
					xhr: function() {
							var myXhr = $.ajaxSettings.xhr();
							if (myXhr.upload) {
								myXhr.upload.addEventListener('progress', function(e) {
									if (e.lengthComputable) {
										var percentage = (e.loaded / e.total) * 100;
										$(progress).text(Math.floor(percentage) + '%');
										if(percentage==100){
											$("#submit_comment_button").prop("disabled", false);
										}
										else{
											$("#submit_comment_button").prop("disabled", true);
										}
									}
								} , false);
							}
							return myXhr;
						
						},
				});

							///////////
						$("#submit_comment_button").prop("disabled", false);
				
					}
					else{
						$("#submit_comment_button").prop("disabled", true);
					}
					$("#clear_post_file").css("display", "inline");
					//$("#clear_post_file").show();
					
					$("#file_media_name").html(r_p_name);
					
				}
		});
	});
	$(document).ready(function(){
		$("#clear_post_file").on('click', function(){
			$("#submit_comment_button").prop("disabled", false);
			$("#file_media_name").html("");
			$("#file_media").val("");
			$("#file_comment_progress").text("");
			$(this).hide();
		});
	});	
	
	//For highlighting of parent comment
	$(document).ready(function(){
		$('.to_parent').on('click', function(){
		var parent_id = $(this).attr('href').replace("#", "");
		var parent_class = $("#" + parent_id);
		parent_class.addClass("highlight");
			setTimeout(function(){
				parent_class.removeClass("highlight");
			},1200);
		});
	});	
	
	$(document).ready(function(){
		$("#modal_image").on('click', function(){
			$(this).css("display", "none");
		});
	});
</script>
<style>
	.highlight
	{
	  background: lightblue !important;
	}
	#test:hover{
		text-decoration:underline;
	}
	.comment-delete:hover{
		text-decoration:underline;	
		color: red;
	}
	
	.comment-not-delete:hover{
		text-decoration: underline;
		color: blue;
	}
	.modal-backdrop
{
    opacity:0.5 !important;
}
</style>
<!--Style for image-->

<style>
#modal_image{
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

#myImg-image:hover {opacity: 0.7;}

/* The Modal (background) */
.modal_div_container {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
#image-container {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}
/*
#caption-image {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}
*/
/* Add Animation */
#image-container{    
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

.close-image {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close-image:hover,
.close-image:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
    .modal-content-image {
        width: 100%;
    }
}
</style>
	<?php 
			$chk_query = $db -> prepare ("SELECT * FROM upvote_post WHERE stud_id=:sid AND news_id=:nid");
			$chk_query -> bindParam (":sid", $getid);
			$chk_query -> bindParam (":nid", $nid);
			$chk_query -> execute();
			$voterow = $chk_query -> rowCount();
			
			$chk_result = $chk_query -> fetch();
			$chk_nid = $chk_result['news_id'];
			$chk_score = $chk_result['score'];
			
			if($voterow==0){
				$upvote_score = $real_score + 1;
				$downvote_score = $real_score - 1;
				$real_score = $real_score;
			}
			else if($chk_score==1){
				$upvote_score = $real_score;
				$downvote_score = $real_score - 2;
				$real_score = $real_score - 1;
			}
			else if($chk_score==-1){
				$downvote_score = $real_score;
				$upvote_score = $real_score + 2;
				$real_score = $real_score + 1;
			}
			//For style, that's why I seperated
			?>
			<style>
			<?php
			if($voterow==0)
			{
			?>
			
				.upvote
				{
					display:inline;
				}
				.downvote
				{
					display:inline;
				}
				#neutral_score
				{
					display: block;
				}
			
	<?php	}
			else if($chk_score==1)
			{
			?>
				.downvote
				{
					display:inline;
				}
				.remove-upvote
				{
					display:inline;
				}
				#upvote_score
				{
					display: block;
				}
			<?php
			}
			else if($chk_score==-1)
			{
			?>
				.upvote
				{
					display:inline;
				}
				.remove-downvote
				{
					display:inline;
				}
				#downvote_score
				{
					display: block;
				}
			<?php 
			}
			?>
			a.user:hover
			{
				text-decoration:underline;
				color:blue;
			}
			
			.show-object
			{
				display: inline;
				visibility: visible;	
				
			}
			.hide-object
			{
				display: none;
				visibility: none;
			}
			</style>
			
		
</head><!--/head-->

<body class="homepage" style="background: linear-gradient(to bottom, #F0F8FF 0%, 	#F5F5F5 100%);">
	<?php
		include_once ("header.php");
	?>
	<!--Content-->
			<!--BANNER-->	<img src="image/banner.png" title="Laguna State Polytechnic University" alt="logo" style="margin-top:4px;" width="100%" height="100%" border="0"> <!--BANNER-->
		<!--Content-->
	<div class="container">
		<?php
		if(isset($_GET['research'])&&$numrow==1)
		{
		$nid = $_GET['research'];
		
		?>
		
		<div class="col-md-12"
		style="margin-top:50px;margin-bottom:25px; color:black; background: linear-gradient(to bottom, 	white 0%, white 100%); border-radius:6px;  border-radius:6px; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);" id="post_t1_<?php echo $nid;?>">
			<div class="col-md-1">
			
				<button style="margin-bottom:5px; margin-top:5px;" class="vote btn upvote-button upvote" id="<?php echo $nid ; ?>" name="up">
				<img src="image/newsfeed/arrow.png" title="Upvote" alt="logo" style="margin-top:4px;" width="15" height="15" border="0"></button>

				<button style="margin-bottom:5px;margin-top:5px;" class="vote btn btn-info remove-upvote-button remove-upvote" id="<?php echo $nid; ?>" name="upvoted">
				<img src="image/newsfeed/arrowg.png" title="Upvoted" alt="logo" style="margin-top:5px;" width="15" height="15" border="0"></button>

			
			
				<button style="margin-top:5px; margin-top:5px;" class="vote btn downvote-button downvote" id="<?php echo $nid; ?>" name="down">
				<img src="image/newsfeed/arrow1.png" title="Downvote" alt="logo" style="margin-bottom:0px;" width="15" height="15" border="0"></button>
				
				<button style="margin-top:5px; margin-top:5px;" class="vote btn btn-danger remove-downvote-button remove-downvote" id="<?php echo $nid; ?>" name="downvoted">
				<img src="image/newsfeed/arrowr.png" title="downvoted" alt="logo" width="15" height="15" border="0"></button>
				<span class="post-score upvote-score" id="upvote_score"><?php echo $upvote_score;?></span>
				<span class="post-score neutral-score" id="neutral_score"><?php echo $real_score; ?></span>
				<span class="post-score downvote-score" id="downvote_score"><?php echo $downvote_score;?></span>
			</div>
			<div class="col-md-8" style="margin-bottom:60px;">
				
				<span style="display:block;font-size:25px;margin-bottom:15px;margin-left:15px; margin-top:6px; background-color: white; color:  #0000FF; border: 2px solid #D3D3D3; padding:2px; border-radius: 4px;word-wrap:break-word;"" id="title">
					<?php echo htmlspecialchars($title); ?>
					<h3 style="color:black;">by <a class="user" href="myprofile?user=<?php echo $sid;?>"><b><?php echo $fullname;?></b></a></h3>
				</span>
				
				<span id="description"><?php echo $description; ?></span>	
				<span id='post_media'>
					<?php
						if($media==1){
							$get_post_media = $db -> prepare ("SELECT cmmt_media.cmid as cmid, cmmt_media.file_name as filename, cmmt_media.file_path as filepath, cmmt_media.file_type as filetype, cmmt_media.type as type 
							FROM cmmt_media_connect
							LEFT JOIN cmmt_media
							ON cmmt_media.cmid = cmmt_media_connect.cmid
							WHERE cmmt_media_connect.cid = :nid");
							$get_post_media -> bindParam (":nid", $nid);
							$get_post_media -> execute();
							$num_post_media = $get_post_media -> rowCount();
							if($num_post_media>0){
								$res_get_post_media = $get_post_media -> fetch();
								$post_img = $res_get_post_media['filename'];
								$post_path = $res_get_post_media['filepath'];
							?>
							<img src='php/<?php echo $post_path.$post_img;?>' style='display:block;width:600px;height:400px;margin-left:75px;margin-top:40px;margin-bottom:10px;'/>
							<?php
							}
							else{
								echo "";
							}
						}
					?>
				</span>
			</div>
			<ul style="margin-top:50px;position:absolute;bottom:0;margin-left:70px;">
				<?php
						$num_comments = $db -> prepare 
						("SELECT COUNT(content) as num_com FROM cmmt_sect
						RIGHT JOIN
						comment_connect
						ON 
						comment_connect.cmmt_id = cmmt_sect.cmmt_id
						WHERE comment_connect.news_id = :nid")	;
						$num_comments -> bindParam (":nid", $nid);
						$num_comments -> execute();
						$res_num_comments = $num_comments -> fetch();
						$num_com_row = $num_comments -> rowCount();
						$num_com = $res_num_comments['num_com'];
						if($num_com==0){
							$num_com = 0;
						}
					?>
				<li style='display:inline;margin-leftl'><?php echo $num_com;?> Comments</li>
				<li style="display:inline;margin-left:10px;" id="<?php echo $nid;?>" class="save_post save_post_<?php echo $nid;?>"><a href="#">
				<?php
					$check = $db -> prepare ("SELECT oid FROM stud_save WHERE oid=:nid AND stud_id=:sid");
					$check -> bindParam (":nid", $nid);
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
				<li style="display:inline;margin-left:10px;" class="report_post" id="<?php echo $nid;?>" name="Post" onclick="report_post(this,event);"><a href="#">Report</a></li>
				<?php
					if($sid==$getid){
				?>	
				<li style="display:inline;margin-left:10px;" class="edit_post" id="edit_p_<?php echo $nid;?>" name="edit_post" onclick="show_edit_post(this,event);"><a href="#">Edit</a></li>
				<li style="display:inline;margin-left:10px;" class="delete_post" id="delete_p_<?php echo $nid;?>" name="dlt_post" onclick="show_delete_confirmation(this);"><a href="#">Delete</a></li>
				
				
				<?php
					}
				?>
			</ul>
		</div>

		<div class="col-md-10" style="margin-bottom:50px;">
			<span style="display:block;font-size:25px;margin-bottom:20px;margin-top:20px;">Comments</span>
			<div style='display:block;' id='sort_c_<?php echo $nid;?>'>
			<label>Sort Comments by: </label>
			<select id='sort_comments' onchange='sort_comments(this,this.value);'>
				<?php
					if(!isset($_GET['sort'])){
						$order = 'scored DESC';
				?>
					<option value='top'>Highest</option>
					<option value='new'>Newest</option>
					<option value='old'>Oldest</option>
				<?php
					}
					else if(isset($_GET['sort'])){
						$what_order = $_GET['sort'];
						if($what_order=='old'){
							$order = 'cmmt_sect.date ASC';
				?>
					<option value='old'>Oldest</option>
					<option value='top'>Highest</option>
					<option value='new'>Newest</option>
				<?php
						}
						else if($what_order=='new'){
							$order = 'cmmt_sect.date DESC';
				?>	
					<option value='new'>Newest</option>
					<option value='old'>Oldest</option>
					<option value='top'>Highest</option>
				<?php
						}
						else if($what_order=='top'){
							$order = "scored DESC";
				?>
					<option value='top'>Highest</option>
					<option value='new'>Newest</option>
					<option value='old'>Oldest</option>
				<?php
						}
					}
				?>

			</select>
			</div>
		<div class="col-md-7">
			<form action="" method="POST" onsubmit="return submit_comment();" enctype='multipart/form-data'>
			<textarea cols="70" rows="7" style="display:block;margin: 20px 0 20px 0;resize:vertical;" id="parent_comment" name="comment" placeholder="Input Comment Here"></textarea>
			<input type="hidden" name="nid" id="nid" value="<?php echo $nid;?>">
			<label for='file_media' style='cursor:pointer;'>Add Image<img src='image/extra/media.png' style='height:25px;width:25px;'/></label>
			<input type='file' name='file' id='file_media' accept='image/jpeg, image/jpg, image/png' style='display:none;'>
			<span style='display:block;' id='file_comment_progress'></span>
			<div>
				<span id='file_media_name'></span>&nbsp;
				<span id='clear_post_file'style='color:red;cursor:pointer;font-size:20px;display:none;'>X</span> 
			</div>
			<input type="submit" name="comment_button" id="submit_comment_button" style="float:right;" class="button btn btn-info" value="Submit Comment">
			</form>
			
		</div>
		<!--End of Post-->
		<!--Start of Comments-->
		
		<div class="col-md-12" id="comment_container" style='width:1000px;'>
		<?php
			
			$comment_query = $db -> prepare("SELECT stud_bas.fname as fname, stud_bas.lname as lname, stud_bas.stud_id as sid, cmmt_sect.cmmt_id as cid, cmmt_sect.content as comment, cmmt_sect.parent_id as pid, cmmt_sect.stat as stat, cmmt_sect.type as type, cmmt_sect.date as date_n_time, cmmt_sect.media as media ,COALESCE(SUM(upvote_comment.score),0) as scored FROM comment_connect 
			LEFT JOIN stud_bas
			ON stud_bas.stud_id = comment_connect.stud_id
			LEFT JOIN cmmt_sect
			ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
			LEFT JOIN newsfeed
			ON newsfeed.news_id = comment_connect.news_id
			LEFT JOIN upvote_comment
			ON upvote_comment.cmmt_id = cmmt_sect.cmmt_id
			WHERE cmmt_sect.parent_id = :nid GROUP BY cmmt_sect.cmmt_id ORDER BY $order");
			$comment_query -> bindParam (":nid", $nid);
			//$comment_query -> bindParam (":order", $order);
			$comment_query -> execute();
			while ($row = $comment_query -> fetch(PDO::FETCH_ASSOC)){
				$comment = $row['comment'];
				$cid = $row['cid'];
				$c_stat = $row['stat'];	
				$c_sid = $row['sid'];
				$c_type = $row['type'];
				$c_datetime = $row['date_n_time'];
				$c_fname = $row['fname'];
				$c_lname = $row['lname'];
				$c_media = $row['media'];
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
			<?php
				if($c_type!='Deleted'){
			?>
			<div style="background-color:#DCDCDC;margin-bottom:10px;padding-bottom:0.5px;padding-top:5px;margin-top:15px; border-radius:5px" class="whole_comment_div_<?php echo $cid;?>" id="pid_<?php echo $cid;?>">
				
			<div class="col-md-12" style='margin-left:-14px;'>
			<div class="col-md-1" style='margin-right:-20px;'>
			
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
						$get_picture = $db -> prepare ("SELECT cmmt_media.file_path as path, cmmt_media.file_name as name, cmmt_media.type as type FROM cmmt_media_connect LEFT JOIN cmmt_media ON cmmt_media.cmid =  cmmt_media_connect.cmid WHERE cmmt_media_connect.cid = :cid");
						$get_picture -> bindParam (":cid", $cid);
						$get_picture -> execute();
						$c_media_numrow = $get_picture -> rowCount();
						$res_get_picture = $get_picture -> fetch();
						$path = $res_get_picture['path'];
						$name = $res_get_picture['name'];
						$media_type = $res_get_picture['type'];
						if($c_media_numrow>0){
							if($media_type=='image'){
					?>
							
						<img src='php/<?php echo $path.$name;?>' style='height;175px;width:175px;display:block;margin-top:10px;cursor:pointer;' id='img_<?php echo $cid;?>' onclick='enlarge_image(this,event);'>
					
					<?php
								}
							}
					?>
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
				<li style="display:inline;margin-left:10px;" class="reply_comment comment_id_<?php echo $cid;?>" id="cmmt_id_<?php echo $cid;?>" onclick="reply_comment(this,event);"><a href="#">Reply</a></li>
				<?php
					if($c_sid==$getid){
				?>
				<li style="display:inline;margin-left:10px;" class="edit_comment" id="edt_cmmt_id_<?php echo $cid;?>" onclick="edit_comment(this,event);"><a href="#">Edit</a></li>
				<li style="display:inline;margin-left:10px;" class="delete_comment"onclick="show_option_delete_comment(this,event);" id="dlt_<?php echo $cid;?>"><span style="color:black;"><a href="#">Delete</a></span></li>
				
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
				else{
				?>
			<div style="background-color:#A9A9A9;margin-bottom:10px;padding-bottom:0.5px;padding-top:5px;margin-top:15px;border-radius:5px;margin-right:10px;" class="whole_comment_div_<?php echo $cid;?>" id="pid_<?php echo $cid;?>">
			<?php
			
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
			
				<?php
				}
				?>
			<!--End of Div-->
			<!--Pia-->
		<?php
			}
		?>
			</div>

			<!--End of Parent Comment-->
			<div class="col-md-12" id="reply_comment_div" style="display:none;">
				<form action="#" method="POST" class="userreply" id="this_reply" >
				<input type="hidden" class="parent_comment_id" value="" name="parent_id" readonly>
				<input type='hidden' class='post_news_id' value='<?php echo $nid;?>' name='news_id'>
				<textarea cols="50" rows="5" style="resize:vertical;" name="user-comment" class="reply_comment_content"></textarea>
				<span style='display:block;'>
				<a href='#'>
				<label for="file-upload" id='reply_media_logo' class="custom-file-upload" style='cursor:pointer;' >
				For Image
				</label>
				</a>
				<span style='display:block' class='reply_media_progress'></span>
				
				<input type='file' id='reply_media' name='media' class='media' style='display:none;' onchange='submitMedia(this,event);' accept="image/*">
				</span>
				<span class='uploaded_media' style='display:block;'></span>
				
				<span style='display:block;'>
				<button class="btn btn-info submit_reply" style="display:inline;margin-top:5px;margin-left:240px;" onclick="submitUserReply(this,event);">Submit Reply</button>
				<button class="btn btn-info cancel_reply" style="display:inline;margin-top:5px;" onclick="cancel_reply(this,event);">Cancel</button>
				</span>
				</form>
			</div>
		<!--End of Body Div v-->
		
		</div>
		<?php

		}
		else{
			include_once("404.php");
		?>
		</div>
		<?php
		}
		?>
		
		
	</div>
	<!---->

	<!--Report-->
	<div title="Report Why?" id="report_reason" style="visibility:hidden;display:none;">
		<form method="POST" class="form-inline" id="report_form">
			<span  style="display:block;"><input type="radio" name="report" value="Violent">Contains Violent content</span>
			<span  style="display:block;"><input type="radio" name="report" value="Sexual">Has sexually offensive content</span>
			<span  style="display:block;"><input type="radio" name="report" value="Spam">Is a spam</span>
			<span  style="display:block;"><input type="radio" name="report" value="Offensive">Has offensive language (Racist, Sexist, etc.)</span>
			<span  style="display:block;"><input type="radio" name="report" value="Other">Other</span>
			<input type="hidden" value="" id="report_type" readonly>
			<input type="hidden" value="" name="nid" id="report_id" readonly>
			<input type="text" class="form-control" name="content" required style="margin: 8px 0 10px 0;" id="report_content">
			<input type="submit" name="submit_report" value="Report" class="btn btn-danger form-control submit_report">
		</form>
	</div>
	<div id='edit_post_form'  style='display:none'>
	<form action='#' method='POST'>
		<input type='hidden' value='' id='edit_p_id'>
		<textarea cols='50' rows='5' style='resize:vertical;' class='form-control' id='edit_p_desc'></textarea><label for='edit_post_media' style='display:block;cursor:pointer;'> Edit Picture<img src='image/extra/media.png' style='height:30px;width:30px;'/></label><span id='post_media_progress'></span><div style='display:block;'><span id='show_edit_media_name'></span>&nbsp;<span style='color:red;font-weight:bold;font-size:12px;cursor:pointer;display:none;' id='clear_edit_media_post' onclick='clear_edit_media_post(this,event);'>X</span></div><input type='file' id='edit_post_media' onchange='change_media_post(this,event); 'style='display:none;' accept='image/jpg, image/png, image/jpeg'><div style='display:block;margin-top:20px;'><button class='btn btn-info' style='margin-left:10px;margin-right:5px;' onclick='edit_post(this,event)' id='save_edit_post'>Save Edit</button><button class='btn btn-info' onclick='cancel_edit_p(this,event)'>Cancel</button>
		</div>
	</form>
	</div>
	<div title="Delete Your Post?" id="delete_post_div" style="visibility: hidden;display:none;">
		<form action="" method="POST" class="form-inline">
		<input type="hidden" id="delete_post_id" readonly value="" name="nid">
		<input type="submit" id="delete_post_btn" class="form-control btn btn-warning" value="Yes" style="margin-bottom:10px;">
		<input type="submit" id="not_delete_post" class="form-control btn btn-success" value="No" onclick="not_delete(event);">
		</form>
	</div>
	
	<div id="edit_comment_div" class="edit-user-comment" style="visibility:hidden;display:none;margin-top:20px;">
		<form method="POST" action="#" class="form-inline" style="width:400px;margin-left:14px;" id="edit_c_form" onsubmit="">
			<input class="c_id" type="hidden" value="" name="c_id" readonly>
			<textarea class="new_comment" name="n_cm" cols="50" rows="7" style="display:block;resize:vertical;margin-bottom:14px;"></textarea>
			
				<label for='' id='' class='edit_comment_media_logo' style='display:block;cursor:pointer;'>Change Picture<img src='image/extra/media.png' style='width:30px;height:30px;'></label>
			<div style=''>
				<span class='show_edit_media_name'></span>
				<span class='clear_edit_media' style='color:red;font-weight:bold;font-size:14px;display:none;cursor:pointer;' onclick='clear_edit_media(this,event);'>X</span>
			</div>
			
			<input type='file' id='' class='edit_comment_media' style='display:none;' onchange='edit_media(this,event);' accept='image/jpeg, image/jpg, image/png'>
			<span style='display:block;'class='edit_media_progress'></span>
			<input type="submit" class="btn btn-info save-edit-c" name="save-edit-c" value="Save Edit" onclick="save_edit_c(this,event);">
			
			<button class="btn btn-info cancel-edit-c" onclick="cancel_edit(this,event);" id="cancel_edit_comment">Cancel</button>
		</form>
	</div>
	
	<div id='modal_image' class='modal_div_container' style='display:none;'>
		<span class="close-image" id='close-image-div' onclick='close_img_div(this,event);'>&times;</span>		
		<img id='image-container' src='' style=''/>
	</div>
	<!--//Report-->
  <!--/#bottom-->

    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6" style="color:white;">
                    &copy; 2017 <a target="_blank" href="#" title="ARVIN TALABIS IS AWESOME">Suicide Squad</a>. All Rights Reserved.
                </div>
         
            </div>
        </div>
    </footer><!--/#footer-->
	<input type="hidden" id="stud_id" value="<?php echo $getid;?>">
	<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/wow.min.js"></script>
</body>
</html>