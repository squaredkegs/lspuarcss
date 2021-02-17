<?php
include_once "connection.php";
include_once "function.php";
include_once "querydata.php";
$cid = createRandomId('chat_id','chat');
$datetime = date("Y-m-d H:i:s");
?>
<html>
	<head>
		<title>Message</title>
	<link rel="stylesheet" href="style.css" media="all"/>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="../js/jquery-1.12.4.js"></script>
	<script src="../js/jquery-ui.js"></script>
	
	<script>
		$(document).ready(function(){
			$(".friend_id").click(function(e){
				id = $(this).attr("id");
				$("#chat").load('currentmsg.php?frid=' + id);
			});
		});
	</script>
	</head>
<body>
	<h1><marquee>The quick brown fox jumps over the lazy dog.</marquee></h1>
	<div id="chat_box">
		<div id="chat"></div>
	</div>
	<div style="float:right;">
		<button name="friend" class="friend_id" id="43">43</button>
		<button name="friend" class="friend_id" id="42">42</button>
		<button name="friend" class="friend_id" id="22">22</button>
	</div>

</body>
</html>