<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/querydata.php");
?>
<html>
<head>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>

<title>My title</title>

</head>
<body>
		<?php
		$test = "www.youtube.com www.reddit.com https://www.google.commmmmmmmddang  problma kasi doon eh pati Mr.Something eh gagawing link so hindi to pde gagawa ako ng custom preg_replace kos";
		
		$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
		$string = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $test);
		echo $string;

</body>
</html>
	