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
<script type="text/javascript">
		$(document).ready(function(){
		$('.other-info-edit-link').click(function(e){
		e.preventDefault();	
		for(var d=1;d<=4;d++){
		$('.other-info-contain' + d).hide();
		$('.other-info-edit-link').hide();
  			var content2 = $('.other-info-contain' + d).html();	
			$('.other-info-edit' + d).show();
			$('.other-editbox' + d).html(content2);
		}
			$('#save_other_edit').click(function(){
			for(var a=1;a<=4;a++){			
				$('.other-info-edit' + a).hide();
			}
				var othercontent1 = $('.other-editbox1').val();
				var othercontent2 = $('.other-editbox2').val();
				var othercontent3 = $('.other-editbox3').val();
				var othercontent4 = $('.other-editbox4').val();
				var other_save_button = $('#save_other_edit').val();
					$.ajax({
						type: 'POST',
						url: 'php/update_profile.php',
						data:
						{
						
							other_save: other_save_button,
							hometown: othercontent1,
							elementary: othercontent2,
							highschool: othercontent3,
							aboutme: othercontent4,
						},
						cache: false,
						success: function(data){
							$('.other-info-contain1').html(escapeHtml(othercontent1));
							$('.other-info-contain2').html(escapeHtml(othercontent2));
							$('.other-info-contain3').html(escapeHtml(othercontent3));
							$('.other-info-contain4').html(escapeHtml(othercontent4));
							$('.other-info-edit-link').show();
							for(var b=1;b<=4;b++){
								$('.other-info-contain' + b).show();
							}
						}
					});
				});
		});
	});

	$(document).ready(function(){
		$("#cancel_other_edit").click(function(){
			for (var g=1;g<=5;g++){
				$('.other-info-edit_link').show();
				$('.other-info-edit' + g).hide();
				$('.other-info-contain' + g).show();
			}
		});
	});						

</script>
					<div class="col-md-9 main-information" style="display:inline;" id="other-info">
						<div class="col-md-12 about-distance">
							<div class="col-md-12">
								<div class="media reply_section"style="background-color:white; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
									  
								<div class="media-body post_reply_content">
									
									<div class="mainbox">
									
									<div class="student-info">
										<span style="color:black;">Hometown:</span>
										<?php
										if($getid==$user_id || !isset($_GET['user'])){
										?>
										<p style="float:right;"><a class="other-info-edit-link btn btn-info" style="cursor:pointer;">Edit <a/></p>
										<?php
										}
										?>
										<span class="other-info-contain1" style="color:#1E90FF;"><?php 
										if($getid==$user_id || !isset($_GET['user'])){
										
											displayText($rhometown); 
										}
										else{
											displayText($profile_hometown);
										}
										?></span>
										<div class="other-info-edit1" style="display:none">
										<textarea class="other-editbox1 form-control" cols="80" rows="2" style="resize:none;" style="resize:none;" id="hometown"></textarea>
										</div>
									</div><hr>
									
									<div class="student-info">
										<span style="color:black;">Elementary:</span>
										
										<span class="other-info-contain2" style="color:#1E90FF;"><?php
										if($getid==$user_id || !isset($_GET['user'])){
											displayText($relem); 
										}
										else{
											displayText($profile_elementary); 
										}
										?>
										</span>
										<div class="other-info-edit2" style="display:none">
										<textarea style="resize:none;" class="other-editbox2 form-control" cols="80" rows="2" id="elementary"></textarea>
										</div>
									</div><hr>
									
									
									<div class="student-info">
										<span style="color:black;">Highschool:</span>
										<span class="other-info-contain3" style="color:#1E90FF;"><?php
										if($getid==$user_id || !isset($_GET['user'])){
											displayText($rhsschol);
										}
										else{
											displayText($profile_highschool);
										}
										?></span>
										<div class="other-info-edit3" style="display:none">
										<textarea style="resize:none;" class="other-editbox3 form-control" cols="80" rows="2" id="highschool"></textarea>
										</div>
									</div><hr>
									
								
									
									<div class="student-info">
										<span style="color:black;">About Me: </span><span class="other-info-contain4" style="color:#1E90FF;"><?php if($getid==$user_id || !isset($_GET['user'])){displayText($raboutme);}
										else{displayText($profile_about_me);}
										?></span><div class="other-info-edit4" style="display:none"><textarea class="other-editbox4 form-control" cols="80" rows="4" style="resize:none;" id="aboutme"></textarea>
										
										<div>	
										<button id="save_other_edit" class="btn btn-info" style="margin-top:25px;">Save Changes</button>
										<button id="cancel_other_edit" class="btn btn-info" style="margin-top:25px;">Cancel</button>	
										</div>
										</div>
									</div>	<hr>
									
									</div>
								</div> 
							</div>
						</div>
						</div>
						
						
					</div>


				</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
