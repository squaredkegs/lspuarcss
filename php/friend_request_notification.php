	<?php
		
		include "connection.php";
		include "querydata.php";
	?>
	<link rel="stylesheet" href="css/navbar.css"/>
	<body>
		<?php
		

		$post_comment_seen = $db -> prepare ("
				SELECT DISTINCT(comment_connect.news_id) as nid 
				FROM (SELECT news_id,stud_id FROM post_connect WHERE stud_id = :sid) as post_connect 
				LEFT JOIN comment_connect 
				ON comment_connect.news_id = post_connect.news_id WHERE comment_connect.post_c_seen = 0 and comment_connect.stud_id != :sid");
		$post_comment_seen -> bindParam (":sid", $getid);
		$post_comment_seen -> execute();
		
		$p_s = $post_comment_seen -> rowCount();
		
		
		$unseen_reply = $db -> prepare ("SELECT cmmt_id FROM comment_connect WHERE stud_id = :sid") ;
		$unseen_reply -> bindParam (":sid", $getid);
		$unseen_reply -> execute();
		while($unseen_rep_row = $unseen_reply -> fetch(PDO::FETCH_ASSOC)){
		$pid = $unseen_rep_row['cmmt_id'];
		$child_unseen_reply = $db -> prepare ("
				SELECT COUNT(comment_connect.cmmt_id) as cid
				FROM comment_connect
				INNER JOIN cmmt_sect
				ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
				WHERE parent_id=:pid 
				AND comment_connect.comment_c_seen = 0 AND comment_connect.stud_id!=:sid");
		$child_unseen_reply -> bindParam (":sid",$getid);
		$child_unseen_reply -> bindParam (":pid", $pid);
		$child_unseen_reply -> execute();
		$child_unseen_numrow = $child_unseen_reply -> rowCount();
			if($child_unseen_numrow>0){
				$res_unseen_reply = $child_unseen_reply -> fetch();
				$count_unseen_reply = $res_unseen_reply['cid'];
			}
		
		}
		$childs_unseen_numrow = 0;
		$unseen_reply = $db -> prepare ("SELECT cmmt_id as cid FROM comment_connect WHERE stud_id = :sid") ;
			$unseen_reply -> bindParam (":sid", $getid);
			$unseen_reply -> execute();
			while($unseen_rep_row = $unseen_reply -> fetch(PDO::FETCH_ASSOC)){
			$pid = $unseen_rep_row['cid'];
			$child_unseen_reply = $db -> prepare ("
					SELECT DISTINCT(cmmt_sect.parent_id) as cid
					FROM comment_connect
					INNER JOIN cmmt_sect
					ON cmmt_sect.cmmt_id = comment_connect.cmmt_id
					WHERE cmmt_sect.parent_id=:pid AND
					 comment_connect.stud_id!=:sid AND comment_connect.comment_c_seen=0");
			$child_unseen_reply -> bindParam (":sid",$getid);
			$child_unseen_reply -> bindParam (":pid", $pid);
			$child_unseen_reply -> execute();
			$child_unseen_numrow2 = $child_unseen_reply -> rowCount();
			$res_unseen_numrow = $child_unseen_reply -> fetch();
		
			$child_unseen_numrow = $res_unseen_numrow['cid'];
			$childs_unseen_numrow += $child_unseen_numrow2;
		}
			
		
		//
		$unseen = $db -> prepare ("SELECT fname, stud_id, lname FROM stud_bas 
			RIGHT JOIN frnd_rqst
			ON frnd_rqst.frst_user = stud_bas.stud_id
			WHERE frnd_rqst.scnd_user = :my_id AND frnd_rqst.status = '1' AND frnd_rqst.seen=0");
			$unseen -> bindParam (":my_id", $getid);
			$unseen -> execute();
			$real_count = $unseen -> rowCount();

			$acceptance = $db -> prepare ("SELECT fname, stud_id, lname FROM stud_bas RIGHT JOIN frnd_rqst
			ON frnd_rqst.frst_user = stud_bas.stud_id
			WHERE frnd_rqst.frst_user = :my_id2 AND frnd_rqst.status = '2' AND seen_acceptance='0'");
			$acceptance -> bindParam (":my_id2", $getid);
			$acceptance -> execute();
			$accepted_rqst = $acceptance -> rowCount();
			$new_real_count = $p_s + $accepted_rqst + $real_count + $childs_unseen_numrow;
				if($new_real_count>0){
				?>
				
				<span id="frndrqst" class="notification-number" id="unseen-notification"><?php echo $new_real_count; ?></span>
					
				<?php							
				}
				else{
									
				}
				?>
</body>