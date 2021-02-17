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
		if($getid==$user_id || !isset($_GET['user'])){
			$real_id = $getid;
		}
		else{
			$real_id = $user_id;
		}
			$query = $db -> prepare ("SELECT frnd_rqst.frst_user as fuser, frnd_rqst.scnd_user as suser, frnd_rqst.status as status, stud_bas.fname as fname, stud_bas.stud_id as sid,stud_bas.lname as lname FROM stud_bas INNER JOIN frnd_rqst ON (frnd_rqst.frst_user = stud_bas.stud_id OR scnd_user = stud_bas.stud_id) WHERE (frnd_rqst.frst_user = :sid OR frnd_rqst.scnd_user = :sid) AND frnd_rqst.status=2");
		$query -> bindParam (":sid", $real_id);
		$query -> execute();
?>
					<div class="col-md-9 main-information" style="display:inline;" id="friends-info">
						<div class="col-md-12 about-distance">
							<div class="col-md-12">
								<div class="media reply_section"style="background-color:white; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
									<div class="media-body post_reply_content">
										<div class="mainbox">
											<?php
											while($row=$query->fetch(PDO::FETCH_ASSOC)){
												$frst_user = $row['fuser'];
												$scnd_user = $row['suser'];
												$friend_fname = $row['fname'];
												$friend_lname = $row['lname'];
												$friend_sid = $row['sid'];
												$friend_info = $db -> prepare ("SELECT * FROM stud_info WHERE stud_id=:sid");
												$friend_info -> bindParam(":sid", $friend_sid);
												$friend_info -> execute();
												$result = $friend_info -> fetch();
												$friend_pic_name = $result['picture_name'];
												$friend_pic_path = $result['picture_path'];
												if($friend_sid!=$real_id){
											?>
												<div>
													<a href="myprofile?user=<?php echo $friend_sid;?>">
													<?php
													if(!empty($friend_pic_name)){
													?>
													<img style="height:110px;width:120px;" src="php/<?php echo $friend_pic_path;?>"/> 
													<?php
													}
													else{
													?>
													
													<img style="height:110px;width:120px;" src="image/profile/profile1.png"/> 
													
													<?php
													}
													?>
													</a>
													<span style="display:block;">
														<a href="myprofile?user=<?php echo $friend_sid;?>">
														<?php
															displayText($friend_fname." ".$friend_lname);
														?>
														</a>
													</span>
												</div>
											<?php
												}
											}
											?>
										</div>
									</div> 
								</div>
							</div>
						</div>
					</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
