
<?php
	include_once ("connection.php");
	include_once ("queryadmindata.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['remove_post'])){
		$datetime = date("Y-m-d H:i:s");
		$nid = $_POST['nid'];
		$query = $db -> prepare("
								START TRANSACTION;
								UPDATE newsfeed SET status=:status WHERE news_id = :nid;
								INSERT INTO admin_activity (admin_id,object_id,activity,stat_date,object)
								VALUES (:aid,:oid,:activity,:date,:object);
								COMMIT;
								");
		$query -> execute(array(
							"status" => "Remove by Admin",
							"nid" => $nid,
							"aid" => $aid,
							"oid" => $nid,
							"activity" => "Remove Post",
							"date" => $datetime,
							"object" => "Post"
							));					
					if($query){
						header("location:../admin_newsfeed.php");
					}
					else{
						echo 	"
								<script>
								alert('Database Error!');
								window.location.href='../admin_newsfeed.php';
								</script>
								";
					}
	}
	else{
		echo 
				"
				<script>
				alert('Error!');
				window.location.href='../admin_newsfeed.php';
				</script>
				";
		die();
		
	}


?>