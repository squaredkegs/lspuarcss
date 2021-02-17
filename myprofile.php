<?php
	include_once ("php/connection.php");
	include_once ("php/function.php");
	include_once ("php/querydata.php");
	include_once ("php/profile_query.php");
	$check_user_login = checkLogIn();

	
	if(isset($_GET['user']) && $getid!=$user_id)
	{
							
	if($profile_num_row==1){
		$check_friend_relation = $db -> prepare ("SELECT * FROM frnd_rqst WHERE (frst_user=:frid AND scnd_user=:sid) || (frst_user=:sid AND scnd_user=:frid)");
		$check_friend_relation -> bindParam (":frid", $user_id);
		$check_friend_relation -> bindParam (":sid", $getid);
		$check_friend_relation -> execute();
		$num_check_friend_relation = $check_friend_relation -> rowCount();
		if($num_check_friend_relation==1){
		$result = $check_friend_relation -> fetch();
		$first_user = $result['frst_user'];
		$second_user = $result['scnd_user'];
		$friend_status = $result['status'];
		}
		else{
			$friend_status = 0;
		}
	}

	}	
	?>
	
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Profile | <?php 		
					if(isset($_GET['user'])){
						if($profile_num_row==1){
							if($getid==$user_id || !isset($_GET['user'])){
								echo $rfname ." ". $rlname ; 
							}
							else if(isset($_GET['user']) && $getid!=$user_id){
								echo $profile_fname." ".$profile_lname." ".$user_id;
							}
							else{
								echo "Error";
							}
						}
						else{
							echo "Error";
						}
					}
					else{
						echo $rfname." ".$rlname;
					}
			?></title>
    
    <!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
	<link href="css/profile.css" rel="stylesheet">
    <link href="css/corlate.css" rel="stylesheet"> 
	<link href="css/mymain.css" rel="stylesheet">
    <link href="css/otherprofile.css" rel="stylesheet">	
	<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    
    <link rel="shortcut icon" href="image/thesis.png">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
	<!--<link rel="stylesheet" href="/resources/demos/style.css">
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	-->
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/simplefunction.js"></script>
	<script type="text/javascript">
	function alphanumeric(inputtxt)  
	{   
		var letters = /^[0-9a-zA-Z]+$/;  
		if(inputtxt.value.match(letters))  
		{  	
			if(inputtxt.value.length>7){
			document.form.create_user.focus();
			return true;
			}
			else{
				alert('Too Short');
				return false;
			} 
		document.form1.text1.focus();  
		return true;  
		}  
		else  
		{  
		alert('Please input alphanumeric characters only');  
		return false;  
		}  
	}  
	
	</script>
	

	<?php
	
	include_once("scripts/profile_scripts.php");
	include_once ("scripts/visiting_profile_scripts.php");
	
	?>
	
	<!--//Style for friend confirmation-->
	
	<style>
	#choose_from_current_photos
	{
		width: 800px;
		height: 800px;
		overflow: hidden;
		overflow-y: scroll;
		overflow-x: scroll;
	}
	</style>
	<?php
	if($getid!=$user_id && isset($_GET['user'])){
		if($profile_num_row==1){
			if($num_check_friend_relation==0 && $friend_status==0)
			{
			?>
			<style type="text/css">
				#add-friend-button
				{
					display:inline;
				}
			</style>					
			<?php
			}
			else if ($num_check_friend_relation>0 && $friend_status==1)
			{
				if($first_user==$getid){
			?>
			<style type="text/css">
				#cancel-button
				{
					display:inline;
				}
			</style>
			<?php						
				}
				else if($second_user==$getid){
			?>
			<style type="text/css">
				#accept-friend-button
				{
					display:inline;
				}
				
				#reject-friend-button
				{
					display:inline;
				}
			</style>
			
			<?php
				}
			}
			else if($num_check_friend_relation==2 || $friend_status==2)
			{
			?>
				
				<style type="text/css">
				#friends
				{
					display:inline;
				}
				</style>
			<?php		
			}
		}	
	}
	?>
</head><!--/head-->

