	<script>
			var renewpass = $("#renew_pass").val();
			var pass = $("#password").val();	
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
				else if($(this).val().length<=7)
				{
					$("#passwordstat").css("color","red");
					$("#passwordstat").html("Password is too short!");
				}
				
			});
		});

	</script>
	


<div style="margin-top:45px;">
				<center>
					<?php
						$err = "ghjgreethggfdgfd" ;
						if(isset($_GET['err'])){
							$err = $_GET['err'];
							if($err=="wrongpass")
							{
					?>
							<span style="color:red;font-size:20px;margin-bottom:">Old Password is wrong</span>
			<?php
							}
							else if($err=="success"){
			?>
			
							<span style="color:green;font-size:20px;margin-bottom:">Password Successfully changed</span>
			<?php
							}
							else if($err=="notmatch"){
								
			?>
							<span style="color:red;font-size:20px;margin-bottom:">New password don't match</span>
			
			<?php
							}
							else if($err=="tooshort"){
			?>
							<span style="color:red;font-size:20px;margin-bottom:">Password is Too Short</span>

			<?php
							}
						}
					?>
				<form style="width:250px;margin-top:20px;" action="php/change_password.php" method="POST" class="form-inline" name="form">
					<label>Old Password</label>
					<input required style="margin-bottom:10px;" type="password" name="old_pass" class="form-control" placeholder="Old Password" required>
					<label>New Password</label>
					<input required style="margin-bottom:20px;"type="password" name="new_pass"  class="form-control" id="password" placeholder="New Password">
					<label style="display:block;">Re-type New Password</label>
					<input required style="margin-bottom:18px;"type="password" name="renew_pass"  class="form-control" placeholder="Re-type Password" id="renew_pass">
					<span style="font-size:13px;display:block;" id="passwordstat"></span>
					<input required type="submit" name="submit" class="btn btn-info form-control">
				</form>
				</center>
</div>
