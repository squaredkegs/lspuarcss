<!DOCTYPE html>
<?php
	session_start();
	include_once ("php/connection.php");
	include_once ("php/querydata.php");
	include_once ("php/function.php");
	$check = checkLogIn();			
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Submit Post </title>
	
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
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
	<script src="js/jquery-1.12.4.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			var dept = $("#select_dept").val();
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
		
		$(document).ready(function(){
			$("#post_media").on('change', function(){
				var media_val = $(this).val();
				var cut_media_name = media_val.lastIndexOf("\\");
				var media_name = media_val.substr(cut_media_name + 1);
				var cut_media_name_again = media_name.lastIndexOf(".");
				var extension = media_name.substr(cut_media_name_again + 1).toUpperCase();
				
				if(media_val==""){
						$("#show_media_name").html("");
						$("#clear_media").hide();
				}
				else{
					if( extension == "JPG"  ||
						extension == "JPEG" ||
						extension == "PNG"  ||
						extension == "MP4"  ||
						extension == "AVI"
						){
						$("#submit_button").prop("disabled", false);
					}
					else{
						$("#submit_button").prop("disabled", true);
					}
						$("#clear_media").css("display", "inline");
						$("#show_media_name").html(media_name);
						$("#show_media_name").css("display", "inline");
				}

			});
		});
		
		$(document).ready(function(){
			$("#clear_media").on('click', function(){
				$("#post_media").val("");
				$("#show_media_name").html("");
				$("#clear_media").hide();
				$("#submit_button").prop("disabled", false);
			});
		});
	</script>
</head><!--/head-->

<body class="homepage" style="background: linear-gradient(to bottom, #F0F8FF 0%, 	#F5F5F5 100%);">

	<?php
		require_once ("header.php");
	?>
    

		<!--Content-->
			<!--BANNER-->	<img src="image/banner.png" title="Laguna State Polytechnic University" alt="logo" style="margin-top:4px;" width="100%" height="100%" border="0"> <!--BANNER-->
		<div class="container" style="margin-top:50px;">
			<div class="col-md-11 col-md-offset-1"
			style="height:;width:700px;margin-bottom:25px;">
				<?php
				$datetime = date('Y-m-d H:i:s');
				
				?>
				<form action="php/submitpost_exec.php" method="POST" name="form" enctype='multipart/form-data'>
					<span style="font-size:29px;margin-bottom:15px;"><center>Title</center></span>
					<input style="margin-top:15px;margin-bottom:15px;"type="text" name="title" autocomplete="off" class="form-control" required maxlength='100'>
					<!--Put text editor-->
					<!---->
					<label>Description</label>
					<textarea id="description" name="desc" cols="81" rows="8" style="margin-bottom:15px;resize:vertical;" required></textarea>
					<span style="display:none;margin-bottom:15px;" id="live_preview"></span>
					<label style="display:block;">Department</label>
					<select class="form-control" style="margin-bottom:15px;" name="dept" required id="select_dept">
						<option value="<?php echo $rdepartment;?>"><?php echo $rdepartment;?></option>
						<?php
						$dept_query = $db -> prepare ("SELECT * FROM department_tbl ORDER BY department ASC");
						$dept_query -> execute();
						
						while($row = $dept_query -> fetch(PDO::FETCH_ASSOC)){
							$department_id = $row['did'];
							$dept = $row['department'];
							$abbr = $row['abbr'];
						?>
						<option value="<?php echo $dept;?>"><?php echo $dept;?></option>
						<?php
						}
						?>
					</select>
					<label>Course (Optional)</label>
					<span id="span_course">
					<select class="form-control" style="margin-bottom:15px;" name="course">
						<option value="">Select Course</option>
					</select>

					</span>
					<label id='select_media' for='post_media' style='cursor:pointer;border-width:2px;border-style:solid;border-color: rgb(160,160,255);'>Picture/Video <img src='image/extra/media.png' style='height:25px;width:25px;'></label> (Optional)
					<div>
					<span style='display:none;' id='show_media_name'></span>
					<span id='clear_media' style='display:none;color:red;font-weight:bold;cursor:pointer;'>X</span>
					</div>
					<input type='file' name='post_media' id='post_media' style='display:none;' accept='image/jpeg, image/jpg, image/png, video/mp4, video/avi'>
					<input style="margin-bottom:15px;margin-top:15px;" type="submit" id='submit_button' name="submit" class="btn btn-info form-control">
				
				</form>
			</div>
			
			<!--START MISSION and VISION OF LSPU-->
							
<div class="col-md-2" style='margin-top:10px; margin-left:40px; background: linear-gradient(to bottom, 	#F5F5DC 0%, #ADFF2F 100%); padding-bottom:10px; padding-top:10px; border-radius:5px;
			 box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);'>
			 
	<center><span style="color:black; font-size:20px;"> VISION </span></center>
	
			 <span style="color:black; padding-bottom:20px;  font-family:Times New Roman;"> LSPU shall be the Center for sustainable Development, transforming Lives and Communities. </span>
			 
	<center><span style="color:black; font-size:20px;"> MISSION </span></center>
	
			  <span style="color:black;  font-family:Times New Roman;"> LSPU Provides quality education through responsive instruction, distinctive research, sustainable 
			  extension and production services for improved quality of life towards nation-building. </span>
	
	</div>

							<!--END MISSION and VISION OF LSPU-->
		</div>
		
		
	
  <!--/#bottom-->

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