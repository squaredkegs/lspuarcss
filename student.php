<?php

	include_once ("php/connection.php");
	include_once ("php/function.php");
	include_once ("php/querydata.php");
	$check_user_login = checkLogIn();
	if(isset($_GET['var'])){
		$variable = $_GET['var'];
		$sort = "";
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
		}
		else if($count==2){
			$first_variable = $variable_to_array[0];
			$second_variable = $variable_to_array[1];
			$student_variable = "+".$first_variable." +".$second_variable;
		}
		else if($count==3){
			$first_variable = $variable_to_array[0];
			$second_variable = $variable_to_array[1];
			$third_variable = $variable_to_array[2];		
			$student_variable = "+(".$first_variable." ".$second_variable.")"." +".$third_variable;
		}
		else if($count>=4){
			$student_variable = "+".$echo_array;
		}
	}
	else {
		$variable = "Not Found";
	}

?>

<html>
<head>
</head>
<body>
		<div id="student">
			<?php
					$search_student_query = $isam_db -> prepare ("SELECT fname,lname,stud_id FROM isam_stud_bas WHERE MATCH 	(fname,lname) AGAINST (:variable IN BOOLEAN MODE);");
					$search_student_query -> bindValue (":variable",$student_variable, PDO::PARAM_STR);	
					$search_student_query -> execute();
					$student_numrow = $search_student_query -> rowCount();
						if($student_numrow>0){
							while($row = $search_student_query -> fetch (PDO::FETCH_ASSOC)){
						$fname = $row['fname'];
						$lname = $row['lname'];
						$sid = $row['stud_id'];	
			?>

			<a href="myprofile?user=<?php echo $sid;?>" class='student-result'><?php echo $fname." ".$lname;?></a><br/>
			
			<?php
							}
						}
						else{
							echo "<center style='color:red'>No Students Found</center>";
						}
			
			?>
			
		</div>
</body>
</html>