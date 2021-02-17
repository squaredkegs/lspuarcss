<!DOCTYPE html>
<!--Yes the code is bullshit I'll try to fix this motherfucker when I have time-->
			
<?php
	include_once ("php/connection.php");
	include_once ("php/querydata.php");
	include_once ("php/function.php");

				$record_limit = 10;
				if(!isset($_GET['dept'])){
				$query = $db -> prepare ("SELECT * FROM newsfeed ORDER BY date_and_time DESC");
				$sec_query = $db -> prepare ("SELECT * FROM newsfeed ORDER BY date_and_time DESC LIMIT :offset, :record_limit");
				}
				else if(isset($_GET['dept']) && !isset($_GET['course'])){
				$department = $_GET['dept'];	
				$query = $db -> prepare ("SELECT * FROM newsfeed WHERE department=:dept");
				$sec_query = $db -> prepare ("SELECT * FROM newsfeed WHERE department=:dept ORDER BY date_and_time DESC LIMIT :offset, :record_limit");
				$query -> bindParam (":dept", $department);
				$sec_query -> bindParam (":dept", $department);
				}
				else if(isset($_GET['dept']) && isset($_GET['course'])){
				$department = $_GET['dept'];	
				$course = $_GET['course'];
				$query = $db -> prepare ("SELECT * FROM newsfeed WHERE department=:dept AND course=:course");
				$sec_query = $db -> prepare ("SELECT * FROM newsfeed WHERE department=:dept AND course=:course ORDER BY date_and_time DESC LIMIT :offset, :record_limit");
				$query -> bindParam (":dept", $department);
				$query -> bindParam (":course", $course);
				$sec_query -> bindParam (":dept", $department);
				$sec_query -> bindParam (":course", $course);
					
				}
				$query -> execute ();
				$numrow = $query -> rowCount();
				$limitrow = floor($numrow/$record_limit) + 2;
				$upperlimit = $limitrow - 1;
				if(isset($_GET['page']))
				{
					$page = $_GET['page'];
					if($page<=0)
					{
						echo 	"<script>
								window.location.href='?page=1';
								</script>";
					}
					else if($page>=$limitrow)
					{
						echo 	
								"<script>
								window.location.href='?page=$upperlimit';
								</script>";
					}
					else if($page!=1)
					{
						$page = $_GET['page'] + 1;
						$offset = ($record_limit * $page) - ($record_limit * 2);
					}
					else
					{
						$page = 1;
						$offset = 0;
					}
				}
				else
				{
					$page = 1;
					$offset = 0;
				}	
				$currentpage = $page - 1;
				$blank_record = $numrow - ($currentpage * $record_limit);
									//100 - 2 * 20
									//80
		$sec_query -> bindValue (":offset", (int) trim ($offset), PDO::PARAM_INT);
		$sec_query -> bindValue (":record_limit", (int) trim($record_limit), PDO::PARAM_INT);
						
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home | ARCSS</title>
	
	<!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
	<link href="css/newsfeed.css" rel="stylesheet">
    
	<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="image/thesis.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/jquery-click-outside.js"></script>
	
