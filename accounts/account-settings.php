<?php

	include_once ("../php/connection.php");
	include_once ("../php/querydata.php");
	
?>
<div style="margin-top:45px;">
				
				<center>
				<?php
					if(isset($_GET['chngml'])){
						$status = $_GET['chngml'];
						if($status=="success"){
						?>
						<label style='color:green;'>Email Successfully Change</label>
						<?php
						}
						else if($status=="exist"){
						?>
						<label style='color:red;'>Email Already Exists
						</label>	
						<?php
						}
					}
				?>
				<form action="php/change_email.php" method='post' class='form-group'>
				<label>Old Email</label>
				<input type='input' readonly name='old_email' value="<?php echo $remail?>" required class='form-control' style='margin-bottom:14px;'>
				<input type='email' name='new_email' required class='form-control' style='margin-bottom:14px;'>
				<input type='submit' name='change_email' value='Change Email' class='btn btn-info form-control'>
				
				</form>
				</center>
</div>
