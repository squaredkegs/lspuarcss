<?php
		if(file_exists("php/connection.php")){
		include("php/connection.php");
		include("php/querydata.php");
		include_once ("php/profile_query.php");
		
		}
		else if(file_exists("../php/connection.php")){
		include("../php/connection.php");
		include("../php/querydata.php");
		include_once ("../php/profile_query.php");
		include_once ("../php/function.php");
		}
		
?>

<head>
	<script type="text/javascript">
	
	var user_id = "<?php echo $user_id;?>";
	var getid = "<?php echo $getid;?>";
	
	</script>
	<script type="text/javascript">

		if(getid==user_id){
					$(document).ready(function(){
					$('.edit_link5').click(function(e){
					e.preventDefault();	
					for(var i=1;i<=5;i++){
					$('.text_wrapper' + i).hide();
					$('.edit_link5').hide();
						var content1 = $('.text_wrapper' + i).html();	
						$('.edit' + i).show();
						$('.editbox' + i).html(content1);
					}
						$('#save_edit').click(function(){
						for(var a=1;a<=5;a++){			
							$('.edit' + a).hide();
						}
							var newcontent1 = $('#select_camp').val();
							var newcontent2 = $('#select_dept').val();
							var newcontent3 = $('#select_course').val();
							var newcontent4 = $('.editbox4').val();
							var newcontent5 = $('#select_gender').val();
							var save_button = $('#save_edit').val();
								$.ajax({
									type: 'POST',
									url: 'php/update_profile.php',
									data:
									{
									
										save: save_button,
										campus: newcontent1,
										department: newcontent2,
										course: newcontent3,
										email: newcontent4,
										gender: newcontent5,
									},
									cache: false,
									success: function(data){
										
										$('.text_wrapper1').html(newcontent1);
										$('.text_wrapper2').html(newcontent2);
										$('.text_wrapper3').html(newcontent3);
										$('.text_wrapper4').html(newcontent4);
										$('.text_wrapper5').html(newcontent5);
										$('.edit_link5').show();
										for(var b=1;b<=5;b++){
											$('.text_wrapper' + b).show();
										}
									}
								});
							});
					});
				});
				
					$(document).ready(function(){
					$("#cancel_edit").click(function(){
						for (var c=1;c<=5;c++){
							$('.edit_link5').show();
							$('.edit' + c).hide();
							$('.text_wrapper' + c).show();
						}
					});
				});		

				$(document).ready(function(){
					$("#create_user").click(function(){
						$("#input_username").dialog({
							resizable: false,
							height: 185,
							width: 270,
							modal: true,
						});
					});
				});
				/*	$(document).ready(function(){
						$(function(){
							$("#create_user").click(function(){
								$("#input_username").dialog({
								resizable: false,
								height: 185,
								width: 270,
								modal: true
								});
							});
						});
					});
				*/
				
				$(document).ready(function(){
					$("#check_user").keyup(function(){
						var user = $(this).val();
						var show_res = $("#show_user_result");
						
						$.post(	
							'php/signcheck.php', {ch_username: form.username.value},
							function(result){
								$("#show_user_result").html(result).show();
							});
							
					});
				});
				$(document).ready(function(){
					$("#check_user").keypress(function( e ){
						if(e.which === 32)
							return false;
					});	
				});
				
		}

				
	</script>
