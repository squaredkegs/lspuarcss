
<?php
	include_once ("php/connection.php");
	include_once ("php/function.php");
	include_once ("php/querydata.php");
	$user = checkLogIn();
?>
	
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Message | <?php echo $rfname ." ". $rlname ; ?></title>
    
    <!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
	<link href="css/profile.css" rel="stylesheet">
    <link href="css/corlate.css" rel="stylesheet"> 
	<link href="css/mymain.css" rel="stylesheet">
    <link href="css/otherprofile.css" rel="stylesheet">	
	<link href="css/message.css" rel="stylesheet">
	<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]--> 
	
    
    <link rel="shortcut icon" href="image/thesis.png">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
	<!--<link rel="stylesheet" href="/resources/demos/style.css">
	-->
	<link rel="stylesheet" href="css/jquery-ui.css">

	
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
		<script type="text/javascript">

		//$('#message-scrollable').scrollTop($('#message-scrollable')[0].scrollHeight);
	

			$(document).ready(function(){
				$(".friend-message").click(function(e){
					id = $(this).attr("id");
					$("#message-box").load('php/display_message.php?frid=' + id);
						$.ajax({
							type: 'POST',
							url: 'php/unread_messages.php',
							data:
							{
								frid: id,
							},
							cache: false,
						});
				});
			});
			
			</script>
	<style>
	div#friend-scrollable
	{
		width:300px;
		height:100px;
		border: 1px solid black;
	}
	div.mousescroll{
		overflow:hidden;
	}
	div.mousescroll:hover{
		overflow-y: scroll;
	}
	</style>
</head><!--/head-->

<body onload="numberOfMessages">
	<?php
		include_once ("header.php");
	?>

    <section id="blog" class="container">


	<div class="blog">
		<div class="row">
		
			<div id="message-container">
				<div class="col-md-9">
				<h2 class="chat"><img src="image/sms2.png" title="Messages" alt="logo" width="32" height="32" border="0">Message</h2>
					<div id="message-box">
						
					</div>
					
				</div>
				
				<div class="col-md-3">
				<h2 class="chat"><img src="image/fff.png" title="Messages" alt="logo" width="32" height="32" border="0">Friends</h2>
						<div style="height:350px ;border-radius:5px; background-color:white; box-shadow: 10px 10px 2px black;" id="friend-scrollable" class="mousescroll">
					<?php
						
						$query = $db -> prepare ("SELECT frst_user as frst,scnd_user as scnd FROM frnd_rqst
						WHERE (frst_user=:fid OR scnd_user = :fid) AND status=2
                    	ORDER BY scnd_user ASC");
						$query -> bindParam (":fid", $getid);
						$query -> execute();
						while($row = $query -> fetch(PDO::FETCH_ASSOC)){
							$numrow = $query -> rowCount();
							$frst = $row['frst'];
							$scnd = $row['scnd'];	
							$sec_quer = $db -> prepare ("SELECT fname, lname,stud_id FROM stud_bas WHERE stud_id!=:sid AND (stud_id=:fid OR stud_id = :tid)");
							$sec_quer -> bindParam (":sid", $getid);
							$sec_quer -> bindParam (":fid", $frst);
							$sec_quer -> bindParam (":tid", $scnd);
							$sec_quer -> execute();
							$num_sec_quer = $sec_quer -> rowCount();
							if($num_sec_quer>0){
							$result = $sec_quer -> fetch();
							$fname = $result['fname'];
							$lname = $result['lname'];
							$frid = $result['stud_id'];
					?>
					
					
							<ul style='position:relative;' id='testing'>
								<li  class="friend-message" id="<?php echo $frid;?>"value="<?php echo $frid;?>"><a href="#" ><?php echo $fname." ".$lname;?></a>
								<span style='background-color:black;color:white;    border-radius:50%;width:100px;height:100px;'id="fridtesting<?php echo $frid;?>"> </span>
								</li>
							</ul>
					
						<script>
							var myVar = setInterval(get_message,1000);
							function get_message(){
								var id = "<?php echo $frid;?>";
								$.ajax({
									type: 'POST',
									url: 'php/display_who_message.php',
									data:
									{
										frid: id,
									},
									cache: false,
									success: function(data){
										$("#fridtesting" + id).html(data);
									}
								});
							}
						</script>
					<?php
						}
					}
					?>
						</div>
<script>
	$("#testing").animate({ scrollTop: $("#testing")[0].scrollHeight}, 1000);
	
</script>
				</div>
	
			</div>
		</div><!--/.blog-->
	</div>
    </section><!--/#blog-->

    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6"style="color:white;">
                     &copy; 2017 <a target="_blank" href="#" title="Arvin Talabis is Awesome">Suicide Squad</a>. All Rights Reserved.
                </div>

            </div>
        </div>
    </footer><!--/#footer-->

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/wow.min.js"></script>
</body>
</html>