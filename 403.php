<?php
	include_once ("php/connection.php");
	include_once ("php/querydata.php");
?>
    <section id="error" class="container text-center">
      <div style="height:500px;">
		<h1>403 Error </h1>
		<h2>This save section is not yours. You are forbidden to access this page</h2>
        <p>Click <a style="color:blue;text-decoration:underline;" href="myprofile?user=<?php echo $getid;?>">here</a> to go back to your profile</p>
	</div>
	</section><!--/#error-->

  