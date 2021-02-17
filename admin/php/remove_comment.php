<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['remove_comment'])){
		$cid = $_POST['cid'];
		$nid = $_POST['nid'];
		$check_child = $db -> prepare ("call comment_procedure (:cid)");
		$check_child -> bindParam (":cid", $cid);
		$check_child -> execute();
		$pid = $check_child	 -> rowCount();
		
		$check_child -> closeCursor();
		if($pid>0){
				$query = $db -> prepare ("UPDATE cmmt_sect SET content='[Removed By Admin]', type='Removed' WHERE cmmt_id=:cid");
				$query -> bindParam (":cid", $cid);
				$query -> execute();
				}
				else{
					$query = $db -> prepare ("DELETE FROM cmmt_sect WHERE cmmt_id=:cid");
					$query -> bindParam (":cid", $cid);
					$query -> execute();
				
				}
				echo 
					"
					<script>
					alert('Removed');
					window.location.href='../admin_comment.php?cmmntdetail=$nid';
					</script>
					";
	}

?>