<script type="text/javascript">
	
	'use strict';
	$(document).ready(function(){
		$(".vote").click(function(){
			var id = $(this).attr("id");
			var name = $(this).attr("name");		
			var score_id = $("#score" + id);
			var int_score = parseInt($("#score" + id).html());
			var add_upvote = $(".upvote" + id);
			var add_downvote = $(".downvote" + id);
			var delete_up = $(".remove-upvote" + id);
			var delete_down = $(".remove-downvote" + id);
			var parent = $(this);
			var upvote_score = $("#upvoted_score" + id);
			var downvote_score = $("#downvoted_score" + id);
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
				$(score_id).hide();
				$(downvote_score).hide();
				$(upvote_score).css("display", "block");
				$(add_downvote).show();
				$(this).hide();
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
				$(score_id).hide();
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
					cache: false,
				});
				$(upvote_score).hide();
				$(downvote_score).hide();
				$(score_id).css("display", "block");
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
				$(upvote_score).hide();
				$(downvote_score).hide();
				$(score_id).css("display", "block");
				$(this).hide();
				$(add_downvote).show();
			}
			return false;
		});
	});
	
	$(document).ready(function(){
		$(".save_post").click(function(){
			var id = $(this).attr("id");
				$.ajax({
					type: 'POST',
					url: 'php/save_post.php',
					data:{
						nid: id,
					},
					cache: false,
					success: function(data){
						$(".save_post_" + id).html(data);
					}
				});
		});
	});
	$(document).ready(function(){
		$('.save_post').click(function(e){
			    e.preventDefault();
		});
	});
	
	$(document).ready(function(){
		$(".report_post").click(function(e){
			var id = $(this).attr("id");
			$("#report_reason").dialog({
				resizable: false,
				height: 300,
				width: 400,
				modal: true,
				open: function(event, ui){
					$('.ui-widget-overlay').bind('click', function(){
						$("#report_reason").dialog('close');
					});
				}
			});
			$("#report_id").val(id);	
			$("#report_reason").css("visibility", "visible");
		   e.preventDefault();
		});
					
				$('.submit_report_post').click(function(e){
					var nid = $("#report_id").val();
					var content = $("#report_content").val();
					var type = "Post";
					if ($('input[name="report"]:checked').length>0 && (content!=null &&
					content!="")){
					var complaint = document.querySelector('input[name="report"]:checked').value;
						$.ajax({
							type: 'POST',
							url: 'php/report_post.php',
							data:
							{
								type: type,
								nid: nid,
								reason: complaint,
								content: content,
							},
							cache: false,
							success: function(data){
							alert(data);
					
							$("#report_content").val("");
							$("input[name='report']").prop('checked', false);
							$("#report_reason").dialog('close');
							}
						});	
					}
					else{
						alert('Fill all of the information!');
					}
				e.preventDefault();	
				});
	});
	


</script>		
	<?php 
		$sec_query -> execute();
		while($result = $sec_query -> fetch(PDO::FETCH_ASSOC))
		{
			$real_newsid = $result['news_id'];
				$chk_query = $db -> prepare ("SELECT * FROM upvote_post WHERE stud_id=:sid AND news_id=:nid");
				$chk_query -> bindParam (":sid", $getid);
				$chk_query -> bindParam (":nid", $real_newsid);
				$chk_query -> execute();
				$voterow = $chk_query -> rowCount();
				$chk_result = $chk_query -> fetch();
				$chk_nid = $chk_result['news_id'];
				$chk_score = $chk_result['score'];
	?>
			<style>
			
			
			<?php
			if($voterow==0)
			{
			?>
				.upvote<?php echo $real_newsid;?>
				{
					display:inline;
				}
				.downvote<?php echo $real_newsid;?>
				{
					display:inline;
				}
				#score<?php echo $real_newsid;?>
				{
					display:block;
				}
	<?php
			}
			else if($chk_score==1)
			{
			?>
				.downvote<?php echo $real_newsid;?>
				{
					display:inline;
				}
				.remove-upvote<?php echo $real_newsid;?>
				{
					display:inline;
				}
				#upvoted_score<?php echo $real_newsid;?>
				{
					display: block;
				}
				
				

			<?php
			}
			else if($chk_score==-1)
			{
			?>
				.upvote<?php echo $real_newsid;?>
				{
					display:inline;
				}
				.remove-downvote<?php echo $real_newsid;?>
				{
					display:inline;
				}				
				#downvoted_score<?php echo $real_newsid;?>
				{
					display: block;
				}
			<?php 
			}
			?>
			</style>
			<?php
		}
	?>
