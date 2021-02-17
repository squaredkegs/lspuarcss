<!DOCTYPE html>
<?php
	include_once ("php/connection.php");
	include_once ("php/adminfunction.php");
	include_once ("php/queryadmindata.php");
	$adminid = adminLog();
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Homepage</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

	<!--For the table-->
	<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<link rel="stylesheet" href="css/jquery-ui.css">

	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>

    <script src="js/jquery-ui.min.js"></script>
	<script>
		function ask_confirmation(this_button,e){
			var form = $(this_button).closest("form");
			var aid = $(form).find(".admin-id:eq(0)").val();
			var rqst_date = $(form).find(".request-date:eq(0)").val();
			$(form).html("<label style='display:block;'>Are you sure?</label><input type='hidden' class='request-date' value='" + rqst_date + "'><input type='hidden' class='admin-id' value='" + aid + "'><button class='btn btn-info' style='margin-right:15px;' onclick='yes_approval(this,event);'>Yes</button><button class='btn btn-danger' onclick='no_approval(this,event);'>No</button>");
			e.preventDefault();
		}
		
		function no_approval(this_button,e){
			var form = $(this_button).closest("form");
			var aid = $(form).find(".admin-id:eq(0)").val();
			var rqst_date = $(form).find(".request-date:eq(0)").val();
			$(form).html("<input type='hidden' class='request-date' value='" + rqst_date + "'><input type='hidden' class='admin-id' value='" + aid + "'><input type='submit' value='Approve Extension' class='approve-request btn btn-info' onclick='ask_confirmation(this,event);'>");
		}
		
		function yes_approval(this_button,e){
			var form = $(this_button).closest("form");
			var rqst_date = $(form).find(".request-date:eq(0)").val();
			var parsedDate = new Date(Date.parse(rqst_date));
			var dateReadable = parsedDate.toDateString();
			var space_number = dateReadable.indexOf(" ");
			var new_dateReadable = dateReadable.substr(space_number);
			var td = $(this_button).closest("td");
			var aid = $(form).find(".admin-id").val();
				$.ajax({
					type: 'POST',
					url: 'php/approve_account_extension.php',
					data:
					{
						rqst_date : rqst_date,
						aid: aid,
					},
					cache: false,
					success: function(data){
						$(".button_" + aid).prop("disabled", true);
						$(".old-expire-date_" + aid).html(new_dateReadable);
						$(td).html("<span style='color:green;'>Successfully changed account expiration date</span>"); 
			
					},
				});
						
			e.preventDefault();
		}
		
		function expire_account(this_button,e){
			var dummy_id = $(this_button).attr("id");
				var cut_id = dummy_id.lastIndexOf("_");
				var real_id = dummy_id.substr(cut_id + 1);
				var td = $(this_button).closest("td");
				$(td).html("<label style='display:block;'>Expire Account?</label><button class='btn btn-info' style='width:100px;margin-right:5px;' id='approve_expire_" + real_id + "' onclick='yes_expire(this,event);'>Yes</button><button class='btn btn-danger' style='width:100px;margin-left:5px;' id='not_expire_" + real_id + "'onclick='not_expire(this,event);'>No</button>");
				e.preventDefault();
		}
		
		function not_expire(this_button,event){
			var dummy_id = $(this_button).attr("id");
			var cut_id = dummy_id.lastIndexOf("_");
			var real_id = dummy_id.substr(cut_id + 1);
			var td = $(this_button).closest("td");
				$(td).html("<button class='button_" + real_id + " btn btn-info extend-account' id='extend_account_" + real_id + "' onclick='show_extend_form(this,event);'>Extend Account</button> <button class='button_" + real_id + " btn btn-danger expire-account' id='expire_account_" + real_id + "' onclick='expire_account(this,event);'>Expire Account</button>");
			e.preventDefault();
		}
		
		function yes_expire (this_button,expire){
			var dummy_id = $(this_button).attr("id");
			var cut_id = dummy_id.lastIndexOf("_");
			var real_id = dummy_id.substr(cut_id + 1);
			var td = $(this_button).closest("td");
			var approve_extension_rqst = $(".approve-extension-" + real_id);
					$.ajax({
						type: 'POST',
						url: 'php/expire_admin_account.php',
						cache: false,
						data:
						{
							aid: real_id,
						},
						success: function(data){
							alert (data);
							$(td).css("color", "red");
							$(td).html("Account is now Expired");
							$(approve_extension_rqst).prop("disabled", true);
						},
					});
					
			e.preventDefault();
		}
		
		function show_extend_form(this_button,e){
			var dummy_id = $(this_button).attr("id");
			var cut_id = dummy_id.lastIndexOf("_");
			var real_id = dummy_id.substr(cut_id + 1);
			var td = $(this_button).closest("td");
			var approve_extension_rqst = $(".approve-extension-" + real_id);
				
				$("#real-admin-id").val(real_id);				
				$("#change-date-form").dialog({
					resizable: false,
					height: 220,
					width: 310,
					modal: true,
					open: function(event, ui){
					$('.ui-widget-overlay').bind('click', function(){
						$("#change-date-form").dialog('close');
					});
				}
				});
	
			e.preventDefault();
		}
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1;
		var yyyy = today.getFullYear();
		
		if(dd<10){
			dd = '0' + dd;
		}
		if(mm<10){
			mm = '0' + mm;
		}
		today = yyyy + '-' + mm + '-' + dd;
		$(document).ready(function(){
			$("#change-account-date").on('click', function(e){
				var id = $("#real-admin-id").val();
				var new_date = $("#new-expire-date").val();
				var new_date_string = Date.parse(new_date);
				
				var parsedDate = new Date(Date.parse(new_date));
				var dateReadable = parsedDate.toDateString();
				var space_number = dateReadable.indexOf(" ");
				var new_dateReadable = dateReadable.substr(space_number);
			
				var today_string = Date.parse(today);
				var td = $(".td-for-expiration-button-" + id);
				var button = $(".approve-extension-" + id);
				if(new_date){
					console.log (today_string);
					console.log (new_date_string);
					if(today_string>=new_date_string){
						$("#change-date-error").html("Invalid Date");
						$("#new-expire-date").focus();
					}
					else{
						$.ajax({
							type: 'POST',
							url: 'php/change_account_expiration_date.php',
							cache: false,
							data:
							{
								aid: id,
								new_date: new_date,
							},
							success: function(data){
								$("#change-date-form").dialog('close');
								alert(data);
								$(td).html("Account Expiration Change");
								$(button).prop("disabled", true);
								$(".old-expire-date_" + id).html(new_dateReadable);
							},
						});
					}
				}
				else{
					$("#new-expire-date").focus();
					$("#change-date-error").html("Input Date");
				}
				e.preventDefault();
			});
		});
	</script>
	<style>
	input[type=date]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    display: none;
	}
	</style>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

			<?php 
				include_once ("navbar.php");
				include_once ("sidebar.php");
			?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
				  <div class="box">
					<div class="box-header">
					
					  <h3 class="box-title">Expire</h3>

					</div><!-- /.box-header -->
					<div class="box-body">
					<table class="table table-bordered table-striped unique-class-table3">
						<thead>
						 <tr>
							<th>Name</th>
							<th>Campus/Dept.</th>
							<th>Account Expiration</th>
							<th>Account Request Extension</th>
							<th>Other Action</th>
						</tr>
						</thead>
						<tbody>
						<?php
							$query = $db -> prepare ("SELECT admin_id,fname,lname,status, accnt_expire,request_account_extension,campus, department FROM admin_tbl WHERE position='School Admin' ORDER BY lname ASC");
							$query -> execute();
							while($row = $query -> fetch(PDO::FETCH_ASSOC)){
							$fname = $row['fname'];
							$lname = $row['lname'];
							$request_date = $row['request_account_extension'];
							$account_expire = $row['accnt_expire'];
							$campus = $row['campus'];
							$raid = $row['admin_id'];
							$department = $row['department'];
							$fullname = $lname.", ".$fname;
							$account_expire = date("F d Y", strtotime($account_expire));
						?>
						<tr>
							<td style='width:150px;'><?php echo $fullname;?></td>
							<td style='width:220px;'><?php echo $campus."/".$department;?></td>
							<td class='old-expire-date_<?php echo $raid;?>' style='width:150px;'><?php echo $account_expire;?></td>
							<td style='width:250px;height:76px;' class='td-request-extension-<?php echo $raid;?>'>
								<?php
									if($request_date=='0000-00-00 00:00:00'){
										echo "No Request";
									}
									else{
										echo date("F d, Y", strtotime($request_date));
								?>
								<form action='#' method='POST'>
								<input type='hidden' class='request-date' value='<?php echo date("Y-m-d", strtotime($request_date));?>'>
								<input type='hidden' class='admin-id' value='<?php echo $raid;?>' readonly>
								<input type='submit' value='Approve Extension' class='approve-request btn btn-info approve-extension-<?php echo $raid;?>' onclick='ask_confirmation(this,event);'>
								</form>
								<?php
									}
								?>
								
							</td>
							<td class='td-for-expiration-button-<?php echo $raid;?>'>
								<button class='button_<?php echo $raid;?> btn btn-info extend-account' id='extend_account_<?php echo $raid;?>' onclick='show_extend_form(this,event);'>Extend Account</button>
								<button class='button_<?php echo $raid;?> btn btn-danger expire-account' id='expire_account_<?php echo $raid;?>' onclick='expire_account(this,event);'>Expire Account</button>
							</td>
							
						</tr>
						<?php
							}
						?>
						</tbody>
						<tfoot>
						 <tr>
							<th>Name</th>
							<th>Campus/Dept.</th>
							<th>Account Expiration</th>
							<th>Account Request Extension</th>
							<th>Other Action</th>
						</tr>
						</tfoot>
					  </table>
					
					</div><!-- /.box-body -->
				  </div>
		</div><!-- /.content-wrapper -->
		
		<form action='#' method='POST' id='change-date-form' class='form-inline' title='Change Account Expiration'>
			<input type='text' value='' id='real-admin-id'  readonly>
			<label style='display:block;'>New Account Date Expiration</label>
			<input type='date' id='new-expire-date' class='form-control' style='display:block;' required>
			<span id='change-date-error' style='color:red;margin-top:3px;margin-left:3px;margin-bottom:3px;'></span>
			<input type='submit' id='change-account-date' class='btn btn-info form-control' value='Change Date' style='display:block;margin-top:10px;'>
		</form>
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.3.0
        </div>
        <strong>Copyright &copy; 2016-2017 <a href="http://almsaeedstudio.com">Suicide Squad</a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar -->
      <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<!--DataTables-->
	<script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

    <!-- Slimscroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
	<!--Ito Yung Problema-->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
   <!-- <script src="dist/js/pages/dashboard.js"></script>
    -->
	<!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
	
	<!-- For Table Database-->
</body>
</html>
