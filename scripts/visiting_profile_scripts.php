	<script type="text/javascript">
	
	
		var visitid = "<?php echo $_SESSION['log_user'];?>";
		var ownerid = "<?php echo $_GET['user']; ?>";

		$(document).ready(function(){
			
			$("#cancel-button").click(function(){
				$.ajax({
					type: 'POST',
					url: 'php/friend_request.php',
					async: true,
					dataType: 'json',
					data: 
					{
						cancel_visit: visitid,
						owner: ownerid
					},
					cache: false,
					success: function(data)
					{
						if(data.response == 'success'){
							$("#cancel-button").hide();
							$("#add-friend-button").show();
						}
						
						
					},
				});
			});
		});
	
	$(document).ready(function(){
		$('#add-friend-button').click(function(){
			$.ajax({
				type: 'POST',
				url: 'php/friend_request.php',
				async: true,
				dataType: 'json',
				data:
				{
					add_visit: visitid,
					owner: ownerid
				},
				success: function(data){
					 if(data.response == 'success'){
						$('#cancel-button').show();
						$('#add-friend-button').hide();
					}
					
				},
			});
		});
	});
	
	$(document).ready(function(){
		$("#accept-friend-button").click(function(){
			$.ajax({
				type: 'POST',
				url: 'php/friend_request.php',
				async: true,
				dataType: 'json',
				data:
				{
					accept_visit: visitid,
					owner: ownerid
				},
				success: function(data){
					if(data.response == 'success' ){
						
						$("#accept-friend-button").hide();
						$("#reject-friend-button").hide();
						$("#friends").show();
					}
				},
			});
		});
	});
	
	$(document).ready(function(){
			$("#reject-friend-button").click(function(){
				$.ajax({
					type: 'POST',
					url: 'php/friend_request.php',
					dataType: 'json',
					data:
					{
						reject_visit : visitid,
						owner: ownerid
					},
					success: function(data){
						if(data.response == 'success'){
							$("#reject-friend-button").hide();
							$("#accept-friend-button").hide();
							$("#add-friend-button").show();
						}
					}
				});
					
			});
	});
	
	//Unfriend
	$(document).ready(function(){
		$(function(){
			$("#friends").click(function(){			
				$("#confirm-unfriend-button").dialog({
				resizable: false,
				height: 140,
				modal: true,
				buttons:{
					"Unfriend": function(){
						$.ajax({
							type: 'POST',
							url: 'php/friend_request.php',
							data:
							{
								destroy_visit :visitid,
								owner: ownerid
							},
							cache: false,
							success:function(data)
							{
								$("#user-unfriended").dialog({
									resizable: false,
									height: 140,
									modal: true,
									open: function(event, ui){
										$('.ui-widget-overlay').bind('click', function(){
											$("#user-unfriended").dialog('close');
										});
									}
									
								});
								
							}
						});
						$(this).dialog("close");
						$("#friends").hide();
						$("#add-friend-button").show();
						},
					Cancel: function(){
						$(this).dialog("close");
						}
					}
				});
			});
		});
	});
	
	
	$(document).ready(function(){
		$("#close-user-unfriended").on('click', function(){
			$("#user-unfriended").dialog('close');
		});
	});
	
	$(document).ready(function(){
		$(".friend-button").click(function(){
			var frid = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: 'php/friend_request.php',
				data:
				{
					frid: frid,
				},
				cache: false,
				success: function(data){
					$("#" + frid).html(data);
					$(".reject-friend-button").hide();
				}
			});
		});
	});

	
	$(document).ready(function(){
		$(".reject-friend-button").click(function(){
			var frid = $(this).attr("id");
			
			$.ajax({
				type: 'POST',
				url: 'php/friend_request.php',
				data:{
					unfrid: frid,
				},
				cache: false,	
					success: function(data){
						$(".friend-button").html("Add Friend");
						$(".reject-friend-button").hide();
					}
			})
		});
	});
						
	</script>