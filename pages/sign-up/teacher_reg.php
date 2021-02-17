<?php
	if(file_exists("php/connection")){
		include ("php/connection.php");
	}
	else if(file_exists("../php/connection")){
		include ("../php/connection.php");
	}
	else if(file_exists("../../php/connection")){
		include ("../../php/connection.php");
	}
	?>

<script type="text/javascript">
//For Teacher
		$(document).ready(function(){
			$('#t_firststat').load('php/signcheck.php').show();
			$('#t_fname').keyup(function(){
				
				$.post('php/signcheck.php', { fname:  t_form.fname.value }, 
				function(result){
					$('#t_firststat').html(result).show();
				});
			});
			
		});
		
		//lname

		$(document).ready(function(){
			$('#t_laststat').load('php/signcheck.php').show();
			$('#t_lname').keyup(function(){
				
				$.post('php/signcheck.php', { lname:  t_form.lname.value }, 
				function(result){
					$('#t_laststat').html(result).show();
				});
			});
			
		});
		

		
		$(document).ready(function(){
			$('#t_emailstat').load('php/signcheck.php').show();
			$('#t_email').keyup(function(){
				$.post('php/signcheck.php', { email:  t_form.email.value }, 
				function(result){
					$('#t_emailstat').html(result).show();
				});
			});
			
		});
		
		$(document).ready(function(){
			$("#t_password").keyup(function(){
				if($(this).val().length <= 0)
				{
				$.post('php/signcheck.php', 	{ password: t_form.password.value },
					function(result){
						$('#t_passwordstat').html(result).show();
					});
				}
				else if($(this	).val().length>=7){
					$("#t_passwordstat").css("color", "green");
					$("#t_passwordstat").html("")
				}
				else
				{
					$("#t_passwordstat").css("color","red");
					$("#t_passwordstat").html("Password is too short!");
				}
			});
		});

		$(document).ready(function(){
			$('#t_studentstat').load('php/signcheck.php').show();
			$('#t_studentid').keyup(function(){
				$.post('php/signcheck.php', { studentid: t_form.studentid.value },
				function (result){
					$('#t_studentstat').html(result).show();
				});
			});
		});
		$(document).ready(function(){
			var num = 0;
			var pass = document.getElementById('t_password');
			$("#t_showpass").click(function(){
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


				<div class="col-md-10 col-md-offset-1" id="teacher_reg">
					<center><label class="maintitle logform">Teacher Sign up</label></center>
					<form action="php/register_exec.php" method="POST" class="form-group"
						name="t_form" id="form_id">
				<div class='field'>	
					<div class="col-md-6">
						Firstname <span id="t_firststat" class="checkerr"></span>
						<input type="text" name="fname" id="t_fname" class="form-control logform sign" id="user_input" style="border: 1px solid	#C0C0C0;" required>
						Lastname <span id="t_laststat" class="checkerr"></span>
						<input type="text" name="lname" id="t_lname" class="form-control logform sign" style="border: 1px solid	#C0C0C0;" required>						
						E-mail 	<span id="t_emailstat" class="checkerr"></span>
						<input type="email" name="email" id="t_email" class="form-control logform sign" style="border: 1px solid	#C0C0C0;" required>
					
					<div style="position:relative;padding:0;margin:0;">
						Password  

						<span id="t_passwordstat" class="checkerr passworderr passwordmatch" style="float:right;"></span> 
						<input type="password" name="password" id="t_password" class="form-control logform sign" style="border: 1px solid	#C0C0C0;" required>
						
						 <img src="image/extra/eye.png" id="t_showpass" style="height:27px;width:20px;cursor:pointer;position:absolute;bottom:8px;right:10px;">
					</div>						
				
					</div>
					<div class="col-md-6">
						
					
						Teacher I.D
						<span id="t_studentstat" class="checkerr" style="float:right;"></span> 
						<input type="text" name="studentid" id="t_studentid" class="form-control logform sign" style="border: 1px solid	#C0C0C0;">
					
						Campus
							<select name="campus" id="t_camp" class="form-control logform sign" style="border: 1px solid	#C0C0C0;" required>
								<option value="">Select Campus</option>
								<option value="Sta. Cruz">Sta. Cruz</option>
								<option value="Los Baños">Los Baños</option>
								<option value="Siniloan">Siniloan</option>
								<option value="San Pablo">San Pablo</option>
							</select>

						Department
							<select name="department" id="t_dept" class="form-control logform sign" style="border: 1px solid	#C0C0C0;" required>
								<option value="">Select Department</option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
								<option value=""></option>
							</select>							
					<input type="submit" name="t_submit" style="margin-top:22px;" 
					class="btn form-control btn-info" value="Register">
					
					</div>
					</div>
					
				</div>
					
					</form>
					
				</div>
