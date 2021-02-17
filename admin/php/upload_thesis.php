<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if($_POST['submit_thesis']){
		$date = date("Y-m-d H:i:s");
		$title = $_POST['title'];
		$year = $_POST['year'];
		$campus = $rcampus;
		
		$dept = $rdepartment;
		$course = $_POST['course'];
		$thid = createUniqueId('thesis_id', 'thesis_arch');
		$name_id = createUniqueId('abstract_filename', 'thesis_arch');
		$thesis_name = $_FILES['abstract_thesis']['name'];
		$temp = explode (".", $_FILES['abstract_thesis']['name']);
		$thesis_file = $_FILES['abstract_thesis']['tmp_name'];
		$filetype = end ($temp);
		$abstract_thesis_name = "";
		$complete_thesis_name = "";
		$thesis_to_be_uploaded = "";
		$addstr =  createUniqueId('abstract_filename','thesis_arch');
		$cut_name = strrpos($thesis_name, ".");
		$raw_name = substr($thesis_name, 0, $cut_name);
		$real_name = $raw_name.$addstr.".".$filetype;
		echo $real_name."<br>";
		/*if($type=='Abstract'){
		$abstract_thesis_name = $real_name;
		$thesis_to_be_uploaded = $abstract_thesis_name;
		}
		else{
		$complete_thesis_name = $real_name;	
		$thesis_to_be_uploaded = $complete_thesis_name;
		}
		$dummy_filepath = "../../thesis/";
		$filepath = "../thesis/";
		*/
		
		/*
				if(!is_dir($filepath)){
					mkdir("../thesis/", 0777, true);
				}
				

			if($_FILES['thesis']['error'] != UPLOAD_ERR_OK){
				die("Upload Failed with error code". $_FILES['thesis']['error']);
			}
			
		
				move_uploaded_file($thesis_file,$dummy_filepath.$thesis_to_be_uploaded);
		
			$insert_thesis = $db -> prepare ("
				INSERT INTO thesis_arch (thesis_id,title,filetype,abstract_filename,complete_filename,filepath,campus,upload_date,year,department,course,type) VALUES(:thid,:title,:filetype,:abstract_filename,:complete_filename,:filepath,:camp,:date,:year,:dept,:course,:type)");
			$insert_thesis -> execute(array(
						"thid" => $thid,
						"title" => $title,
						"filetype" => $filetype,
						"abstract_filename" => $abstract_thesis_name,
						"complete_filename" => $complete_thesis_name,
						"filepath" => $filepath,
						"camp" => $campus,
						"date" => $date,
						"year" => $year,
						"dept" => $dept,
						"course" => $course,
						"type" => $type
						));	
			$insert_isam_thesis = $db2 -> prepare ("INSERT INTO isam_thesis_arch (thesis_id,title,datetime,campus,department,course,year) VALUES (:thid,:title,:date,:camp,:dept,:course,:year)");
			$insert_isam_thesis -> execute(array(
						"thid" => $thid,
						"title" => $title,
						"date" => $date,
						"camp" => $campus,
						"dept" => $dept,
						"course" => $course,
						"year" => $year
						));
			$insert_isam_thesis -> execute();			
						
						if($insert_thesis && $insert_isam_thesis){
							echo 
							"
							<script>
							alert('Uploaded');
							</script>
							";
						}
						else{
							echo 
							"
							<script>
							alert('Error');
							</script>
							";
						}
						
							echo 
								"
								<script>
								window.location.href='../upload_thesis.php';
								</script>
								";
						
						
		*/
	}

?>