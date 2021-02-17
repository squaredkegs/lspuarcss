"<div style='background-color:lightblue;margin-bottom:10px;padding-bottom:0.5px;padding-top:5px;margin-top:15px;'>
			<div class='col-md-12'>
			<div class='col-md-1'>
			<button class='c_vote' name='upvote' id='c_up_$cid' style='margin: 10px 0 5px 0;'>U</button>
			<button class='c_vote' name='remove_upvote' id='c_remove_up_$cid'>UP</button>
			
			<button class='c_vote' name='downvote' id='c_down_$cid'>D</button>
			<button class='c_vote' name='remove_downvote' id='c_remove_down_$cid'>DW</button>
			
			<span class='c_upvoted_sc_$cid' style='margin-left:10px;'>1</span>
			<span class='c_neutral_sc_$cid' style='margin-left:10px;'>0</span>
			<span class='c_downvoted_sc_$cid' style='margin-left:10px;'>-1</span>
			</div>
			<div class='col-md-11'>
				<div style='position:relative;'>
				<span style='margin-left:10px;'><a href='myprofile?user=$getid'class='user'>$fullname</a></span>
				</div>
				<span style='margin-left:10px;word-wrap:break-word;'>$comment</span>
			</div>
			</div>
			<ul style='margin-left:70px;'>
				<li style='display:inline;'></li>
				<li style='display:inline;margin-left:10px;' id='".$cid."' class='save_comment save_comment_".$cid."'><a href='#'>
				Save
				</a>
				</li>				
				<li style='display:inline;margin-left:10px;' class='report_post' id='$cid' name='Comment'><a href='#'>Report</a></li>
				<li style='display:inline;margin-left:10px;' class='reply_comment comment_id_$cid' id='cmmt_id_$cid'><a href='#'>Reply</a></li>
				<div class='reply_form_$cid'>
				</div>
			</ul>
			</div>