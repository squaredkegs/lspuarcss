<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	include_once ("adminfunction.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if($_POST['delete_thesis_btn']){
		$thid = $_POST['thid'];
		$get_filename = $db -> prepare ("SELECT abstract_filename, complete_filename,filepath FROM thesis_arch WHERE thesis_id = :thid");
		$get_filename -> bindParam (":thid", $thid);
		$get_filename -> execute();
		$res_fn = $get_filename -> fetch();
		$abs = $res_fn['abstract_filename'];
		$com = $res_fn['complete_filename'];
		$path = "../../thesis/";
		if($abs!=""){	
			if(file_exists($path.$abs)){
				unlink($path.$abs);
			}
		}
		if($com!=""){
			if(file_exists($path.$com)){
				unlink($path.$com);
			}
		}
		$delete = $db -> prepare ("DELETE FROM thesis_arch WHERE thesis_id = :thid");
		$delete -> bindParam (":thid", $thid);
		$delete -> execute();
		if($delete){
			$update = $db -> prepare ("UPDATE request_thesis
				INNER JOIN request_thesis_connect
				ON request_thesis.request_id = request_thesis_connect.request_id
				INNER JOIN thesis_arch
				ON thesis_arch.thesis_id = request_thesis_connect.thesis_id
				SET request_thesis.status = 'Deleted' 
				WHERE thesis_arch.thesis_id = :thid");
			$update -> bindParam (":thid", $thid);
			$update -> execute();
			echo 
			"
			<script>
			alert('Deleted');
			window.location.href='../thesis_archive.php';
			</script>
			";
		}
		else{
				echo 
			"
			<script>
			alert('Error! Try Again Later');
			window.location.href='../thesis_archive.php';
			</script>
			";
		
		}
	}

?>