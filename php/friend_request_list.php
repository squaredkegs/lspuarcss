<?php

	include "connection.php";
	include "querydata.php";
	
							$query = $db -> prepare ("SELECT fname, stud_id, lname FROM stud_bas 
								RIGHT JOIN frnd_rqst
								ON frnd_rqst.frst_user = stud_bas.stud_id
								WHERE frnd_rqst.scnd_user = :my_id AND frnd_rqst.status = '1'");
								$query -> bindParam (":my_id", $getid);
								$query -> execute();


							while($row = $query -> fetch(PDO::FETCH_ASSOC))
							{
								$friend_fname = $row['fname'];
								$friend_lname = $row['lname'];
								$friend_id = $row['stud_id'];
								$full_name = $friend_fname. " ". $friend_lname;
						
							?>
								<!--<input type="hidden" value="<?php echo $full_name;?>" class="friendname<?php echo $friend_id;?>">-->
							
								<a href="profile.php?user=<?php echo $friend_id; ?>
								"style="margin:20px 15px 0px; 15px;">
						
						<div class="friend_request<?php echo $friend_id;?>">			
							<span class="fullname<?php echo $friend_id;?>">
								<?php
								$fullname = limit_length($full_name,22);
								?>
							</span>
							
								</a>
							<li style="margin:10px 10px 0 10px;">
								<div class='top'>
									<div style="display: table-row;">
										<div style="display: table-cell;">
										
										<button id="<?php echo $friend_id;?>" style="margin-right:10px;margin-left:10px;"class="btn btn-info friend-request accept<?php echo $friend_id;?>" name="accept">Accept
										</button>
										<button id="<?php echo $friend_id; ?>" class="btn btn-danger friend-request reject<?php echo $friend_id;?>">Reject</button>
										</div>				
									</div>
								</div>
							</div>
							</li>
							<?php
							}
							
							?>
