<?php
	include_once ("php/connection.php");
	include_once ("php/function.php");
	include_once ("php/querydata.php");
	$check_user_login = checkLogIn();
	$get_dept_id = $db -> prepare ("SELECT department_id as did FROM department_tbl WHERE department=:dept");
		$get_dept_id -> bindParam (":dept", $rdepartment);
		$get_dept_id -> execute();
		$res_did = $get_dept_id -> fetch();
		$did = $res_did['did'];
	
	if(isset($_GET['var'])){
		$variable = $_GET['var'];
		
	
		if(isset($_GET['sort'])){
			$sort = $_GET['sort'];
		}
	
	
		$variable = $_GET['var'];
		$variable_to_array = explode(" ",$variable);
		$echo_array = implode(" +",$variable_to_array);
		$count = count($variable_to_array);
		if($count==1){
			$student_variable = "+".$variable;
			$thesis_variable = "+".$variable;
			$post_variable = "+".$variable;
		}
		else if($count==2){
			$first_variable = $variable_to_array[0];
			$second_variable = $variable_to_array[1];
			$student_variable = "+".$first_variable." +".$second_variable;
			$thesis_variable = $student_variable;
			$post_variable = $student_variable;
		}
		else if($count==3){
			$first_variable = $variable_to_array[0];
			$second_variable = $variable_to_array[1];
			$third_variable = $variable_to_array[2];		
			$student_variable = "+(".$first_variable." ".$second_variable.")"." +".$third_variable;
			$thesis_variable = $student_variable;
			$post_variable = $student_variable;
		}
		else if($count>=4){
			$student_variable = "+".$echo_array;
			$post_variable = $student_variable;
		}
		$search_student_query = $isam_db -> prepare ("SELECT fname,lname,stud_id FROM isam_stud_bas WHERE MATCH 	(fname,lname) AGAINST (:variable IN BOOLEAN MODE);");
		$search_student_query -> bindValue (":variable",$student_variable, PDO::PARAM_STR);	
		$search_student_query -> execute();
		$student_numrow = $search_student_query -> rowCount();

	
		$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
		WHERE MATCH (title ,description) AGAINST (:variable IN BOOLEAN MODE);");
		$search_thesis_query -> bindValue (":variable",$variable, PDO::PARAM_STR);
		$search_thesis_query -> execute();			
			
			
		$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE)");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> execute();
				
	if(isset($_GET['sort'])){
		if(!isset($_GET['department'])){
		$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
		WHERE MATCH (title ,description) AGAINST (:variable IN BOOLEAN MODE);");
		$search_thesis_query -> bindValue (":variable",$variable, PDO::PARAM_STR);
		$search_thesis_query -> execute();			
		}
		else if($sort=='thesis'){
		//dept only
			if(isset($_GET['department']) && !isset($_GET['course']) && !isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept");
			$search_thesis_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_thesis_query -> bindParam (":dept", $dept);
			$search_thesis_query -> execute();
			}
			//dept and course
			else if(isset($_GET['department']) && isset($_GET['course']) && !isset($_GET['campus']) && !isset($_GET['year'])&& !isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$course = $_GET['course'];
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND course = :course");
			$search_thesis_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_thesis_query -> bindParam (":dept", $dept);
			$search_thesis_query -> bindParam (":course", $course);
			$search_thesis_query -> execute();
			}		
			//dept and year
			else if(isset($_GET['department']) && isset($_GET['year']) && isset($_GET['other_year']) && !isset($_GET['campus']) && !isset($_GET['course'])){
			$dept = $_GET['department'];
			$year = $_GET['year'];
			$other_year = $_GET['other_year'];
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
			$search_thesis_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_thesis_query -> bindParam (":dept", $dept);
			$search_thesis_query -> bindParam (":other_year", $other_year);
			$search_thesis_query -> bindParam (":year", $year);
			$search_thesis_query -> execute();
			}		
			//dept and campus
			else if(isset($_GET['department']) && isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['course']) && !isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$campus = $_GET['campus'];
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND campus = :campus");
			$search_thesis_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_thesis_query -> bindParam (":dept", $dept);
			$search_thesis_query -> bindParam (":campus", $campus);
			$search_thesis_query -> execute();
			}		
			//dept and course and campus
			else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$course = $_GET['course'];
			$campus = $_GET['campus'];
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND course = :course AND campus = :campus");
			$search_thesis_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_thesis_query -> bindParam (":dept", $dept);
			$search_thesis_query -> bindParam (":course", $course);
			$search_thesis_query -> bindParam (":campus", $campus);
			$search_thesis_query -> execute();
			}
			//dept and course and year
			else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['year'])&& !isset($_GET['campus']) && isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$course = $_GET['course'];
			$year = $_GET['year'];
			$other_year = $_GET['other_year'];
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND course = :course AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
			$search_thesis_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_thesis_query -> bindParam (":dept", $dept);
			$search_thesis_query -> bindParam (":course", $course);
			$search_thesis_query -> bindParam (":other_year", $other_year);
			$search_thesis_query -> bindParam (":year", $year);
			$search_thesis_query -> execute();
			}
			//dept and campus and year
			else if(isset($_GET['department']) && isset($_GET['year']) && isset($_GET['campus']) && !isset($_GET['course']) && isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$campus = $_GET['campus'];
			$year = $_GET['year'];
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND campus = :campus AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
			$search_thesis_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_thesis_query -> bindParam (":dept", $dept);
			$search_thesis_query -> bindParam (":year", $year);
			$search_thesis_query -> bindParam (":other_year", $other_year);
			$search_thesis_query -> bindParam (":campus", $campus);
			$search_thesis_query -> execute();
			}				
			
			//all
			else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['campus']) && isset($_GET['year']) && isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$course = $_GET['course'];
			$campus = $_GET['campus'];
			$year = $_GET['year'];	
			$other_year = $_GET['other_year'];
			$search_thesis_query = $isam_db -> prepare ("SELECT * FROM isam_thesis_arch 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND course = :course AND campus = :campus AND (year BETWEEN :other_year AND :year OR year BETWEEN :year AND :other_year)");
			$search_thesis_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_thesis_query -> bindParam (":dept", $dept);
			$search_thesis_query -> bindParam (":course", $course);
			$search_thesis_query -> bindParam (":campus", $campus);
			$search_thesis_query -> bindParam (":year", $year);
			$search_thesis_query -> bindParam (":other_year", $other_year);
			$search_thesis_query -> execute();
			}
		}
		else if($sort=='posts'){
			//dept only
			if(isset($_GET['department']) && !isset($_GET['course']) && !isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> bindParam (":dept", $dept);
			$search_posts_query -> execute();
			}
			//dept and course
			else if(isset($_GET['department']) && isset($_GET['course']) && !isset($_GET['campus']) && !isset($_GET['year'])&& !isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$course = $_GET['course'];
			$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND course = :course");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> bindParam (":dept", $dept);
			$search_posts_query -> bindParam (":course", $course);
			$search_posts_query -> execute();
			}		
			//dept and year
			else if(isset($_GET['department']) && isset($_GET['year']) && isset($_GET['other_year']) && !isset($_GET['campus']) && !isset($_GET['course'])){
			$dept = $_GET['department'];
			$year = $_GET['year'];
			$other_year = $_GET['other_year'];
			$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND (year(date_and_time) BETWEEN :other_year AND :year OR year(date_and_time) BETWEEN :year AND :other_year)");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> bindParam (":dept", $dept);
			$search_posts_query -> bindParam (":other_year", $other_year);
			$search_posts_query -> bindParam (":year", $year);
			$search_posts_query -> execute();
			}		
			//dept and campus
			else if(isset($_GET['department']) && isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['course']) && !isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$campus = $_GET['campus'];
			$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND campus = :campus");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> bindParam (":dept", $dept);
			$search_posts_query -> bindParam (":campus", $campus);
			$search_posts_query -> execute();
			}		
			//dept and course and campus
			else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['campus']) && !isset($_GET['year']) && !isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$course = $_GET['course'];
			$campus = $_GET['campus'];
			$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND course = :course AND campus = :campus");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> bindParam (":dept", $dept);
			$search_posts_query -> bindParam (":course", $course);
			$search_posts_query -> bindParam (":campus", $campus);
			$search_posts_query -> execute();
			}
			//dept and course and year
			else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['year'])&& !isset($_GET['campus']) && isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$course = $_GET['course'];
			$year = $_GET['year'];
			$other_year = $_GET['other_year'];
			$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND course = :course AND (year(date_and_time) BETWEEN :other_year AND :year OR year(date_and_time) BETWEEN :year AND :other_year)");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> bindParam (":dept", $dept);
			$search_posts_query -> bindParam (":course", $course);
			$search_posts_query -> bindParam (":other_year", $other_year);
			$search_posts_query -> bindParam (":year", $year);
			$search_posts_query -> execute();
			}
			//dept and campus and year
			else if(isset($_GET['department']) && isset($_GET['year']) && isset($_GET['campus']) && !isset($_GET['course']) && isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$campus = $_GET['campus'];
			$year = $_GET['year'];
			$other_year = $_GET['other_year'];
			$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND campus = :campus AND (year(date_and_time) BETWEEN :other_year AND :year OR year(date_and_time) BETWEEN :year AND :other_year)");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> bindParam (":dept", $dept);
			$search_posts_query -> bindParam (":year", $year);
			$search_posts_query -> bindParam (":other_year", $other_year);
			$search_posts_query -> bindParam (":campus", $campus);
			$search_posts_query -> execute();
			}				
			
			//all
			else if(isset($_GET['department']) && isset($_GET['course']) && isset($_GET['campus']) && isset($_GET['year']) && isset($_GET['other_year'])){
			$dept = $_GET['department'];
			$course = $_GET['course'];
			$campus = $_GET['campus'];
			$year = $_GET['year'];	
			$other_year = $_GET['other_year'];
			$search_posts_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed 
			WHERE MATCH (title,description) AGAINST (:variable IN BOOLEAN MODE) AND department = :dept AND course = :course AND campus = :campus AND (year(date_and_time) BETWEEN :other_year AND :year OR year(date_and_time) BETWEEN :year AND :other_year)");
			$search_posts_query -> bindValue (":variable", $variable, PDO::PARAM_STR);
			$search_posts_query -> bindParam (":dept", $dept);
			$search_posts_query -> bindParam (":course", $course);
			$search_posts_query -> bindParam (":campus", $campus);
			$search_posts_query -> bindParam (":year", $year);
			$search_posts_query -> bindParam (":other_year", $other_year);
			$search_posts_query -> execute();
			}
		}
	}
		$thesis_numrow = $search_thesis_query -> rowCount();
		$post_numrow = $search_posts_query -> rowCount();
		/*
		$post_query = $isam_db -> prepare ("SELECT * FROM isam_newsfeed WHERE MATCH(title,description) AGAINST (:variable IN BOOLEAN MODE);");
		$post_query -> bindParam (":variable", $post_variable);
		$post_query -> execute();
		*/
		$new_variable = str_replace(" ","%20",$variable);
		}
	else {
		$variable = "Not Found";
	}
	
