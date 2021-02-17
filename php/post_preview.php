<?php
include_once ("connection.php");


		$desc = ($_POST['description']); 
		$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
		$string = preg_replace($url, '<a style="color:blue;text-decoration:underline;" href="$0" target="_blank" title="$0">$0</a>', $desc);
		echo strip_tags($string,"<a>");

?>