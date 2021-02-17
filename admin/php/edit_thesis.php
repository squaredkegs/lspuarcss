<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if($_POST['save_edit_thesis']){
		$date = date("Y-m-d H:i:s");
		$title = $_POST['title'];
		$year = $_POST['year'];
		$type = $_POST['type'];
		$thid = $_POST['thid'];
		$course = $_POST['course'];
		$new_name = createUniqueId('abstract_filename','thesis_arch');
		$complete_filename = $_POST['complete_filename'];
		$abstract_filename = $_POST['abstract_filename'];
		$type = $_POST['type'];
		$action = $_POST['action'];
		$filepath = "../thesis/";
		$dummy_path = "../../thesis/";
		$filename = $_FILES['thesis_file']['name'];
		$temp = explode (".", $_FILES['thesis_file']['name']);
		
		$thesis_file = $_FILES['thesis_file']['tmp_name'];
		$filetype = end ($temp);
		$thesis_to_be_uploaded = "";
		if($action=='Change Abstract'){
			$abstract_filename = $filename.$new_name.".".$filetype;
			$new_type = 'Abstract';
			$thesis_to_be_uploaded = $abstract_filename;
		}
		else if($action=='Change Complete'){
			$complete_filename = $filename.$new_name.".".$filetype;
			$new_type = 'Thesis';
			$thesis_to_be_uploaded = $complete_filename;
		}
		else if($action=='Upload Complete'){
			$complete_filename = $filename.$new_name.".".$filetype;
			$new_type = 'Both';
			$thesis_to_be_uploaded = $complete_filename;
		}
		else if($action=='Upload Abstract'){
			$abstract_filename = $filename.$new_name.".".$filetype;
			$new_type = 'Both';
			$thesis_to_be_uploaded = $abstract_filename;
		}
				

			if($_FILES['thesis_file']['error'] != UPLOAD_ERR_OK){
				die("Upload Failed with error code". $_FILES['thesis_file']['error']);
			}
			
			
				move_uploaded_file($thesis_file,$dummy_path.$thesis_to_be_uploaded);
		
						$change = $db -> prepare ("UPDATE thesis_arch SET abstract_filename = :abstract, 
						complete_filename = :complete,
						title = :title,
						type = :type,
						course = :course,
						year = :year 
						WHERE thesis_id = :thid 
						");
						$change -> execute(array(
								"abstract" => $abstract_filename,
								"complete" => $complete_filename,
								"title" => $title,
								"type" => $new_type,
								"course" => $course,
								"year" => $year,
								"thid" => $thid
								));
							if($change){
							echo 
								"
								<script>
							alert('Successfully Updated');	window.location.href='../view_thesis.php?thid=$thid';
								</script>
								";
							}
							else{
							echo 
								"
								<script>
								alert('Error!');	window.location.href='../thesis_archive.php';
								</script>
								";
								
							}
						
	}

?>