?>
	
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Search - <?php echo $variable;?></title>
    
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
	<!--<script src="js/jquery-1.12.4.js"></script>
	-->
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">

</head><!--/head-->
	<script type="text/javascript">
		$(document).ready(function(){
			$("#student-results").click(function(){
				var stud_var = "<?php echo $new_variable;?>";
				$("#result-container").load("student.php?var=" + stud_var);
				window.history.pushState ("student", "Student", "/smnp/result?var=" + stud_var + "&sort=students");
			});
		});

		$(document).ready(function(){
			$("#thesis-results").click(function(){
				var thesis_var = "<?php echo $new_variable;?>";				
				$("#result-container").load("thesis.php?var=" + thesis_var );
				window.history.pushState ("thesis", "Thesis", "/smnp/result?var=" + thesis_var + "&sort=thesis");
			});
		});
		
		
		$(document).ready(function(){
			$("#post-results").click(function(){
				var post_var = "<?php echo $new_variable;?>";				
				$("#result-container").load("post.php?var=" + post_var );
				window.history.pushState ("post", "Post", "/smnp/result?var=" + post_var + "&sort=posts");
			});
		});
		
		$(document).ready(function(){
			$("#year_option").on('change', function(){
				var val = $(this).val();
					if(val=='from'){
					$.ajax({
						type: 'POST',
						url: 'php/show_other_year.php',
						data:
						{
							val : val,
						},
						cache: false,
						success: function(data){
							$("#second_year").html(data);
							$("#until").css("display", "block");
							$("#until").show();
						}
					});
					}
					else
					{
							$("#second_year").html("");
							$("#until").hide();
					}
				
			});
		});
		
		
	$(document).ready(function(){
			$("#year_option_2").on('change', function(){
				var val = $(this).val();
					if(val=='from'){
					$.ajax({
						type: 'POST',
						url: 'php/show_other_year.php',
						data:
						{
							val : val,
						},
						cache: false,
						success: function(data){
							$("#second_year_2").html(data);
							$("#until_2").css("display", "block");
							$("#until_2").show();
						}
					});
					}
					else
					{
							$("#second_year_2").html("");
							$("#until_2").hide();
					}
				
			});
		});
		
		
		$(document).ready(function(){
			$("#select_department").on('change', function(){
				var dept = $(this).val();
					if(dept){
					$.ajax({
						type: 'POST',
						url: 'php/get_course.php',
						data:
						{
							department: dept,
						},
						success: function(data){
							$("#select_course").show();
							$("#select_course").html(data);
						},
					});
					}
					else{
						$("#select_course").hide();
					}
				
				
			});
		});
		
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
		
		function notapproved(e){
			e.preventDefault();
		}
	</script>
	<style>
	 .student-result:hover{
		 color:blue;
		 text-decoration:underline;
	 }
	 select{
		 margin-bottom:5px;
		 margin-top: 5px;
	 }
	</style>
