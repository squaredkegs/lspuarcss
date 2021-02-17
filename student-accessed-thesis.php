<!DOCTYPE html>
			
<?php
	include_once ("php/connection.php");
	include_once ("php/querydata.php");
	include_once ("php/function.php");
	$check_user_login = checkLogIn();
	
	$record_limit = 15;
	$offset = 0;
	

	$query = $db -> prepare ("SELECT thesis_arch.complete_filename as compname, thesis_arch.filepath as filepath, thesis_arch.year as year, thesis_arch.title as title, thesis_arch.campus as thes_camp, thesis_arch.type as type, thesis_arch.department as thes_dept FROM request_thesis_connect 
	INNER JOIN thesis_arch
	ON thesis_arch.thesis_id = request_thesis_connect.thesis_id
	INNER JOIN stud_bas
	ON stud_bas.stud_id = request_thesis_connect.stud_id
	INNER JOIN request_thesis
	ON request_thesis.request_id = request_thesis_connect.request_id
	WHERE stud_bas.stud_id = :sid AND  (request_thesis.status = 'Approved' OR request_thesis.status = 'Deleted')");
	$query -> bindParam (":sid", $getid);
	//$query -> bindValue (":offset", (int) trim ($offset), PDO::PARAM_INT);
	//	$query -> bindValue (":record_limit", (int) trim($record_limit), PDO::PARAM_INT);
	$query -> execute();

	$numquery = $db -> prepare ("SELECT thesis_arch.complete_filename as compname, thesis_arch.filepath as filepath, thesis_arch.year as year, thesis_arch.campus as thes_camp, thesis_arch.department as thes_dept FROM request_thesis_connect 
	INNER JOIN thesis_arch
	ON thesis_arch.thesis_id = request_thesis_connect.thesis_id
	INNER JOIN stud_bas
	ON stud_bas.stud_id = request_thesis_connect.stud_id
	INNER JOIN request_thesis
	ON request_thesis.request_id = request_thesis_connect.request_id
	WHERE stud_bas.stud_id = :sid AND (request_thesis.status = 'Approved' OR request_thesis.status = 'Deleted')");
	$numquery -> bindParam (":sid", $getid);
	$numquery -> execute();
	$numrow = $numquery -> rowCount();
	
		if(isset($_GET['page'])){
			$page = $_GET['page'];
			$offset = $record_limit * $page;
			$offset = ($record_limit * $page) - $record_limit;
			
		}
	
	$upper_limit = ceil($numrow/$record_limit);
			
			
		if(isset($_GET['page'])){
			$page = $_GET['page'];
			$offset = $record_limit * $page;
			$offset = ($record_limit * $page) - $record_limit;
		
			
		}
	
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Thesis Archive | Accessed Archive </title>
	
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
	<script>
	function request_thesis (this_button,e){

				var id = $(this_button).attr("id");
				var container = $(this_button).closest(".request-container");
				var cut_id = id.lastIndexOf("_");
				var real_id = id.substr(cut_id + 1);
					$.ajax({
						type: 'POST',
						url: 'php/request_thesis_access.php',
						data:
						{
							thid: real_id,
						},
						cache: false,
						success: function(data){
							$(container).html(data);
						},
					});
				e.preventDefault();
				
		}
		
			function cancel_request (this_button,e){
				var id = $(this_button).attr("id");
				var container = $(this_button).closest(".request-container");
				var cut_id = id.lastIndexOf("_");
				var real_id = id.substr(cut_id + 1);
					$.ajax({
						type: 'POST',
						url: 'php/cancel_request_thesis.php',
						data:
						{
							thid: real_id,
						},
						cache: false,
						success: function(data){
							$(container).html(data);
						},
					});
				e.preventDefault();
		}
	
	</script>
</head>
</script>
<body class="homepage" style="background: linear-gradient(to bottom, #F0F8FF 0%, #F5F5F5 100%);">

	<?php
		include_once ("header.php");
	?>
		<!--Content-->
	<!--BANNER-->	
	<img src="image/banner.png" title="Laguna State Polytechnic University" alt="logo" style="margin-top:4px;" width="100%" height="100%" border="0"> 
	<!--BANNER-->
	
	<div class="container" style="margin-top:50px;">
		
		<!--Start of Thesis Archive Display-->
		<?php
		if($numrow>15){
		?>
		<div class="col-md-12" style="margin-bottom:25px;margin-left:20px; background-color:white; border-radius:8px; padding:4px; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);" id="newsfeed">
		<?php
		}
		else{
		?>
		<div class="col-md-12" style="margin-bottom:25px;margin-left:20px; background-color:white; border-radius:8px; padding:4px; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);height:700px;" id="newsfeed">
		<?php	
		}
	if($numrow>0){
		while($row = $query -> fetch (PDO::FETCH_ASSOC)){
		/*
		"SELECT thesis_arch.complete_filename as compname, thesis_arch.filepath as filepath, thesis_arch.year as year, thesis_arch.campus as thes_camp, thesis_arch.department as thes_dept FROM request_thesis_connect 
		*/
		$thesis_path = $row['filepath'];
		$thesis_year = $row['year'];
		$thesis_campus = $row['thes_camp'];
		$thesis_dept = $row['thes_dept'];
		$thesis_type = $row['type'];
		$title = $row['title'];
		$thesis_complete_filename = $row['compname'];
		$get_filename = $db -> prepare ("SELECT abstract_filename,filepath,type,upload_date,complete_filename FROM thesis_arch WHERE thesis_id = :thid");
		$get_filename -> bindParam (":thid", $thid);
		$get_filename -> execute();
			$r_get_filename = $get_filename -> fetch();
			$abstract = $r_get_filename['abstract_filename'];
			$filepath = $r_get_filename['filepath'];
			$complete = $r_get_filename['complete_filename'];
							
			$check_if_unlock = $db -> prepare ("SELECT request_thesis_connect.request_id as rqid, request_thesis.status as request_status FROM request_thesis_connect 
			LEFT JOIN request_thesis
			ON request_thesis.request_id = request_thesis_connect.request_id
			WHERE request_thesis_connect.thesis_id = :thid AND request_thesis_connect.stud_id = :sid
							");
			$check_if_unlock -> bindParam (":thid", $thid);
			$check_if_unlock -> bindParam (":sid", $getid);
			$check_if_unlock -> execute();
			$res_check_unlock = $check_if_unlock -> fetch();
			$request_status = "";
			$request_status = $res_check_unlock['request_status'];
			$numrow_check_unlock = $check_if_unlock -> rowCount();
	?>
	<div class='col-md-5' style='position:relative;border:2px solid #1E90FF; border-radius:5px; margin-bottom:10px;height:170px;margin-left:60px; margin-top:10px;'>
			<label>
				<a href='php/<?php echo $thesis_path.$thesis_complete_filename;?>'target="_blank" style="color:red"><?php echo $title."(Complete)";?>
				</a>
			</label>		
	<span>
		
		<div style='font-size:10px;'>
			<span style='display:block;margin-top:-5px;'><?php echo $thesis_campus;?></span>
			<span style='display:block;margin-top:-5px;'><?php echo $thesis_dept;?></span>
			<span style='display:block;margin-top:-5px;'><?php echo $thesis_year;?></span>
		</div>

		<a href='php/<?php echo $thesis_path.$thesis_complete_filename;?>' style='margin-bottom:25px;' download title='Download Abstract'><img style='height:20px;width:20px;margin-top:30px;'src='image/extra/download_button.png' ></a>
				
	</div>
		<?php
		}
	}
	else{
		echo "<span style='display:block;font-size:25px;margin-left:25px;margin-top:25px;'>Nothing Here</span>";
	}
		?>
				
				
		</div>
		<!--Start of Pagination-->
		<div class='col-md-12'>
		<?php
			if(!isset($_GET['page'])){
				$page = 1;
			}
			$next_page = $page + 1;
			$previous_page = $page - 1;
		
		if($previous_page>0){
		?>
		<a href='thesis_archive?page=<?php echo $previous_page;?>'>
		<?php echo $previous_page;?>
		</a>
		<?php
		}
		?>
		<?php
		if($next_page<=$upper_limit){
		?>
		<a href='thesis_archive?page=<?php echo $next_page;?>'>
		<?php echo $next_page;?>
		</a>
		<?php
		}
		?>
	
		</div>
		<!--End of thesis archive display-->

		
		<!--End of Pagination-->
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