<body style="background: linear-gradient(to bottom, #F0F8FF 0%, #F5F5F5 100%);">
	<?php
		include_once ("header.php");
	?>

    <section id="blog" class="container" >
		<div class="blog">
            <div class="row">
				<!--<div class="col-md-12 main-distance">-->
				<div class="col-md-12" style="margin-top:-25px;">
					<div class="col-md-3" >
							<?php
							if(isset($_GET['user'])){
								if($profile_num_row==1){
								
							?>
							<!--Put jQuery on change image-->
							<div class="col-md-11 profile-distance" style="position:relative;">
							<?php
								if($getid==$user_id || !isset($_GET['user'])){

									if(empty($rpicpath))
									{
							?>
								<img class="media-object profilepic profile_picture" style="border-radius:5px; border-style:solid; border-color:#4682B4;" src="image/profile/profile1.png" alt="" >
							
							<?php
									}	
									else
									{
							?>
							<img class="media-object profilepic profile_picture" style="border-radius:5px; border-style:solid; border-color:#4682B4;" src="php/<?php echo $rpicpath;?>" alt="" >
								<?php
									}
								}
								else{
									if(empty($profile_picture_path))
									{
							?>
								<img class="media-object profilepic profile_picture" style="border-radius:5px; border-style:solid; border-color:#4682B4;" src="image/profile/profile1.png" alt="" >
							
							<?php
									}	
									else
									{
							?>
							<img class="media-object profilepic profile_picture" style="border-radius:5px; border-style:solid; border-color:#4682B4;" src="php/<?php echo $profile_picture_path;?>" alt="" >
								<?php
									}
								}								
								?>		
						<?php
							if($getid==$user_id || !isset($_GET['user'])){

						?>
							<img src="image/extra/camera.png" class="picture_edit" id="camera_icon"/>
						<?php
							}
						?>
						<?php
						if(isset($_GET['user']) && $getid!=$user_id)
						{
							
							if($profile_num_row==1){
								$check_friend_relation = $db -> prepare ("SELECT * FROM frnd_rqst WHERE (frst_user=:frid AND scnd_user=:sid) || (frst_user=:sid AND scnd_user=:frid)");
								$check_friend_relation -> bindParam (":frid", $user_id);
								$check_friend_relation -> bindParam (":sid", $getid);
								$check_friend_relation -> execute();
								$numrow_check_friend_relation = $check_friend_relation -> rowCount();
								if($num_check_friend_relation==1){
								$result = $check_friend_relation -> fetch();
								$first_user = $result['frst_user'];
								$second_user = $result['scnd_user'];
								}
								?>
								<button class="btn btn-info add-friend"
									id="add-friend-button">
										<span>
									Add Friend
										</span>
								</button>
									
								<button class="btn btn-info add-friend friend
									cancel" id="cancel-button">
										<span>
										Awaiting Confirmation
										</span>
								</button>
								
								<button class="btn btn-info add-friend" id="accept-friend-button">
								Accept Friend Request
								</button>
								
								<button class="btn btn-danger reject-friend" id="reject-friend-button">
								Reject Friend Request
								</button>
								
								<button class="add-friend friend current-friend" class="btn "
								id="friends">
									<span>
									Already Friends
									</span>
								</button>
					
						<?php
							}
						}
						?>						
							
							
							</div>
							<div class="col-md-12" >
								<div class="parrent pull-left" style="background-color:white; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19); border-radius:4px;" >
									<ul class="nav side-tabs nav-stacked" >
										<li class="" ><a href="#" class="profile-click" name="main" id="main-click" style="color:black;">Main Info
										</a></li>
										<li class=""><a href="#" class="profile-click" name="other" id="other-click" style="color:black;">Other Information</a></li>
										<li class=""><a href="#" class="profile-click" id="post-click" style="color:black;">Posts & Comments</a></li>
											<!-- data-toggle="tab"-->	
										<li class=""><a href="#" class="profile-click" name="friend" id="friend-click" style="color:black;">Friends</a></li>
										<?php
											if($getid==$user_id || !isset($_GET['user'])){
										?>
										<li class=""><a href="#" class="profile-click" name="save" id="save-click" style="color:black;">Saves</a></li>
										<?php
											}
										
										?>
									</ul>
								</div>
							</div>
					</div>
					<div id="information-container">
					</div>
				
         </div><!--/.blog-->
	</div>
    </section><!--/#blog-->

		<div style="display:none;" id="change_profile_pic">
			<!--Nasa profile.css ang class vvvvv-->
			<form action="php/profile_pic_exec.php" method="post" class="form-group " enctype="multipart/form-data">
				<input type="file" name="picture" style="margin-top:10px;" accept="image/*" required/>
				<input type="submit" name="submit_picture" value="Upload" class="btn btn-info form-control" style="margin-top:10px;"/>
			</form>
			<!--Hanggang Dito-->
		</div>


		<div style="display:none;" id="choose_from_current_photos">
			<!--Nasa profile.css ang class vvvvv-->
				<?php
					$query_pic = $db -> prepare ("SELECT stud_picture.pic_id as picid, 
								stud_picture.picture_name as picname, stud_picture.picture_path as picpath, stud_picture.datetime as datetime FROM picture_connect
								LEFT JOIN stud_picture
								ON stud_picture.pic_id = picture_connect.pic_id
								LEFT JOIN stud_bas
								ON stud_bas.stud_id = picture_connect.stud_id
								WHERE stud_bas.stud_id=:getid");
					$query_pic -> bindParam (":getid", $getid);
					$query_pic -> execute();
					while($row = $query_pic -> fetch(PDO::FETCH_ASSOC)){
						$picname = $row['picname'];
						$picpath = $row['picpath'];
						$picid = $row['picid'];
						$datetime = $row['datetime'];
					
				?>
					<img src="php/<?php echo $picpath;?>" class="choose_profile" style="height:200px;"/>
				<?php
				}
				?>
			<!--Hanggang Dito-->
		</div>


		
		<span id="user-unfriended" title="Remove from friends" class='form-group' style='display:none;'>
		<center>
		<button id='close-user-unfriended' class='btn btn-info' style='margin-top:5px;width:140px;'>Okay</button>
		</center>
		</span>
		<span id="confirm-unfriend-button" title="Are You Sure?"></span>
		
						<?php
						//end of isset($_GET['user'])
							}
							else{
							include_once("profile_doesnt_exists.php");
						
						
						?>
						
						</div>
						</section>
						<?php
							}
						}
						else{
							include_once ("profile_doesnt_exists.php");
						?>
							
						</div>
						</section>
						<?php
						}
						?>
	<footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2016 and 2017 <a target="_blank" href="#" title="Created by Team Suicide Squad">Suicide Squad</a>. All Rights Reserved.
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