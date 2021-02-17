<?php

	include_once ("connection.php");
	$_SESSION['LAST_ACTIVITY'] = time();
	if(isset($_POST['submit_filter'])){
		$word = $_POST['new_filter'];
		$check = $db -> prepare ("SELECT filter_word FROM filter_tbl WHERE filter_word = :fword");
		$check -> bindParam (":fword", $word);
		$check -> execute();
		$numrow = $check -> rowCount();
		if($numrow==0){
			$query = $db -> prepare ("INSERT INTO filter_tbl (filter_word) VALUES (:fword)");
			$query -> bindParam(":fword", $word);
			$query -> execute();
			if($query){
					echo 
						"
						<script>
						alert('Word Added!');
						window.location.href='../filterwords.php';
						</script>
						";
				
			}
		}
		else{
		echo 
			"
			<script>
			alert('Word Already Filtered!');
			window.location.href='../filterwords.php';
			</script>
			";
			
		}
		
	}
	else{
		
		echo 
			"
			<script>
			alert('Error!');
			window.location.href='../filterwords.php';
			</script>
			";
	}
?>