<body style="background: linear-gradient(to bottom, #F0F8FF 0%, #F5F5F5 100%);">
	<?php
		include_once ("header.php");
		
			if(isset($_GET['var'])){	
	?>
		<!--BANNER-->	<img src="image/banner.png" title="Laguna State Polytechnic University" alt="logo" style="margin-top:4px;" width="100%" height="100%" border="0"> <!--BANNER-->
	<div style="margin-left:90px;margin-top:50px;">
		<a href="#" id="student-results" title="Show result for Students" style="font-size:20px;background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%);color: black;border: 2px solid 	#DCDCDC; border-radius:4px;padding:5px;
						box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			Students</a><span style='font-size:25px;'> | </span>
		<a href="#" id="thesis-results" title="Show result for Thesis" style="font-size:20px;background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%);color: black;border: 2px solid 	#DCDCDC; border-radius:4px;padding:5px;
						box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			Thesis</a><span style='font-size:25px;'> | </span>
		<a href="#" id="post-results" title="Show result for Posts" style="font-size:20px;background: linear-gradient(to bottom, 	#F0FFFF 0%, #B0E0E6 100%);color: black;border: 2px solid 	#DCDCDC; border-radius:4px;padding:5px;
						box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			Posts</a>
	</div>
	<section id="blog" class="container" >
		<div class="blog">
			<div class="row">
				<div class="col-md-12" style="background-color:	white; border-radius:10px; height:800px; font-size:20px; padding-top:10px;overflow:hidden;overflow-y:scroll; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
				<center style=" font-color:red; padding-bottom:10px; text-shadow: 1px 1px 1px black;font-size:30px;">Search Results</center><hr>
					<div id="result-container">
				<?php
				if(!isset($_GET['sort'])){
					if($student_numrow>0){
						while($row = $search_student_query -> fetch (PDO::FETCH_ASSOC)){
							$fname = $row['fname'];
							$lname = $row['lname'];
							$sid = $row['stud_id'];							
						?>
						<a href="myprofile?user=<?php echo $sid;?>" class='student-result' style='display:block;'><?php echo $fname." ".$lname;?></a><br/>
				<?php
						}
					}
					else{
						echo "<center><span style='display:block;margin-bottom:20px; color:red;'>No Student Found</span></center>";
					}
					if($thesis_numrow>0){
						while($row = $search_thesis_query -> fetch (PDO::FETCH_ASSOC)){
							$thid = $row['thesis_id'];
							$title = $row['title'];
							$thesis_campus = $row['campus'];
							$thesis_dept = $row['department'];
							$thesis_course = $row['course'];
							$thesis_year = $row['year'];
							$thesis_course = $row['course'];
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
								<?php
								if($abstract!="" && $complete==""){
								?>
								<a href='php/<?php echo $filepath.$abstract;?>' target="_blank" style="color:red; font-size:13px;"><?php echo $title;?>a
								<?php
								}
								else if($complete!="" && ($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected') && $abstract==""){
								?>
								<a href='#' onclick='notapproved(event);' style="color:#000080;"><?php echo $title;?>b
								<?php
								}
								else if($complete!="" && $request_status=='Approved'){
								?>
								<a href='php/<?php echo $filepath.$complete;?>' target="_blank" style="color:#000080;"><?php echo $title;?>c
								
								<?php
								}
								else if($complete!="" && $abstract != "" &&($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected')){
								?>
								<a href='php/<?php echo $filepath.$abstract;?>' target="_blank" style="color:red;"><?php echo $title;?>d
								
								<?php
								}
								else if($complete!="" && $abstract != "" && $request_status == 'Approved'){
								?>
								<a href='php/<?php echo $filepath.$complete;?>' target="_blank" style="color:#000080;"><?php echo $title;?>e
								
								<?php
								}
								?>
						<span style='font-size:10px;'><?php
							if($abstract!="" && $complete==""){
							echo "(Abstract Only)";
							}
							else if($abstract=="" && $complete!=""){
							echo "(Complete Only)";	
							}
							else if($abstract!="" && $complete!=""){
							echo "(Both)";
							}
						
							if(($complete!="") && ($request_status=='Rejected' || $request_status == 'Pending' || $request_status=="")){
						?>
						<img src='image/extra/lock_icon.png' style='width:12px;height:12px;'>
						
							<?php
							}
							else if($numrow_check_unlock>0 && $request_status == 'Approved'){
							?>
						<img src='image/extra/unlock_icon.png' style='width:12px;height:12px;'>
						
							<?php
							}
							?>
						</span></a></label>		
						<span>
							<div style='font-size:10px;'>
								<span style='display:block;margin-top:-5px;'><?php echo $thesis_campus;?></span>
								<span style='display:block;margin-top:-5px;'><?php echo $thesis_dept;?></span>
								<span style='display:block;margin-top:-5px;'><?php echo $thesis_year;?></span>
							</div>
						<?php
						if($abstract != "" && ($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected')){
						?>
						<a href='php/<?php echo $filepath.$abstract;?>' style='margin-bottom:25px;' download title='Download Abstract'><img style='height:20px;width:20px;'src='image/extra/download_button.png' ></a>
						<?php
						}
						else if($complete!="" && $request_status == "Approved"){
						?>
						<a href='php/<?php echo $filepath.$complete;?>' style='margin-bottom:25px;' download title='Download Abstract'><img style='height:20px;width:20px;'src='image/extra/download_button.png' ></a>
						<?php	
						}
							
							if($complete!=""){
							?>
							<span class='request-container'>
								<?php
								if($request_status=="" || $request_status=="Rejected"){
								?>
							<a href='#' style='margin-bottom:25px;' title='Request Complete Access' class='request-access'id='thesis_<?php echo $thid;?>' onclick='request_thesis(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/request.png'></a>
								<?php
								}
								else if($request_status=='Pending'){
								?>
							<a href='#' style='margin-bottom:25px;' title='Waiting for Approval' class='cancel-request' id='cancel_request_<?php echo $thid;?>' onclick='cancel_request(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/waiting.png'></a>
								<?php
								}
								?>
							</span>
							<?php
							}
							?>
							
						</div>
						<?php
						}
						
					}
					else{
						echo "<center><span style='display:block; color:red;'>No Thesis Found</span></center>";
					}
				}
				//End of !isset	
				
				
				
				else if($sort=="students"){
					if($student_numrow>0){
						while($row = $search_student_query -> fetch (PDO::FETCH_ASSOC)){
							$fname = $row['fname'];
							$lname = $row['lname'];
							$sid = $row['stud_id'];							
						
						$check_if_friend = $db -> prepare ("SELECT frst_user,scnd_user,status FROM frnd_rqst WHERE (frst_user=:sid AND scnd_user=:getid) OR (frst_user=:getid AND scnd_user=:sid)");
						$check_if_friend -> bindParam (":sid", $sid);
						$check_if_friend -> bindParam (":getid", $getid);
						$check_if_friend -> execute();
						
						$check_result = $check_if_friend -> fetch();
						$check_numrow = $check_if_friend -> rowCount();
						if($check_numrow>0){
							$status = $check_result['status'];
						}
						else{
							$status = 0;
						}
						?>
						<a href="myprofile?user=<?php echo $sid;?>" class='student-result'><?php echo $fname." ".$lname;?></a><br/>
									<?php
						}
					}
					else{
						echo "<center style='color:red'>No Students Found</center>";
					}
				}
				else if($sort=="thesis"){
					?>
		<div style='width:300px;margin-left:20px;margin-bottom:30px;'>
			<form class='form-inline' action='php/filter_thesis.php' method='POST'>
				<input type='hidden' name='var' value='<?php echo $variable;?>'>
				<input type='hidden' name='sort' value='thesis'>
				<select class='form-control' name='campus' style='margin-bottom:15px;'>
						<option value=''>Select Campus</option>
					<?php
						$get_camp = $db -> prepare ("SELECT campus FROM campus_tbl");
						$get_camp -> execute();
						while($r_get_camp = $get_camp -> fetch(PDO::FETCH_ASSOC)){
						$campus = $r_get_camp['campus'];	
					?>
						<option value='<?php echo $campus;?>'><?php echo $campus;?></option>
					<?php
						}
					?>
				</select>
				<input type='text' name='department' value='<?php echo $rdepartment;?>' class='form-control' readonly required>
				<select name='course' class='form-control'>
					<option value=''>Select Course</option>
					<?php
						
						$get_course = $db -> prepare ("SELECT course_tbl.course as course FROM course_connect
						LEFT JOIN course_tbl
						ON course_tbl.course_id = course_connect.course_id
						WHERE course_connect.department_id = :did
						");
						$get_course -> bindParam (":did", $did);
						$get_course -> execute();
						while($r_get_course = $get_course -> fetch(PDO::FETCH_ASSOC)){
							$course = $r_get_course['course'];
					?>
						<option value='<?php echo $course;?>'><?php echo $course;?></option>
					<?php
						}
					?>	
				</select>
				<select id='year_option' class='form-control'>
					<option value='in'>In</option>
					<option value='from'>From</option>
				</select>
				<select name='year' class='form-control'>
					<option value=''>Select Year</option>
					<?php
						$curr_year = date("Y");
						$limit_year = $curr_year - 6;
						for($x = $curr_year;$x>=$limit_year;$x--){
					?>
						<option value='<?php echo $x;?>'><?php echo $x;?></option>
					<?php
						}
					?>
				</select>
				<span id='until' style='display:none;'>Until</span>
				
				<span id='second_year'>
				</span>
				
				<input type='submit' name='submit' value='Search' class='btn btn-info'>
				
			</form>
			</div>
					
					<?php
					if($thesis_numrow>0){
						while($row = $search_thesis_query -> fetch (PDO::FETCH_ASSOC)){
							$thid = $row['thesis_id'];
							$title = $row['title'];
							$thesis_campus = $row['campus'];
							$thesis_dept = $row['department'];
							$thesis_course = $row['course'];
							$thesis_year = $row['year'];
							$thesis_course = $row['course'];
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
						<div class='col-md-3' style='position:relative;border-style:solid;margin-bottom:10px;height:150px;margin-left:10px;'>
						<label>
								<?php
								if($abstract!="" && $complete==""){
								?>
								<a href='php/<?php echo $filepath.$abstract;?>' target="_blank"><?php echo $title;?>
								<?php
								}
								else if($complete!="" && ($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected') && $abstract==""){
								?>
								<a href='#' onclick='notapproved(event);'><?php echo $title;?>
								<?php
								}
								else if($complete!="" && $request_status=='Approved'){
								?>
								<a href='php/<?php echo $filepath.$complete;?>' target="_blank"><?php echo $title;?>
								
								<?php
								}
								else if($complete!="" && $abstract != "" &&($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected')){
								?>
								<a href='php/<?php echo $filepath.$abstract;?>' target="_blank"><?php echo $title;?>
								
								<?php
								}
								else if($complete!="" && $abstract != "" && $request_status == 'Approved'){
								?>
								<a href='php/<?php echo $filepath.$complete;?>' target="_blank"><?php echo $title;?>
								
								<?php
								}
								?>
						<span style='font-size:10px;'><?php
							if($abstract!="" && $complete==""){
							echo "(Abstract Only)";
							}
							else if($abstract=="" && $complete!=""){
							echo "(Complete Only)";	
							}
							else if($abstract!="" && $complete!=""){
							echo "(Both)";
							}
						
							if(($complete!="") && ($request_status=='Rejected' || $request_status == 'Pending' || $request_status=="")){
						?>
						<img src='image/extra/lock_icon.png' style='width:12px;height:12px;'>
						
							<?php
							}
							else if($numrow_check_unlock>0 && $request_status == 'Approved'){
							?>
						<img src='image/extra/unlock_icon.png' style='width:12px;height:12px;'>
						
							<?php
							}
							?>
						</span></a></label>		
						<span>
							<div style='font-size:10px;'>
								<span style='display:block;margin-top:-5px;'><?php echo $thesis_campus;?></span>
								<span style='display:block;margin-top:-5px;'><?php echo $thesis_dept;?></span>
								<span style='display:block;margin-top:-5px;'><?php echo $thesis_year;?></span>
							</div>
						<?php
						if($abstract != "" && ($request_status == "" || $request_status == 'Pending' || $request_status == 'Rejected')){
						?>
						<a href='php/<?php echo $filepath.$abstract;?>' style='margin-bottom:25px;' download title='Download Abstract'><img style='height:20px;width:20px;'src='image/extra/download_button.png' ></a>
						<?php
						}
						else if($complete!="" && $request_status == "Approved"){
						?>
						<a href='php/<?php echo $filepath.$complete;?>' style='margin-bottom:25px;' download title='Download Abstract'><img style='height:20px;width:20px;'src='image/extra/download_button.png' ></a>
						<?php	
						}
							
							if($complete!=""){
							?>
							<span class='request-container'>
								<?php
								if($request_status=="" || $request_status=="Rejected"){
								?>
							<a href='#' style='margin-bottom:25px;' title='Request Complete Access' class='request-access' id='thesis_<?php echo $thid;?>' onclick='request_thesis(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/request.png'></a>
								<?php
								}
								else if($request_status=='Pending'){
								?>
							<a href='#' style='margin-bottom:25px;' title='Waiting for Approval' class='cancel-request' id='cancel_request_<?php echo $thid;?>' onclick='cancel_request(this,event);'><img style='margin-left:7px;height:20px;width:20px;'src='image/extra/waiting.png'></a>
								<?php
								}
								?>
							</span>
							<?php
							}
							?>
							
						</div>
						<?php
							}
						?>
				<?php
					}
					else{
						echo "<center style='color:red;'>No Thesis Found</center>";
					}
				}
				//End of thesis
				else if($sort=='posts'){
				?>
				
			<div style='width:300px;margin-left:20px;'>
				<form class='form-inline' action='php/filter_newsfeed_search_result.php' method='POST' style='margin-bottom:15px;'>
				<input type='hidden' name='var' value='<?php echo $variable;?>'>
				<input type='hidden' name='sort' value='posts'>
				<select class='form-control' name='campus' style='margin-bottom:15px;'>
						<option value=''>Select Campus</option>
					<?php
						$get_camp = $db -> prepare ("SELECT campus FROM campus_tbl");
						$get_camp -> execute();
						while($r_get_camp = $get_camp -> fetch(PDO::FETCH_ASSOC)){
						$campus = $r_get_camp['campus'];	
					?>
						<option value='<?php echo $campus;?>'><?php echo $campus;?></option>
					<?php
						}
					?>
				</select>
			
				<select name='department' id='select_department' class='form-control' required>
					<option value=''>Select Department</option>
						
					<?php
						$get_dept = $db -> prepare ("SELECT department FROM department_tbl");
						$get_dept -> execute();
						while($r_get_dept = $get_dept -> fetch(PDO::FETCH_ASSOC)){
							$p_department = $r_get_dept['department'];
					?>
					<option value='<?php echo $p_department;?>'><?php echo $p_department;?></option>
					<?php
						}
					?>
				</select>
				<span id='select_course' style='display:block;'>
				</span>
				
				<select id='year_option_2' class='form-control'>
					<option value='in'>In</option>
					<option value='from'>From</option>
				</select>
				<select name='year' class='form-control'>
					<option value=''>Select Year</option>
					<?php
						$curr_year = date("Y");
						$limit_year = $curr_year - 6;
						for($x = $curr_year;$x>=$limit_year;$x--){
					?>
						<option value='<?php echo $x;?>'><?php echo $x;?></option>
					<?php
						}
					?>
				</select>
				<span id='until_2' style='display:none;'>Until</span>
				
				<span id='second_year_2'>
				</span>
				
				<input type='submit' name='submit' value='Filter Search' class='btn btn-info' style='display:block;'>
			</form>
				<a href='result.php?var=<?php echo $variable;?>&sort=posts' style='margin-top:10px;margin-bottom:13px;'><button class='btn btn-info'>No Filter</button></a>
			</div>
					<?php
					if($post_numrow>0){
					?>
						<div class='col-md-12' style='margin-top:14px;'>
					<?php
						while($post_row = $search_posts_query -> fetch (PDO::FETCH_ASSOC)){
						$post_title = $post_row['title'];
						$post_nid = $post_row['news_id'];
						$post_description = $post_row['description'];
						$nid = $post_row['news_id'];
							$get_pname = $db -> prepare 
								("
								SELECt stud_bas.fname as fname, stud_bas.lname as lname 
								FROM post_connect 
								LEFT JOIN stud_bas
								ON stud_bas.stud_id = post_connect.stud_id
								WHERE post_connect.news_id = :nid
								");
							$get_pname -> bindParam (":nid", $nid);
							$get_pname -> execute();
							$res_pname = $get_pname -> fetch();
							$post_fname = $res_pname['fname'];
							$post_lname = $res_pname['lname'];
							
						?>
						
						<div class='col-md-7' style='border-style:solid;display:block;'>
						<span>
							<a href='newsfeed.php?research=<?php echo $nid;?>'><?php limit_length($post_title, 80);?></a>
						</span>
						<span style='font-size:12px;'>
						</span>
						</div>
						
						<?php
						}
						?>
						</div>
					<?php
					}
					else{
				
						echo "<center><span style='color:red;'>No Posts Found</span></center>";
				
					}
					?>
		
		
				<?php
				}
			}
			else{
				include_once ("404.php");
			}
					?>		
					</div>
				</div>
			</div><!--/.blog-->
		</div>
    </section><!--/#blog	-->


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