</head>
</script>
<body class="homepage" style="background: linear-gradient(to bottom, #F0F8FF 0%, #F5F5F5 100%);">

	<?php
		include_once ("header.php");
	
	?>
    
	<!---->
	<?php 
	if(!isset($_SESSION['log_user']))
	{
		?>
	   <section id="main-slider" class="no-margin">
        <div class="carousel slide">
            <ol class="carousel-indicators">
                <li data-target="#main-slider" data-slide-to="0" class="active"></li>
                <li data-target="#main-slider" data-slide-to="1"></li>
                <li data-target="#main-slider" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">

                <div class="item active" style="background-image: url(image/LSPU/LSPU2.JPG)">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1" style=" text-shadow: 2px 2px 4px #000000;">LSPU-CCS Thesis Social Media</h1>
                                    <h2 class="animation animated-item-2" style=" text-shadow: 2px 2px 4px #000000;">Meet new friends from other LSPU Campuses and help each other.</h2>
                                   
                                </div>
                            </div>

                            <div class="col-sm-6 hidden-xs animation animated-item-4">
                                <div class="slider-img">
                                <!--    <img src="image/LSPU/Kid.PNG" class="img-responsive"> -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!--/.item-->

                <div class="item" style="background-image: url(image/LSPU/LSPU1.JPG)">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1" style=" text-shadow: 2px 2px 4px #000000;"><br>College of Computer Studies</h1>
                                    <h2 class="animation animated-item-2" style=" text-shadow: 2px 2px 4px #000000;">Thesis I.T for you to learn more!</h2>
                                 
                                </div>
                            </div>

                            <div class="col-sm-6 hidden-xs animation animated-item-4">
                                <div class="slider-img">
                                    <img src="image/LSPU/CCS2.PNG" width="400" height="400" class="img-responsive">
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!--/.item-->

                <div class="item" style="background-image: url(image/LSPU/comm.jpg)">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1" style=" text-shadow: 2px 2px 4px #000000;">LSPU Campuses</h1>
                                    <h2 class="animation animated-item-2" style=" text-shadow: 2px 2px 4px #000000;">Share your ideas with other CCS students</h2>
                                    
                                </div>
                            </div>
                            <div class="col-sm-6 hidden-xs animation animated-item-4">
                                <div class="slider-img">
                                    <img src="image/LSPU/ideas.PNG" class="img-responsive">
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.item-->
            </div><!--/.carousel-inner-->
        </div><!--/.carousel-->
        <a class="prev hidden-xs" href="#main-slider" data-slide="prev">
            <i class="fa fa-chevron-left"></i>
        </a>
        <a class="next hidden-xs" href="#main-slider" data-slide="next">
            <i class="fa fa-chevron-right"></i>
        </a>
    </section>

	<?php
	}
	else if(isset($_SESSION['log_user']))
	{
	?>
		<!--Content-->
		<!--BANNER-->	<img src="image/banner.png" title="Laguna State Polytechnic University" alt="logo" style="margin-top:4px;" width="100%" height="100%" border="0"> <!--BANNER-->
		<div class="container" style="margin-top:50px;">
			<?php
			if($numrow>2){
			?>
			<div class="col-md-10 col-md-offset-1"
			style="width:60%;margin-bottom:25px;">
			<?php
			}
			else{
			?>
			<div class="col-md-10 col-md-offset-1"
			style="height:650px;width:60%;margin-bottom:25px;">
			<!--700px date yung width-->
			
			<?php
			}
				
				$sec_query -> execute();
				if($numrow>0 or ($numrow==0 && !isset($_GET['dept']))){
				while($row = $sec_query -> fetch(PDO::FETCH_ASSOC))
				{
					$datetime = $row['date_and_time'];
					$datetime = date('F d, Y H:i', strtotime ($datetime));
					$title = $row['title'];
					$nid = $row['news_id'];
					$description = $row['description'];
					
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
						$real_score='0';
					}
					$check_if_user_voted = $db-> prepare ("SELECT * FROM upvote_post WHERE stud_id=:sid AND news_id=:nid");
					$check_if_user_voted -> bindParam (":sid", $getid);
					$check_if_user_voted -> bindParam (":nid", $nid);
					$check_if_user_voted -> execute();
					$check_row_if_user_vote = $check_if_user_voted -> rowCount();
					if($check_row_if_user_vote>0){
						$result_check_if_user_vote = $check_if_user_voted -> fetch();
						$upvote_or_downvote = $result_check_if_user_vote ['score'];
						if($upvote_or_downvote==1){
							$upvoted_score = $real_score;
							$downvoted_score = $real_score - 2;
							$real_score = $real_score - 1;
						}
						else if($upvote_or_downvote==-1){
							$upvoted_score = $real_score + 2;
							$downvoted_score = $real_score;
							$real_score = $real_score + 1;
						}
					}
					else{
							$upvoted_score = $real_score + 1;
							$downvoted_score = $real_score - 1;
					}
					$stud_quer = $db -> prepare ("
								SELECT stud_bas.fname as fname, 				stud_bas.stud_id as sid, stud_bas.lname as lname 
								FROM post_connect
								INNER JOIN stud_bas
								ON
								stud_bas.stud_id = post_connect.stud_id
								INNER JOIN newsfeed 
								ON
								newsfeed.news_id = post_connect.news_id
								WHERE post_connect.news_id=:nid
										");
					$stud_quer -> bindParam (":nid", $nid);
					$stud_quer -> execute();
					$res_stud_quer = $stud_quer -> fetch();
					$post_fname = $res_stud_quer['fname'];
					$post_lname = $res_stud_quer['lname'];
					$post_sid = $res_stud_quer['sid'];
			?>
	
		<div class="col-md-12" style="margin-bottom:25px; background-color:white; border-radius:8px; padding:4px; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);" id="newsfeed">
			<div class="col-md-1">		
			
			<button style="margin-bottom:5px; margin-top:5px;" class="vote btn upvote-button upvote<?php echo $nid;?>" id="<?php echo $nid ; ?>" name="up">
			<img src="image/newsfeed/arrow.png" title="Upvote" alt="logo" style="margin-top:4px;" width="15" height="15" border="0"></button>

			<button style="margin-bottom:5px;margin-top:5px;" class="vote btn btn-info remove-upvote-button remove-upvote<?php echo $nid ; ?>" id="<?php echo $nid; ?>" name="upvoted">
			<img src="image/newsfeed/arrowg.png" title="Upvoted" alt="logo" style="margin-top:5px;" width="15" height="15" border="0"></button>

		<hr style="margin-left:10px;">
		
			<button style="margin-top:5px; margin-top:1px;" class="vote btn downvote-button downvote<?php echo $nid;?>" id="<?php echo $nid; ?>" name="down">
			<img src="image/newsfeed/arrow1.png" title="Downvote" alt="logo" style="margin-bottom:0px;" width="15" height="15" border="0"></button>
			<button style="margin-top:5px; margin-top:1px;" class="vote btn btn-danger remove-downvote-button remove-downvote<?php echo $nid; ?>" id="<?php echo $nid; ?>" name="downvoted">
			<img src="image/newsfeed/arrowr.png" title="downvoted" alt="logo" width="15" height="15" border="0"></button>
			<span class="post-score upvote-score" id="upvoted_score<?php echo $nid;?>"><?php echo $upvoted_score;?></span>
			<span class="post-score score neutral-score" id="score<?php echo $nid;?>"><?php echo $real_score; ?></span>
			<span class="post-score downvote-score" id="downvoted_score<?php echo $nid;?>"><?php echo $downvoted_score;?></span>
			
			<!--Put current score here-->
			</div>
			<div class="col-md-8">
				<span class="title" style="padding-right:10px; font-family:verdana;font-size:20px;display:block;margin-bottom:8px;  background-color: white; color: black; border: 2px solid #D3D3D3; padding:2px; border-radius: 4px;word-wrap:break-word;">
					<a href="newsfeed?research=<?php echo $nid;?>" style="color:#008000;">
					<?php limit_length($title, 70); ?>
					</a>
				</span>
				<span>
					<!--
					<a href="newsfeed.php?research=<?php echo $nid;?>">
						<?php limit_length($description, 100); ?>
					</a>
					-->
			<span style="color:black;">by: <b><a style='color:black;' href='myprofile?user=<?php echo $post_sid;?>'><?php echo $post_fname." ".$post_lname;?></b></a></span>
			<span style="display:block;"><?php echo $datetime;?></span>
				</span>
			</div>
				
			<h3 style="color:black;"><?php echo "Testing Na2";?></h3>
				<ul style="position:absolute;bottom:0;left:0;margin-left:45px;">
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
					<li style="display:inline;">
						<a href="newsfeed?research=<?php echo $nid;?>"><?php echo $num_com;?> Comments</a></li>
					<li style="display:inline;margin-left:10px;" id="<?php echo $nid;?>" class="save_post save_post_<?php echo $nid;?>"><a href="#">
					<?php
					$check = $db -> prepare ("SELECT oid FROM stud_save WHERE oid=:nid");
					$check -> bindParam (":nid", $nid);
					$check -> execute();
					$save_numrow = $check -> rowCount();
						if($save_numrow==0){
							echo "Save";
						}
						else if($save_numrow==1){
							echo "Unsave";
						}
					?>
					
					</a></li>
					<li style="display:inline;margin-left:10px;" class="report_post" id="<?php echo $nid;?>"><a href="#">Report</a></li>
				</ul>
		</div>
		
		
	

	<?php } ?>
		<!--Pagination-->
		<?php
			if($page==1 && $numrow<$record_limit){
				
			}
			else if($page==1)
			{
				$nextpage = $page + 1;
				if(!isset($_GET['dept'])){
		?>
				<a href="?page=<?php echo $nextpage;?>" style="border-radius:6px; border-color:black; border-style:solid;background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;" >Next Pages</a>
		<?php
				}
				else if(isset($_GET['dept']) && !isset($_GET['course'])){
		?>
				<a href="?page=<?php echo $nextpage."&dept=".$department;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;" >Next Pages</a>
		
		<?php
				}
				else if(isset($_GET['dept']) && isset($_GET['course'])){
		?>			
				<a href="?page=<?php echo $nextpage."&dept=".$department."&course=".$course;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;" >Next Pages</a>
	
		<?php	}
			}
			else if($page>0 && $blank_record>0)
			{

				$last = $page - 2;
				$nextpages = array($page + 1, $page +2, $page + 3);
				if(!isset($_GET['dept'])){
		?>		
				<a href="?page=<?php echo $last;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Previous Page </a>|
				<a href="?page=<?php echo $page;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Next Page</a>
		<?php
				}
				else if(isset($_GET['dept']) && !isset($_GET['course'])){
		?>
				<a href="?page=<?php echo $last."&dept=".$department;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Previous Page </a>|
				<a href="?page=<?php echo $page."&dept=".$department;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Next Page</a>
		
		<?php
				}
				else if(isset($_GET['dept']) && isset($_GET['course'])){
		?>
		
		
				<a href="?page=<?php echo $last."&dept=".$department."&course=".$course;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Previous Page </a>|
				<a href="?page=<?php echo $page."&dept=".$department."&course=".$course;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Next Page</a>
		
		
		<?php
				}
			}
			else if($page>0 && $blank_record<=0)
			{
				$last = $page - 2;
				if(!isset($_GET['dept'])){
		?>	
				<a href="?page=<?php echo $last;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Page <?php echo $last;?></a>
		<?php
				}
				else if(isset($_GET['dept']) && !isset($_GET['course'])){
		?>

				<a href="?page=<?php echo $last."&dept=".$department;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Page <?php echo $last;?></a>
		
		<?php
				}
				else if(isset($_GET['dept']) && isset($_GET['course'])){
		?>
				<a href="?page=<?php echo $last."&dept=".$department."&course=".$course;?>" style="border-radius:6px; border-color:black; border-style:solid; background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%); padding:4px;">Page <?php echo $last;?></a>
					
		<?php
				}
			}
		?>
		<!--//Pagination-->
				
	</div>
	<!--Submit-->
	
	<div class="col-md-2" style='margin-left:40px; background: linear-gradient(to bottom, #F5F5DC 0%, #ADFF2F 100%); padding-bottom:10px; padding-top:10px; border-radius:5px;
			 box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);'>
			 
			 
	<span style="font-family:arial;font-size:16px; margin-left:29px;"> Submit  A Post</span>
		<a style="color:white;"href="submitpost.php"><button class="btn btn-info" style="margin-bottom:15px; margin-left:17px;">Submit a Post</button></a>
		<span style="margin-top:10px;font-family:arial;font-size:16px; margin-left:37px;">Filter Feed</span>
		<label style="margin-left:5px;" >Select Department<span title="Required" style="font-size:12px;" >*</span></label>
		<form action="php/filter_newsfeed.php" method="POST">
		<select style="margin-top:10px;margin-bottom:10px;" class="form-control" name="select_dept" required id='select_dept'>
			<option value="">Select Department</option>
			<?php
			 $dept_query = $db -> query ("SELECT department FROM department_tbl ORDER BY department ASC");
			 $dept_query -> execute();
			 while($dept_row = $dept_query -> fetch(PDO::FETCH_ASSOC)){
				 $dept_option = $dept_row['department'];
			?>
			<option value="<?php echo $dept_option;?>"><?php echo $dept_option;?></option>
			<?php
			}
			?>
			
		</select>
		<label  style="margin-left:27px;">Select Course</label>
			<script type=''>
				$(document).ready(function(){
					$("#select_dept").on('change', function(){
						var dept = $(this).val();
						$.ajax({
							type: 'POST',
							url: 'pages/filter_feed.php',
							data:
							{
								department_filter: dept,
							},
							cache: false,
							success: function(data){
								$("#span_course").html(data);
							},
						});
					});
				});
			</script>
			
			<span id="span_course">
			<select style="margin-top:10px;margin-bottom:10px;"class="form-control" name="select_course" id='select_course'>
				<option value="">Select Course</option>
			</select>
			</span>
			
		<input type="submit" name="submit" value="Filter Feed" class="btn btn-info form-control" style="margin-top:10px;">
		</form>
		<?php
		if(isset($_GET['dept']) || isset($_GET['course'])){
		?>
			<a href="index"><button style="margin-top:20px;" class="btn btn-info form-control">Default Feed</button></a>
		<?php
		}
		
		?>
	</div>


							<!--END MISSION and VISION OF LSPU-->
		</div>
		<div title="Report Why?" id="report_reason" style="visibility:hidden;display:none;">
			<form method="POST">
			<span  style="display:block;"><input type="radio" name="report" value="Violent">Contains Violent content</span>
			<span  style="display:block;"><input type="radio" name="report" value="Sexual">Has sexually offensive content</span>
			<span  style="display:block;"><input type="radio" name="report" value="Spam">Is a spam</span>
			<span  style="display:block;"><input type="radio" name="report" value="Offensive">Has offensive language (Racist, Sexist, etc.)</span>
			<span  style="display:block;"><input type="radio" name="report" value="Other">Other</span>
			<input type="hidden" value="" id="report_id">
			<input type="text" class="form-control" name="content" id="report_content" required style="margin: 8px 0 10px 0;" placeholder="Details">
			<button name="submit_report_post" value="Report" class="btn btn-danger submit_report_post form-control">Report</button>
			</form>
		</div>
	<?php	
		}
		else{
			include_once ("Nothing.php");
			echo "</div>";	
		}
	}
	?>
	</div>

    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
               <div class="col-sm-6" style="color:white;">
                   &copy; 2017 <a target="_blank" href="#" title="arvin is awesome">Suicide Squad</a>. All Rights Reserved.
                </div>

            </div>
        </div>
    </footer><!--/#footer-->

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/wow.min.js"></script>
</body>
</html>