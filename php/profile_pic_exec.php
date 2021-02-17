<?php


include_once("connection.php");
include_once("user_data.php");
include_once("function.php");


if(isset($_POST['submit_picture']))
{

	if($_FILES['picture']['error'] != UPLOAD_ERR_OK){
		header("location:../myprofile.php?user=$getid");
		die("Upload Failed with error code". $_FILES['picture']['error']);
	}
	$info = getimagesize($_FILES['picture']['tmp_name']);
	
	if($info === FALSE ){
		header("location:../myprofile.php?user=$getid");
		die("Invalid/Unable to determine image type of uploaded file");
	}
	
	if(($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)){
		header("location:../myprofile.php?user=$getid");
		die("Invalid file type");
	}
	
	$datetime = date("Y-m-d h:i:s");
	$temp_name = $_FILES['picture']['name'];
	$pic_type = $_FILES['picture']['type'];
	$temp = explode (".", $_FILES['picture']['name']);
	$name = createRandomId('picture_name','stud_info');		
	$pic_name = $name . "." . end ($temp);	
	$pic_temp = $_FILES['picture']['tmp_name'];
	
	$rand_name = round(microtime(true));		
	$pic_path = "../image/profile/". $getid . "/profile_picture/" . $pic_name;
		//could be change ^
		
	
	$query = $db -> prepare ("UPDATE stud_info SET picture_path=:pic_pat, picture_type=:pic_type, picture_name=:pic_name WHERE stud_id=:sid");
	$query -> bindParam (":pic_pat", $pic_path);
	$query -> bindParam (":pic_name", $pic_name);
	$query -> bindParam (":pic_type", $pic_type);
	$query -> bindParam (":sid",$getid);
	$query -> execute();
		if($query)
		{
			move_uploaded_file($pic_temp,$pic_path);
			header("location:../myprofile.php?user=$getid");
		}
		else
		{
			echo "First Error";
		}
		
		
}
?>