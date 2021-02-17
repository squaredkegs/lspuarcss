<?php

	include_once ("connection.php");
	include_once ("queryadmindata.php");
	if($_POST['aid']){
		$query = $db -> prepare ("SELECT stud_id FROM stud_bas WHERE status='Pending' AND campus= :campus AND department = :dept");
		$query -> bindParam (":campus", $rcampus);
		$query -> bindParam (":dept", $rdepartment);
		$query -> execute();
		$numrow = $query -> rowCount();
		
		$rqst_qry = $db -> prepare ("SELECT request_id FROM request_thesis WHERE status='Pending' AND campus= :campus AND department = :dept");
		$rqst_qry -> bindParam (":campus", $rcampus);
		$rqst_qry -> bindParam (":dept", $rdepartment);
		$rqst_qry -> execute();
		$rqst_num = $rqst_qry -> rowCount();
		$total_numrow = $numrow + $rqst_num;
		if($total_numrow>0)
		{
			echo $total_numrow;
		}

	}	
?>