	function report_post(content,e){
			var id = $(content).attr("id");
			var type = $(content).attr("name");
			$("#report_reason").dialog({
				resizable: false,
				height: 300,
				width: 400,
				modal: true,
				open: function(event, ui){
					$('.ui-widget-overlay').bind('click', function(){
						$("#report_reason").dialog('close');
					});
				}
			});
			$("#report_type").val(type);
			$("#report_id").val(id);	
			$("#report_reason").css("visibility", "visible");
			e.preventDefault();
						
				$('.submit_report').click(function(e){
					var nid = $("#report_id").val();
					var content = $("#report_content").val();
					var type = $("#report_type").val();
					if ($('input[name="report"]:checked').length>0 && (content!=null &&
					content!="")){
					var complaint = document.querySelector('input[name="report"]:checked').value;
						$.ajax({
							type: 'POST',
							url: 'php/report_post.php',
							data:
							{
								type: type,
								nid: nid,
								reason: complaint,
								content: content,
							},
							cache: false,
							success: function(data){
							alert(data);
					
							$("#report_content").val("");
							$("input[name='report']").prop('checked', false);
							$("#report_reason").dialog('close');
							}
						});	
					}
					else{
						alert('Fill all of the information!');
					}
				e.preventDefault();	
				});
	}

	function submit_comment(){
		var postComment = new FormData();
		var parent_comment_id = document.getElementById('nid').value;
		var comment = document.getElementById('parent_comment').value;
		var media = document.getElementById('file_media');
		var progress = $("#file_comment_progress");
		postComment.append('post_media', media.files[0]);
		postComment.append('nid', parent_comment_id);
		postComment.append('comment', comment);
			if(comment){
				$.ajax({
					type: 'POST',
					url: 'php/comment_post.php',
					data: postComment,
					processData: false,
					contentType: false,
					success: function(data){
						$("#parent_comment").val('');
						$(media).val("");
						$("#file_media_name").html("");
						$("#file_media").val("");
						$("#clear_post_file").hide();
						$(progress).text("");
						document.getElementById('comment_container').innerHTML = data + document.getElementById('comment_container').innerHTML;
					}
				});
			}
			else{
				alert("Comment is empty!");
			}
		return false;
	}
	
	function reply_comment(this_button,e){
			var id = $(this_button).attr("id");
			var index = id.lastIndexOf("_");
			var result = id.substr(index+1);
			var div_reply = (".reply_form_" + result);
			$(".reply_form_" + result).html($("#reply_comment_div").html());
			$("#this_reply .parent_comment_id").val(result);
			
			if($("#this_reply").attr("id", "this_reply_" + result)){
			$("#this_reply_" + result).find(".media:eq(0)").attr("id", "media_" + result);
			$("#this_reply_" + result).find(".custom-file-upload:eq(0)").attr("id", "custom_file_upload_" + result);
			$("#this_reply_" + result).find(".custom-file-upload:eq(0)").attr("for", "media_" + result);
			}
			
			$("#this_reply_" + result).find("textarea").focus();
			$("#this_reply").css({
						"visibility": "visible",
						"display": "inline",
						});
			e.preventDefault();
	}
	
	
	function submitMedia(this_button,e)
	{
		var div = $(this_button).closest("form");
		var uploaded_media = $(div).find('.uploaded_media:eq(0)');
		var media_path = $(div).find('.media:eq(0)').val();
		var cut_name = media_path.lastIndexOf("\\");
		var media_name = media_path.substr(cut_name + 1);	
		var submit_reply_btn = $(div).find(".submit_reply:eq(0)");
		var show_media_name = $(div).find('.uploaded_media:eq(0)');
		var get_id = $(this_button).attr("id");
		var media_id = document.getElementById(get_id);
		var progress = $(div).find(".reply_media_progress:eq(0)");
		$(show_media_name).html(media_name + " <span class='remove_media' onclick='remove_media(this,event);' style='color:red;cursor:pointer;'>&nbsp;&nbsp;X</span>");
		var x = $(div).find(".remove_media:eq(0)");
		var media_val = $(this_button).val();
		var c_index = media_val.lastIndexOf(".");
		var cut_media_val = media_val.substr(c_index + 1).toUpperCase();
			if(media_val==""){
				$(submit_reply_btn).prop('disabled', false);
				$(progress).text("");
				$(x).hide();
			}else{
				if(cut_media_val=="PNG" || cut_media_val=="JPG" || cut_media_val=="JPEG"){
					//$(submit_reply_btn).prop('disabled', false);
						var replyMediaPost = new FormData();
						replyMediaPost.append('media_id', media_id.files[0]);
						$.ajax({
							type: 'POST',
							contentType: false,
							processData: false,
							data: replyMediaPost,
							xhr: function() {
							var myXhr = $.ajaxSettings.xhr();
							if (myXhr.upload) {
								//For handling the progress of the upload
								myXhr.upload.addEventListener('progress', function(e) {
									if (e.lengthComputable) {
										var percentage = (e.loaded / e.total) * 100;
										$(progress).text(Math.floor(percentage) + '% File Completed');
										if(percentage==100){
											$(submit_reply_btn).prop("disabled", false);
										}
										else{
											$(submit_reply_btn).prop("disabled", true);
										}
									}
								} , false);
							}
							return myXhr;
						
							},

						});
				}
				else{
					$(submit_reply_btn).prop('disabled', true);
				}
			}
	}
	
	function remove_media(this_button,e)
	{
		var div = $(this_button).closest("form");
		var media = $(div).find('.media:eq(0)');
		var media_name = $(div).find('.uploaded_media:eq(0)');
		var submit_reply_btn = $(div).find(".submit_reply:eq(0)");
		var progress = $(div).find(".reply_media_progress:eq(0)");
		$(submit_reply_btn).prop('disabled', false);
		$(media_name).html('');
		$(this_button).hide();
		$(media).val('');
		$(progress).text("");
		e.preventDefault();
	}
	
	function submitUserReply(this_button,e){
		var replyData = new FormData();
		var media_val = "";
		var div = $(this_button).closest("form");
		var id = $(div).find(".parent_comment_id").val();
		var comment = $(div).find(".reply_comment_content").val();
		var child = $(this_button).closest("ul").find(".child");
		var nid = $(div).find(".post_news_id").val();
		var media = $(div).find(".media");
		var real_media = document.getElementById("media_" + id);
		media_val = $(media).val();
		replyData.append('nid', nid);
		replyData.append('pcid', id);
		replyData.append('comment', comment);
		replyData.append('media', real_media.files[0]);
			if(comment && id && nid){
				$.ajax({
					type: 'POST',
					url: 'php/reply_comment.php',
					data: replyData,
					success: function(data){
						document.getElementById("child_of_comment_" + id).innerHTML = data + document.getElementById("child_of_comment_" + id).innerHTML;
						$(this_button).closest("form").hide();
					},
					cache: false,
					processData: false,
					contentType: false, 
				});
			}
			else{
				alert ("Comment cannot be empty");
			}
			e.preventDefault();
	}
	
	
	function cancel_reply (this_button,e){
		$(this_button).closest("form").hide();
		e.preventDefault();
	}
	
	function upvote_downvote(object,e){
			var cid = $(object).attr("id");
			var name = $(object).attr("name");
			var c_index = cid.lastIndexOf("_");
			var c_result = cid.substr(c_index + 1);
				$.ajax({
					type: 'POST',
					url: 'php/comment_upvote',
					data:
					{
						cid: c_result,
						name: name,
					},
					cache: false,
				
					success: function(data){	
					if(name=="upvote"){
						$("#c_up_" + c_result).hide();
						$(".c_neutral_sc_" + c_result).hide();
						$(".c_downvoted_sc_" + c_result).hide();
						$("#c_remove_down_" + c_result).hide();
						$("#c_down_" + c_result).css({
								"display": "inline",
								"visibilty": "visible",
								});
						$("#c_remove_up_" + c_result).css({
								"display": "inline",
								"visibility": "visible",
								});
						$(".c_upvoted_sc_" + c_result).css({
								"display": "inline",
								"visibility": "visible",
						});
					}
					else if(name=="downvote"){
					$("#c_down_" + c_result).hide();
					$(".c_upvoted_sc_" + c_result).hide();
					$(".c_neutral_sc_" + c_result).hide();
					$("#c_remove_up_" + c_result).hide();
					$("#c_up_" + c_result).css({
							"display": "inline",
							"visibility": "visible",
							});
					$("#c_remove_down_" + c_result).css({
							"display": "inline",
							"visibility": "visible",
							});		
					$(".c_downvoted_sc_" + c_result).css({
							"display": "inline",
							"visibility": "visible",
					});
					}
					else if(name=="remove_upvote"){
						$("#c_remove_up_" + c_result).hide();
						$(".c_downvoted_sc_" + c_result).hide();
						$(".c_upvoted_sc_" + c_result).hide();
						$("#c_up_" + c_result).css({
							"display": "inline",
							"visibility": "visible",
							});
						$(".c_neutral_sc_" + c_result).css({
							"display": "inline",
							"visibility": "visible",
							})
					}
					else if(name=="remove_downvote"){
						$(".c_downvoted_sc_" + c_result).hide();
						$("#c_remove_down_" + c_result).hide();
						$("#c_down_" + c_result).css({
							"display": "inline",
							"visibility": "visible",
							});
						$(".c_neutral_sc_" + c_result).css({
							"display": "inline",
							"visibility": "visible",
						});
					}
				}	
			});
		e.preventDefault();	
	}


	function save_comment(comment_id,e){
		$.ajax({
			type: 'POST',
			url: 'php/save_comment.php',
			cache: false,
			data:
			{
				cid: comment_id,
			},
			success: function(data){
				$(".save_comment_" + comment_id).html(data);
	
			}
		});
		e.preventDefault();
	}
	
	function edit_comment(edit_button,e){
		var orig_content = $(edit_button).html();
		var id = $(edit_button).prop("id");
		var cut_id = id.lastIndexOf("_");
		var real_id = id.substr(cut_id + 1);
		var new_comment = "";
		var input_hidden_id = "";
		var comment = $(edit_button).closest("div").find(".post-comment:eq(0)");
		var comment_value = $(comment).html();
		var new_comment = "";
		var div_comment = $(edit_button).closest("div").find(".div-post-comment:eq(0)");
			if(typeof comment_value !== "undefined"){
			$(div_comment).html($("#edit_comment_div").html());
			var new_form  = $("#edit_c_form").attr("id", "edit_form_" + real_id);
			if(new_form){
			$("#edit_form_" + real_id).find(".edit_comment_media:eq(0)").attr("id", "edit_comment_media_" + real_id);
			$("#edit_form_" + real_id).find(".edit_comment_media_logo:eq(0)").attr("id", "edit_comment_media_logo_" + real_id);
			$("#edit_form_" + real_id).find(".edit_comment_media_logo:eq(0)").attr("for", "edit_comment_media_" + real_id);
			}
			var get_comment = $(new_form).find(".new_comment");
			var get_id = $(new_form).find(".c_id");
			new_id = $(get_id).val(real_id);
			new_comment = $(get_comment).html(comment_value).value;
				$(comment).css({
				"display": "none",
				"visibility": "hidden",
				});
			}
		e.preventDefault();
	}
	
	function cancel_edit(cancel_button,e){
		var edit_div = $(cancel_button).closest("div");
		var comment = $(edit_div).find(".new_comment").html();
		var id = $(cancel_button).attr("id");
		var parent_div = $(cancel_button).parents("div:eq(0)").html("<span style='margin-left:10px;word-wrap:break-word;' class='post-comment'>" + comment + "</span>");	
		
		
		e.preventDefault();
	}
	
	function save_edit_c(this_form,e){
		var editForm = new FormData();
		var comment_div = $(this_form).parents("div:eq(0)");
		var img_div = $(this_form).closest("div").parent().find(".media-container:eq(0)");
		var div = $(this_form).closest("div");
		var id = $(this_form).closest("form").find(".c_id:eq(0)").val();
		var comment = $(this_form).closest("form").find("textarea").val();
		var media = document.getElementById("edit_comment_media_" + id);
		var media_val = $(media).val();
		editForm.append ('c_id', id);
		editForm.append ('n_cm', comment);
		editForm.append ('edit_media', media.files[0]);
		if(comment && id){	
			$.ajax({
				type: 'POST',
				url:'php/edit_comment.php',
				data: editForm,
				processData: false,
				contentType: false,
				cache: false,
				success: function(data){
					$(img_div).html(data);
					comment_div.html(
					"<span style='margin-left:10px;word-wrap:break-word' class='post-comment'>" + comment + "</span>"
					);
				}
			});
		}
		else {
			alert("Comment cannot be empty!");
		}
		e.preventDefault();
	}
	
	//Edit Comment Media
	function edit_media(this_button,e){
		var edit_form = $(this_button).closest("form");
		var media_name = $(this_button).val();
		var first_cut = media_name.lastIndexOf(".");
		var second_cut = media_name.lastIndexOf("\\");
		var raw_name = media_name.substr(second_cut + 1);
		var get_id = $(this_button).attr("id");
		var media_id = document.getElementById(get_id);
		var x = $(edit_form).find(".clear_edit_media:eq(0)");
		var dummy_name = $(edit_form).find(".show_edit_media_name:eq(0)");
		var progress = $(edit_form).find(".edit_media_progress:eq(0)");
		var submit_button = $(edit_form).find(".save-edit-c:eq(0)");
		var extension = media_name.substr(first_cut + 1).toUpperCase();
			if(media_name==""){
				$(submit_button).prop("disabled", false);
				$(dummy_name).html("");
				$(progress).html("");
				$(x).css("display", "none");
			}
			else{
				$(x).css("display", "inline");
				$(dummy_name).show();
				$(dummy_name).html(raw_name);
				if(
					extension == "JPG"  ||
					extension == "JPEG" ||
					extension == "PNG"
				){
					//$(submit_button).prop("disabled", false);
					var editCommentPost = new FormData();
						editCommentPost.append('media_id', media_id.files[0]);
						$.ajax({
							type: 'POST',
							contentType: false,
							processData: false,
							data: editCommentPost,
							xhr: function() {
							var myXhr = $.ajaxSettings.xhr();
							if (myXhr.upload) {
								//For handling the progress of the upload
								myXhr.upload.addEventListener('progress', function(e) {
									if (e.lengthComputable) {
										var percentage = (e.loaded / e.total) * 100;
										$(progress).text(Math.floor(percentage) + '% File Completed');
										if(percentage==100){
											$(submit_button).prop("disabled", false);
										}
										else{
											$(submit_button).prop("disabled", true);
										}
									}
								} , false);
							}
							return myXhr;
						
							},

						});
	
				}
				else{
					$(submit_button).prop("disabled", true);
				}
			}
		e.preventDefault();
	}
	
	function clear_edit_media(this_button,e){
		var edit_form = $(this_button).closest("form");
		var media = $(edit_form).find(".edit_comment_media:eq(0)");
		var dummy_name = $(edit_form).find(".show_edit_media_name:eq(0)");
		var submit_button = $(edit_form).find(".save-edit-c:eq(0):eq(0)");
		var progress = $(edit_form).find(".edit_media_progress:eq(0)");
		$(this_button).hide();
		$(dummy_name).html("");
		
		$(media).val("");
		$(progress).text("");
		$(submit_button).prop("disabled", false);
		e.preventDefault();
	}
	
	function show_delete_confirmation(this_btn){
		var id = $(this_btn).attr("id");
		var cut_id = id.lastIndexOf("_");
		var real_id = id.substr(cut_id + 1);
		$("#delete_post_div").dialog({
			modal: true,
			resizable: false,
			open: function(event, ui){
				$('.ui-widget-overlay').bind('click', function(){
					$("#delete_post_div").dialog('close');
				});
			}
		});
		$("#delete_post_id").val(real_id);
		$("#delete_post_div").css({
			"display": "inline",
			"visibility": "visible",
		});
		$("#delete_post_btn").click(function(e){
			var nid = $("#delete_post_id").val();
			$.ajax({
				type: 'POST',
				url: 'php/delete_post.php',
				data:{
					nid: nid,
				},
				cache: false,
				success: function(data){
					$("#delete_post_div").dialog('close');
					$(
					"<center><a href='index.php'><button class='btn btn-info'style='border-radius:3px;background-color:lightblue;width:200px;height:50px;'>Post Deleted</button></a></center>"
					).dialog({
						modal: true,
						open: function(event, ui){
							$('.ui-dialog-titlebar-close', ui.dialog | ui).hide();
						}
					});

				},	
			});
			e.preventDefault();
		});
	}
	
	function not_delete(e){
		$("#delete_post_div").dialog("close");
		e.preventDefault();
	}
	
	function show_option_delete_comment(this_comment,e){
		var this_id = $(this_comment).attr("id");
		var div_id = $(this_comment).closest("div").attr("class");
		var cut_id = div_id.lastIndexOf("_");
		var real_id = div_id.substr(cut_id + 1);
		$(this_comment).html("<span style='color:red;'>Delete? </span>");
		$(
			"<span><a href='#' class='comment-delete' onclick='delete_comment(this,event)'>Yes</a> / <a href='#' class='comment-not-delete' onclick='no_delete(this,event)'>No</a></span>"
			).insertAfter(this_comment);	
		e.preventDefault();
		$("#" + this_id).removeAttr("onclick");
	}
	
	function no_delete(this_btn,e){
		$(this_btn).closest("ul").find(".delete_comment:first").attr("onclick", "show_option_delete_comment(this,event);");
		$(this_btn).closest("ul").find(".delete_comment:first").html("<span style='color:black;'><a href='#'>Delete</a></span>");
		$(this_btn).closest("span").hide();
		e.preventDefault();
	}
	
	
	function delete_comment(this_button,e){
		var div = $(this_button).closest("div");
		var str_id = $(div).attr("class");
		var cut_id = str_id.lastIndexOf("_");
		var real_id = str_id.substr(cut_id + 1);
			$.ajax({
				type: 'POST',
				url: 'php/delete_comment.php',
				data:{
					cid: real_id,
				},
				cache: false,
				success: function(data){
					$(this_button).closest("span").hide();
					$("." + str_id).find(".post-comment:first").html("<span style='color:grey;font-weight:bold;'>[Comment Deleted]</span>");
					$("." + str_id).find(".media-container:eq(0)").hide();
					$("." + str_id).find(".comment-score:eq(3)").hide();
					$("." + str_id).find(".comment-score:eq(0)").hide();
					$("." + str_id).find(".comment-score:eq(1)").hide();
					$("." + str_id).find(".c_vote:eq(0)").hide();
					$("." + str_id).find(".c_vote:eq(1)").hide();
					$("." + str_id).find(".c_vote:eq(2)").hide();
					$("." + str_id).find(".c_vote:eq(3)").hide();
					$("." + str_id).find("ul li:eq(0)").html("");	
					$("." + str_id).find("ul li:eq(1)").html("");	
					$("." + str_id).find("ul li:eq(2)").html("");	
					$("." + str_id).find("ul li:eq(3)").html("");	
					$("." + str_id).find("ul li:eq(4)").html("");	
					$("." + str_id).find("ul li:eq(5)").html("");	
					$("." + str_id).find("ul li:eq(6)").html("");					
					
					//$("." + str_id + " ul li").html("");
				},
			});
		e.preventDefault();
	}
	
	function show_edit_post(this_button,e){
		var div = $(this_button).closest("div");
		var description = $("#description").html();
		var id = $(div).attr("id");
		var cut_id = id.lastIndexOf("_");
		var real_id = id.substr(cut_id + 1);
		var new_description = $("#edit_p_desc").html(description);
		$("#edit_post_form").css("display", "inline");
		$("#description").html($("#edit_post_form").html());
		$("#edit_p_id").val(real_id);
		$("#edit_p_desc").focus();
	}
	
	function cancel_edit_p (this_button,e){
		var cancel_e_form = $(this_button).closest("form");
		var orig_content = $(cancel_e_form).find("textarea").html();
		$("#description").html(orig_content);
		$(cancel_e_form).hide();
		e.preventDefault();
	}
	
	function edit_post(this_button,e){
		var editPostForm = new FormData();
		var real_id = $("#edit_p_id").val();
		var description = $("#edit_p_desc").val();
		var media = document.getElementById("edit_post_media");
		var img_div = $("#post_media");
		editPostForm.append('nid', real_id);
		editPostForm.append('desc', description);
		editPostForm.append('edit_post_media', media.files[0]);
		if(real_id && description){
			$.ajax({
				type: 'POST',
				url: 'php/edit_post.php',
				data: editPostForm,
				success: function (data){
					$("#description").html(description);
					$(img_div).html(data);
					$("#edit_post_form").css("display", "none");
	
				},
				cache: false,
				processData: false,
				contentType: false,
			});
		}
		else{
			alert("Description cannot be empty!");
		}
	e.preventDefault();
	}
	
	function sort_comments(this_select,val){
		var div = $(this_select).closest("div").attr("id");
		var cut_id = div.lastIndexOf("_");
		var real_id = div.substr(cut_id + 1);
		window.location.href='newsfeed?research=' + real_id + '&sort=' + val;
	}
	
	function highlight(this_button,e){
		var parent_id = $(this_button).attr('href').replace("#", "");
		var parent_class = $("#" + parent_id);
		parent_class.addClass("highlight");
			setTimeout(function(){
				parent_class.removeClass("highlight");
		},1200);
	}
	
	function change_media_post(this_button,e){
		var media_name = $(this_button).val();
		var media_id = document.getElementById("edit_post_media");
		var first_cut = media_name.lastIndexOf("\\");
		var raw_media_name = media_name.substr(first_cut + 1);
		var second_cut = raw_media_name.lastIndexOf(".");
		var dummy_name = $("#show_edit_media_name");
		var clear_media = $("#clear_edit_media_post");
		var edit_button = $("#save_edit_post");
		var extension = raw_media_name.substr(second_cut + 1).toUpperCase();
		var progress = $("#post_media_progress");
		
			if(media_name==""){
				clear_media.css("display", "none");
				dummy_name.html("");
				$(progress).text("");
				$("#save_edit_post").prop("disabled", false);
			}
			else{
				if(
					extension == "JPG"  ||
					extension == "JPEG" ||
					extension == "PNG"
					){
						//$(edit_button).prop("disabled", false);
						var editMediaPost = new FormData();
						editMediaPost.append('media_id', media_id.files[0]);
						$.ajax({
							type: 'POST',
							contentType: false,
							processData: false,
							data: editMediaPost,
							xhr: function() {
							var myXhr = $.ajaxSettings.xhr();
							if (myXhr.upload) {
								//For handling the progress of the upload
								myXhr.upload.addEventListener('progress', function(e) {
									if (e.lengthComputable) {
										var percentage = (e.loaded / e.total) * 100;
										$(progress).text(Math.floor(percentage) + '% File Completed');
										if(percentage==100){
											$("#save_edit_post").prop("disabled", false);
										}
										else{
											$("#save_edit_post").prop("disabled", true);
										}
									}
								} , false);
							}
							return myXhr;
							},
						});
					}
					else{
						$(edit_button).prop("disabled", true);
					}
				clear_media.css("display", "inline");
				dummy_name.html(raw_media_name);
			}
		e.preventDefault();
	}
	
	function clear_edit_media_post(this_button,e){
		var edit_button = $("#save_edit_post");
		var dummy_name = $("#show_edit_media_name");
		var media = $("#edit_post_media");
		$(this_button).hide();
		$(dummy_name).html("");
		$(media).val("");
		$("#post_media_progress").text("");
		$(edit_button).prop("disabled", false);
		e.preventDefault();
	}
	
	function enlarge_image(this_image,e){
		var fake_id = $(this_image).attr("id");
		var cut_f_id = fake_id.lastIndexOf("_");
		var real_id = fake_id.substr(cut_f_id + 1);
		var img = document.getElementById(fake_id);
		var img_src = $(this_image).attr("src");
		var img_holder = document.getElementById("image-container");
		var img_div = $("#modal_image");
			$(img_div).css("display", "block");
			img_holder.src = img_src;
		e.preventDefault();
	}
	
	function close_img_div(this_span,e){
		var modal =  $("#modal_image");
		modal.css("display", "none");
		e.preventDefault();
	}
	
	function highlight(this_button,e){
		var parent_id = $(this_button).attr('href').replace("#", "");
		var parent_class = $("#" + parent_id);
		parent_class.addClass("highlight");
			setTimeout(function(){
				parent_class.removeClass("highlight");
			},1200);
		e.preventDefault();
	}
	
	function hide_section(this_section,e){
		var id = $(this_section).attr("id");
		var cut_id = id.lastIndexOf("_");
		var real_id = id.substr(cut_id + 1);
		var div = $("#pid_" + real_id);
		$(div).hide("animate");
		e.preventDefault();
	}
	
	function show_section(this_section,e){
		var id = $(this_section).attr("id");
		var cut_id = id.lastIndexOf("_");
		var real_id = id.substr(cut_id + 1);
		var div = $("#pid_" + real_id);
		$(div).show("animate");
		e.preventDefault();
	}
	
	
	
	
	