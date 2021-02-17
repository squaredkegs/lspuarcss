 <?php
 	
	include_once ("connection.php");
	include_once ("queryadmindata.php");
	
	$query = $db -> prepare ("
						SELECT stud_id FROM stud_bas 
						WHERE status='Pending' 
						AND campus= :campus 
						AND department = :dept");
		$query -> bindParam (":campus", $rcampus);
		$query -> bindParam (":dept", $rdepartment);
		$query -> execute();
		$numrow = $query -> rowCount();
		
	$check_thesis_request = $db -> prepare ("
						SELECT request_id 
						FROM request_thesis 
						WHERE campus = :campus
						AND department = :dept
						AND status='Pending'");
	$check_thesis_request -> bindParam (":campus", $rcampus);
	$check_thesis_request -> bindParam (":dept", $rdepartment);
	$check_thesis_request -> execute();
	$request_numrow = $check_thesis_request -> rowCount();
	
 ?>
	<li>
		<ul class="menu">
			<li>
				<a href='pending'>
					<i class="fa fa-users text-aqua"></i> 
					<?php
					if($numrow>0){
					?>
					<span>You have <?php echo $numrow;?> pending registration</span>
					<?php
					}
					else{
					?>
					<span>No Pending Notification</span>
					<?php
					}
					?>
				</a>
			</li>
		</ul>
	</li>
	
	<li>
		<ul class="menu">
			<li>
				<a href='pending_thesis'>
					<i class="fa fa-users text-aqua"></i> 
					<?php
					if($numrow>0){
					?>
					<span>You have <?php echo $request_numrow;?> requests for thesis acess</span>
					<?php
					}
					else{
					?>
					<span>No Thesis Access</span>
					<?php
					}
					?>
				</a>
			</li>
		</ul>
	</li>
                 