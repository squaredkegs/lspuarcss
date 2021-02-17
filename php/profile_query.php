<?php
	if(file_exists("php/connection.php")){		
		include_once ("php/connection.php");
		include_once ("php/querydata.php");
		
		}
	if(file_exists("../php/connection.php")){
		include_once ("../php/querydata.php");
	}
	//$userid = checkLogIn();
	if(isset($_GET['user']))
	{
		$user_id = $_GET['user'];
		$query = $db -> prepare ("SELECT * FROM stud_bas WHERE stud_id=:sid");
		$query -> bindParam (":sid", $user_id);
		$query -> execute();
		$profile_num_row = $query -> rowCount();
		if($getid!=$user_id){
			$profile_result = $query -> fetch();
			$profile_fname = $profile_result['fname'];
			$profile_lname = $profile_result['lname'];
			$profile_camp = $profile_result['campus'];
			$profile_dept = $profile_result['department'];
			$profile_email = $profile_result['email'];
			$profile_gender = $profile_result['gender'];
			$profile_course = $profile_result['course'];
		$info_query = $db -> prepare ("SELECT * FROM stud_info WHERE stud_id=:sid");
		$info_query -> bindParam (":sid", $user_id);
		$info_query -> execute();
			$info_query_result = $info_query -> fetch();
			$profile_hometown = $info_query_result['hometown'];
			$profile_highschool = $info_query_result['hsschol'];
			$profile_elementary = $info_query_result['elemschol'];
			$profile_about_me = $info_query_result['aboutme'];
			$profile_picture_name = $info_query_result['picture_name'];
			$profile_picture_path = $info_query_result['picture_path'];
			$profile_type = $info_query_result['picture_type'];
		
		}
			
	}
	else if(!isset($_GET['user'])){
		$user_id = $getid;
	}
?>