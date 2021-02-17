<?php
include_once "connection.php";
include_once "querydata.php";

?>

	
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script>
			$(document).ready(function(){
				$("#thebutton").click(function(e){
					alert('Maybe');
				});
			});

		$(document).ready(function(){
			$(".friend-message").click(function(e){
				//id = $(this).attr("id");
				//$("#message-box").load('php/display_message.php?frid=' + id);
				//console.log('Where');
				alert('Maybe');
			});
		});
	</script>
	
<body>
<?php
						$query = $db -> prepare ("SELECT frst_user as frst,scnd_user as scnd FROM frnd_rqst
						WHERE (frst_user=:fid OR scnd_user = :fid) AND status=2
                    	");
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
							$result = $sec_quer -> fetch();
							$fname = $result['fname'];
							$lname = $result['lname'];
							$frid = $result['stud_id'];
					?>
							<ul>
								<li  class="friend-message" id="<?php echo $frid;?>"value="<?php echo $frid;?>"><a href="#" ><?php echo $fname." ".$lname;?></a></li>
								<p id="fridfrid<?php echo $frid;?>"></p>
							</ul>
					<?php
					}
					?>
					<button id="thebutton">The Bloody Button</button>
</body>