</head>

					<div class="col-md-9 main-information" id="main-info">
						<div class="col-md-12">
							<div class="col-md-12"  style="font-size:35px; color:black" >	
									<?php 
								if($getid==$user_id || !isset($_GET['user'])){
									if(empty($rusername))
												{
									?>
							<span class="btn btn-info username-size default-hover"
							style="cursor:pointer;" id="create_user">
										
										Create Username
										
												
											<?php
										
											}
											else
											{
									
											?>
							<span style="font-size:30px; font-family:Ahoroni; color:#1E90FF; text-shadow: 1px 1px 2px black">				
											<?php
							echo htmlspecialchars($rusername);				
											}
								}
											?>
							</span>
							<?php 
								
								if($getid==$user_id || !isset($_GET['user'])){								
									echo htmlspecialchars($rfname. " ". $rlname);
								}
								else{
									echo htmlspecialchars($profile_fname." ".$profile_lname);
								}
							
							?>
							</div>
						</div>
					
						<div class="col-md-12 about-distance">
							<div class="col-md-12">
								<div class="media reply_section" style="background-color:white; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
									  
								<div class="media-body post_reply_content">
									
									<div class="mainbox">
									
									<div class="student-info">
										<span style="color:black;"><img src="image/profile2/campus.png" alt="logo" width="25" height="25" border="0"> Campus:</span>
										<?php
											
										if($getid==$user_id || !isset($_GET['user'])){
										?>
										<p style="float:right;"><a class="edit_link5 btn btn-info" style="cursor:pointer;">Edit <a/></p>
										<?php
										}
										?>
										<span class="text_wrapper1" style="color:#1E90FF;">
											<?php
												if($getid==$user_id || !isset($_GET['user'])){
													echo $rcampus; 
												}
												else{
													echo $profile_camp;
												}
											?>
										</span>
										<div class="edit1" style="display:none">
										<select class="form-control" id="select_camp">
											<option class="editbox1"></option>
											<?php
												$camp_quer = $db -> prepare ("SELECT campus as campus FROM campus_tbl WHERE campus!=:campus ORDER BY campus ASC");
												$camp_quer -> bindParam (":campus", $rcampus);
												$camp_quer -> execute();
												while($rowcamp = $camp_quer -> fetch(PDO::FETCH_ASSOC)){
												$newcamp = $rowcamp['campus'];
											?>
												<option value="<?php echo $newcamp;?>"><?php echo $newcamp;?></option>
											<?php
											}
											?>
										</select>
										</div>
									</div>
									<hr>
									
									<div class="student-info">
										<span style="color:black;"><img src="image/profile2/dept.png" alt="logo" width="25" height="25" border="0"> Department:</span>
										
										<span class="text_wrapper2" style="color:	#1E90FF;">
											
											<?php
												if($getid==$user_id || !isset($_GET['user'])){
													echo $rdepartment;
												}
												else{
													echo $profile_dept;
												}
											?></span>
										<div class="edit2" style="display:none">
										<select class="form-control" id="select_dept">
											<option class="editbox2"></option>
											<?php
												$dept_quer = $db -> prepare ("SELECT department as department FROM department_tbl ORDER BY department ASC");
											
										
												$dept_quer -> execute();
												while($rowdept = $dept_quer -> fetch(PDO::FETCH_ASSOC)){
												$newdept = $rowdept['department'];
											?>
												<option value="<?php echo $newdept;?>"><?php echo $newdept;?></option>
											<?php
											}
											?>
										</select>	
										</div>
									</div>
									<hr>
									
									<div class="student-info">

										<span style="color:black;"><img src="image/profile2/course.png" alt="logo" width="25" height="25" border="0"> Course:</span>
										
										<span class="text_wrapper3" style="color:#1E90FF;">
										<?php 
											if($getid==$user_id || !isset($_GET['user'])){
												echo $rcourse;
											}
											else{
												echo $profile_course;
											}
										?></span>
										<div class="edit3" style="display:none">
					<script>
						$(document).ready(function(){
							$("#select_dept").on('change', function(){
								var dept = $(this).val();
									$.ajax({
										type: 'POST',
										url: 'pages/filter_feed.php',
										data:
										{
											department_filter : dept,
										},
										cache: false,
										success: function(data){
											document.getElementById("span_course").innerHTML = data;
										},
									});
							});
						});
					</script>
										<span id="span_course">
										<select style="margin-top:10px;"class="form-control" name="select_course" id='select_course'>
										<option value="">Select Course</option>
										</select>
											
										</span>
										</div>
									</div>
									<hr>
								
									
									<div class="student-info">
										<span style="color:black;"><img src="image/profile2/gender.png" alt="logo" width="25" height="25" border="0"> Gender: </span>

										<span class="text_wrapper5" style="color:	#1E90FF;">
											<?php
												if($getid==$user_id || !isset($_GET['user'])){
													if($rgender!=""){
														echo $rgender; 
													}
												}	
												else{
													if($profile_gender!=""){
													echo $profile_gender;
													}
												}	
																	?></span>
										<div class="edit5" style="display:none">
										<select class="form-control" id="select_gender">
											<option class="editbox5"></option>
											<?php
											if ($rgender==""){
											?>
											
											<option value="Male">Male</option>
											<option value="Female">Female</option>
											
											<?php
											}
											else if($rgender == 'Male'){
											?>
											
												<option value="Female">Female</option>
											<?php
											
											}
											else{
												
											
											?>
												<option value="Male">Male</option>
											<?php
											}
											?>
										</select>
										<div>
										<button id="save_edit" class="btn btn-info" style="margin-top:25px;">Save Changes</button>
										<button id="cancel_edit" class="btn btn-info" style="margin-top:25px;">Cancel</button>	
										</div>
										</div>
									</div>	`
									
									<div style="display:none;" id="input_username" title="Create Username">
			<form name="form" action="php/create_username.php" method="POST" onsubmit="return alphanumeric(document.form.username)">
				<label>Username<span id="show_user_result"></span></label>
				
				<input type="text" style="height:27px;display:block;margin-bottom:10px;" name="username" class="form-control" id="check_user" autocomplete="off" required>
				<input type="submit" name="create_user" class="btn btn-info form-control">
				
				<span style="margin-left:2px;font-size:10px;color:red;display:block;">This cannot be changed!</span>
				
			</form>
		</div>
									</div>
								</div> 
							</div>
						</div>
						
						
					</div>
				</div>
				
				
				
				