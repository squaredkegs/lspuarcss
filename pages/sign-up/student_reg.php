<?php
	
	include_once ("../../php/connection.php");
	
?>
<script type="text/javascript">
		//fname
		$(document).ready(function(){
			$('#firststat').load('php/signcheck.php').show();
			$('#fname').keyup(function(){
				
				$.post('php/signcheck.php', { fname:  form.fname.value }, 
				function(result){
					$('#firststat').html(result).show();
				});
			});
			
		});
		
		//lname

		$(document).ready(function(){
			$('#laststat').load('php/signcheck.php').show();
			$('#lname').keyup(function(){
				
				$.post('php/signcheck.php', { lname:  form.lname.value }, 
				function(result){
					$('#laststat').html(result).show();
				});
			});
			
		});
		

		
		$(document).ready(function(){
			$('#emailstat').load('php/signcheck.php').show();
			$('#email').keyup(function(){
				$.post('php/signcheck.php', { email:  form.email.value }, 
				function(result){
					$('#emailstat').html(result).show();
				});
			});
			
		});
		
		$(document).ready(function(){
			$("#password").keyup(function(){
				if($(this).val().length <= 0)
				{
				$.post('php/signcheck.php', 	{ password: form.password.value },
					function(result){
						$('#passwordstat').html(result).show();
					});
				}
				else if($(this	).val().length>=7){
					$("#passwordstat").css("color", "green");
					$("#passwordstat").html("")
				}
				else
				{
					$("#passwordstat").css("color","red");
					$("#passwordstat").html("Password is too short!");
				}
			});
		});

		$(document).ready(function(){
			$('#studentstat').load('php/signcheck.php').show();
			$('#studentid').keyup(function(){
				$.post('php/signcheck.php', { studentid: form.studentid.value },
				function (result){
					$('#studentstat').html(result).show();
				});
			});
		});
		$(document).ready(function(){
			var num = 0;
			var pass = document.getElementById('password');
			$("#showpass").click(function(){
				if(num==0){
					num = 1;
					pass.type = 'text';
				}
				else{
					num = 0;
					pass.type = 'password';
				}
			});
		});




</script>

	<div class="col-md-10 col-md-offset-1" id="student_reg">
					<center><label class="maintitle logform">Student Sign up</label>
					</center>
					<form action="php/register_exec.php" method="POST" class="form-group"
						name="form" id="form_id">
				<div class='field'>	
					<div class="col-md-6">
						Firstname <span class="checkerr" id="firststat"></span>
						<input type="text" name="fname" id="fname" class="form-control logform sign" id="user_input" style="border: 1px solid #C0C0C0;"  required>
						Lastname <span id="laststat" class="checkerr"></span>
						<input type="text" name="lname" id="lname" class="form-control logform sign" style="border: 1px solid 	#C0C0C0;" required>						
						E-mail 	<span id="emailstat" class="checkerr"></span>
						<input type="email" name="email" id="email" class="form-control logform sign" style="border: 1px solid 	#C0C0C0;" required>
						<div style="position:relative;padding:0;margin:0;">
						Password  

						<span id="passwordstat" class="checkerr passworderr passwordmatch" style="float:right;"></span> 
						<input type="password" name="password" id="password" class="form-control logform sign" style="border: 1px solid	#C0C0C0;" required>
						
						 <img src="image/extra/eye.png" id="showpass" style="height:27px;width:20px;cursor:pointer;position:absolute;bottom:8px;right:10px;">
					</div>						
						
					</div>
					<div class="col-md-6">
					
						Student I.D
						<span id="studentstat" class="checkerr" style="float:right;"></span> 
						<input type="text" name="studentid" id="studentid" class="form-control logform sign" style="border: 1px solid	#C0C0C0;">
					
						Campus
							<select name="campus" id="camp" class="form-control logform sign" style="border: 1px solid	#C0C0C0;" required>
								<option value="">Select Campus</option>
								<option value="Sta. Cruz">Sta. Cruz</option>
								<option value="Los Baños">Los Baños</option>
								<option value="Siniloan">Siniloan</option>
								<option value="San Pablo">San Pablo</option>
							</select>
						Department
								<?php
									$query = $db -> prepare("SELECT * FROM department_tbl ORDER BY department ASC");
									$query -> execute();
									
								?>
							<select name="department" id="dept" class="form-control logform sign" style="border: 1px solid	#C0C0C0;" required>
								<option value="">Select Department</option>
								<?php
									while($row=$query -> fetch(PDO::FETCH_ASSOC)){
									$dept = $row['department'];
								?>
									<option value="<?php echo $dept;?>"><?php echo $dept;?></option>
								<?php
									}
								?>
						
							</select>							
					<input type="submit" name="submit" style="margin-top:22px;"
					class="btn btn-info form-control" id="test_submit" value="Register">
					

					</div>
				</div>
					</form>
				
				</div>